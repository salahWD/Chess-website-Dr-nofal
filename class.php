<?php

class Router {
  private $handlers = [];
  private $not_found;
  private const GET_METHOD  = "GET";
  private const POST_METHOD = "POST";
  private const ID          = "{id}";
  private const PARAM       = "{param}";

  public function get($url, $handler) {
    $this->add_handler(self::GET_METHOD, $url, $handler);
  }

  public function get_admin($url, $handler) {
    $this->add_handler(self::GET_METHOD, $url, $handler, true/* admin check */);
  }

  public function post($url, $handler) {
    $this->add_handler(self::POST_METHOD, $url, $handler);
  }

  protected function add_handler($method, $url, $handler, $admin_check = false) {
    $this->handlers[$method . ":" . $url] = [
      "url" => $url,
      "method" => $method,
      "handler" => $handler,
      "admin_check" => $admin_check,
    ];
  }

  public function add_404($handler) {
    $this->not_found = $handler;
  }

  protected function has_id($url, $current_url) {
    /* $url => handler url like (=>url, funciton () {) */

    if (str_contains($url, self::ID)) {
      $path_arr = explode("/", $url);
      $id_index = array_search(self::ID, $path_arr);
      $url_arr = explode("/", $current_url);
      $id = isset($url_arr[$id_index]) ? intval($url_arr[$id_index]): null;
      if (is_numeric($id) && $id > 0) {
        return $id;
      }else {
        return false;
      }
    }

  }

  protected function has_param($url, $current_url) {
    /* $url => handler url like (=>url, funciton () {) */

      if (str_contains($url, self::PARAM)) {

        $path_arr = explode("/", $url);
        $id_index = array_search(self::PARAM, $path_arr);
        
        $url_arr = explode("/", $current_url);
        $title = isset($url_arr[$id_index]) && !empty($url_arr[$id_index]) ? $url_arr[$id_index]: null;

        if ($title != null && is_string($title) && strlen($title) > 0) {
          return $title;
        }else {
          return false;
        }

      }

  }

  public function run() {
    
    include_once CLASS_PATH . "permissionscheck.php";
    session_start();

    $is_admin       = PermissionsCheck::is_valid_admin_request();
    $request_parse  = parse_url($_SERVER["REQUEST_URI"]);
    $request_url    = $request_parse["path"] ?? "";
    $method         = $_SERVER["REQUEST_METHOD"];

    $callback = null;
    $id = null;
    $param = null;
    
    foreach($this->handlers as $handler) {
      
      $id = null;
      $param = null;
      
      if (($handler["admin_check"] === true && $is_admin) || $handler["admin_check"] === false) {

        $id = $this->has_id($handler["url"], $request_url);
        $param = $this->has_param($handler["url"], $request_url);

        if ($id != false) {
          
          $handler["url"] = str_replace(self::ID, $id, $handler["url"]);

          if ($request_url == $handler["url"] && $handler["method"] == $method) {
            $callback = $handler["handler"];
            break;
          }
          continue;
        }else if ($param != false) {

          $handler["url"] = str_replace(self::PARAM, $param, $handler["url"]);
          if ($request_url == $handler["url"] && $handler["method"] == $method) {
            
            $callback = $handler["handler"];
            break;
          }
          continue;
        }else if ($request_url == $handler["url"] && $handler["method"] == $method) {

          $callback = $handler["handler"];

        }
      }else {
        continue;
      }

    }

    if (!$callback) {
      header("Location: " . Router::route("404"));
      if (!empty($this->not_found)) {
        $callback = $this->not_found;
      }
    }

    call_user_func_array($callback, [
      array_merge($_GET, $_POST, $_FILES, ["id" => $id, "param" => $param])
    ]);
    exit();

  }

  public static function route($page) {
    return MASTER_URL . $page;
  }

  public static function set_session($name, $value) {
    if (!isset($_SESSION)) {
      session_start();
    }
    if (isset($_SESSION[$name])) {
      unset($_SESSION[$name]);
    }
    return $_SESSION[$name] = $value;
  }

  public static function get_session($name, $delete) {
    if (!isset($_SESSION)) {
      session_start();
    }
    if (isset($_SESSION[$name])) {
      $errors = $_SESSION[$name];
      if ($delete) {
        unset($_SESSION[$name]);
      }
      return $errors;
    }else {
      return null;
    }
  }

  public static function get_user() {
    if (!isset($_SESSION)) {
      session_start();
    }
    if (isset($_SESSION["user"])) {
      include_once MODELS_PATH . "user.php";
      $user = $_SESSION["user"];
      return unserialize($user);
    }else {
      return null;
    }
  }

  public static function isset_session($name) {
    if (!isset($_SESSION)) {
      session_start();
    }
    if (isset($_SESSION[$name])) {
      return true;
    }else {
      return false;
    }
  }

}

class Template {

  public static function view($page, $level = 3, $variables = null) {
    /*
      $page   => view page in /views/$page
      $level  => require level [
        1 => only the $page,
        2 => $page with head and body close,
        3 => $page with header and footer,
        4 => $page with the header,
        5 => $page with the footer,
      ]
    */
    if (!empty($variables)) {
      extract($variables);
    }
    
    if ($level > 1) {
      include_once LAYOUT_PATH . "head.php";
    }
    if ($level == 3 || $level == 4 ) {
      include_once LAYOUT_PATH . "header.php";
    }

    include_once VIEW_PATH . $page . ".php";// include the actual $page

    if ($level == 3 || $level == 5) {
      include_once LAYOUT_PATH . "footer.php";
    }
    if ($level > 1) {
      include_once LAYOUT_PATH . "close.php";
    }
  }

  public static function admin_view($page, $level = 3, $variables = null) {
    /*
      $page       => view page in /views/$page
      $level      => require level [
        1 => only the $page,
        2 => $page with head and body close,
        3 => $page with navbar,
      ]
      $variables  => array of values to pass to the view
    */
    if (!empty($variables)) {
      extract($variables);
    }
    
    if ($level > 1) {
      include_once LAYOUT_PATH . "dashboard_head.php";
    }
    if ($level == 3) {
      include_once MODELS_PATH . "admin.php";
      $admin = Admin::get_admin_session();
      include_once LAYOUT_PATH . "dashboard_navabr.php";
    }

    include_once VIEW_PATH . $page . ".php";// include the actual $page

    if ($level > 1) {
      include_once LAYOUT_PATH . "dashboard_close.php";
    }
  }

}


?>