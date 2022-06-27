<?php

class Router {
  private $handlers = [];
  private $not_found;
  private const GET_METHOD  = "GET";
  private const POST_METHOD = "POST";
  private const ID          = "{id}";

  public function get($url, $handler) {
    $this->add_handler(self::GET_METHOD, $url, $handler);
  }

  public function post($url, $handler) {
    $this->add_handler(self::POST_METHOD, $url, $handler);
  }

  public function add_handler($method, $url, $handler) {
    $this->handlers[$method . ":" . $url] = [
      "url" => $url,
      "method" => $method,
      "handler" => $handler,
    ];
  }

  public function add_404($handler) {
    $this->not_found = $handler;
  }

  public function run() {
    $request_url_parse = parse_url($_SERVER["REQUEST_URI"]);
    $request_url = $request_url_parse["path"];
    $method = $_SERVER["REQUEST_METHOD"];

    $callback = null;
    $id = null;
    foreach($this->handlers as $handler) {
      if (str_contains($handler["url"], self::ID)) {
        $path_arr = explode("/", $handler["url"]);
        $id_index = array_search(self::ID, $path_arr);
        if ($id_index != false) {
          $url_arr = explode("/", $request_url);
          $id = isset($url_arr[$id_index]) ? intval($url_arr[$id_index]): null;
          if (is_numeric($id)) {
            $handler["url"] = str_replace(self::ID, $id, $handler["url"]);
            if (str_contains($request_url, $handler["url"]) && $handler["method"] == $method) {
              $callback = $handler["handler"];
            }
          }
        }
      }else if (str_contains($request_url, $handler["url"]) && $handler["method"] == $method) {
        $callback = $handler["handler"];
      }
    }

    if (!$callback) {
      header("HTTP/1.0 404 Not Found");
      if (!empty($this->not_found)) {
        $callback = $this->not_found;
      }
    }

    call_user_func_array($callback, [
      array_merge($_GET, $_POST, [$id])
    ]);
    exit();

  }

  public static function route($page) {
    return MASTER_URL . $page;
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

}


?>