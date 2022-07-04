<?php

include_once "config.php";
include_once "class.php";
include_once "db.php";

$router = new Router();

$router->get("/", function () {
	Template::view("home", 3, ["custom_style" => "home.css"]);
});
$router->get("/about", function () {
	Template::view("about", 3, ["custom_style" => "about.css"]);
});
$router->get("/courses", function () {
	
	include_once MODELS_PATH . "course.php";
	$courses = Course::get_active_courses();
	
	Template::view("courses", 3, ["custom_style" => "courses.css", "courses" => $courses]);
});
$router->get("/course/{id}", function ($params) {// params => [$_POST, $_GET, [$id == {id}]]

	include_once MODELS_PATH . "course.php";
	$model = new Course();
	$course = Course::get_active_course($params['id']);
	
	if ($course !== false) {
		Template::view("single-course", 3, ["custom_style" => "single-course.css", "course" => $course]);
	}else {
		header("Location: " . Router::route("404"));
		exit();
	}
});
$router->get("/blog", function () {
	
	include_once MODELS_PATH . "blog.php";
	$articles = Blog::get_active_articles();
	
	Template::view("blog", 3, ["custom_style" => "blog.css", "articles" => $articles]);
});
$router->get("/blog/{param}", function ($params) {

	include_once MODELS_PATH . "blog.php";
	$article = Blog::get_article_title($params["title"]);

	if ($article !== false) {
		Template::view("article", 3, ["custom_style" => "article.css", "article" => $article]);
	}else {
		header("Location: " . Router::route("404"));
		exit();
	}
});
$router->get("/contact", function () {
	Template::view("contact", 3, ["custom_style" => "contact.css"]);
});
$router->get_admin("/dashboard", function () {
	include_once MODELS_PATH . "admin.php";
	Template::admin_view("dashboard/index", 3, ["custom_style" => "dashboard.css"]);
});
$router->get_admin("/dashboard/articles", function () {
	include_once MODELS_PATH . "blog.php";
	$articles = Blog::get_all_article();
	Template::admin_view("dashboard/articles", 3, ["custom_style" => "dashboard.css", "articles" => $articles]);
});
$router->get_admin("/dashboard/article/{id}", function ($args) {
	include_once MODELS_PATH . "article.php";
	$article = Article::get_article($args["id"]);
	if ($article != false) {
		Template::admin_view("dashboard/article/edit", 3, ["custom_style" => "dashboard.css", "article" => $article, "token" => 'B&!$UmU+36']);
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
	}
});
$router->get_admin("/dashboard/article/create", function ($args) {
	
	include_once MODELS_PATH . "admin.php";
	$token = Admin::get_admin_session("token");
	
	Template::admin_view("dashboard/article/create", 3, [
		"custom_style" => "dashboard.css",
		"token" 		=> $token,
		"article" 	=> $_SESSION["article/info"] ?? "",
		"errors" 		=> $_SESSION["article/error"] ?? "",
	]);
	if (isset($_SESSION["article/error"])) {
		unset($_SESSION["article/error"]);
	}
	if (isset($_SESSION["article/info"])) {
		unset($_SESSION["article/info"]);
	}

});
$router->get_admin("/dashboard/courses", function () {
	include_once MODELS_PATH . "course.php";
	$courses = Course::get_all_courses();
	Template::admin_view("dashboard/courses", 3, ["custom_style" => "dashboard.css", "courses" => $courses]);
});
$router->get_admin("/dashboard/course/{id}", function ($args) {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "course.php";

	$token = Admin::get_admin_session("token");
	$course = Course::get_course($args["id"]);

	if ($course != false) {
		Template::admin_view("dashboard/course/edit", 3, [
			"custom_style" => "dashboard.css",
			"course" => $course,
			"token" => $token
		]);
	}else {
		// header("HTTP/1.0 404 Not Found");
		// header("Location: " . Router::route("404"));
	}

});
$router->get_admin("/dashboard/course/create", function ($args) {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "course.php";

	$token = Admin::get_admin_session("token");

	$errors 	= Course::get_errors_session();
	$info 		= Course::get_info_holder_session();

	Template::admin_view("dashboard/course/create", 3, [
		"custom_style" => "dashboard.css",
		"token" => $token,
		"errors" => $errors,
		"info_holder" => $info,
	]);

});
$router->get("/dashboard/login", function () {
	
	if (isset($_SESSION["admin"]) && !empty($_SESSION["admin"])) {
		header("Location: " . Router::route("dashboard"));
	}else {
		Template::admin_view("dashboard/login", 2, ["custom_style" => "login.css"]);
	}
	
});
$router->get("/logout", function () {

	session_start();
	unset($_SESSION);
	$_SESSION = [];
	session_destroy();
	header("Location: " . Router::route(""));
	exit();

});

/* ======== POST REQUESTS AND API ======== */

$router->post("/dashboard/login", function ($args) {
	
	include_once MODELS_PATH . "admin.php";
	$admin = new Admin();
	$res = $admin->login($args);

	if ($res["success"]) {
		session_start();
		$admin::set_admin_session($res["admin"]);
		header("Location: " . Router::route("dashboard"));
	}else {
		header("Location: " . Router::route("dashboard/login"));
	}
	exit();

});
$router->post("/api/article", function ($args) {

	include_once CLASS_PATH . "permissionscheck.php";

	$request = PermissionsCheck::is_valid_api_request($args, ["create", "get_content", "delete", "update"]);

	if ($request["success"]) {

		include MODELS_PATH . "article.php";
		
		$model = new Article();
		$action = $request["action"];
		// $admin = Admin::get_admin_session();
		
		if ($action == "create") {

			$model->set($args);
			// $result = $admin->create_article($args);
			$result = $model->insert($request["id"]);

			if ($result["success"]) {
				header("Location: " . Router::route("dashboard/article/" . $result["id"]));
				exit();
			}else {
				unset($_SESSION["article/info"]);
				unset($_SESSION["article/error"]);
				$_SESSION["article/info"] 	= $model;
				$_SESSION["article/error"] 	= $result["error"];
				header("Location: " . Router::route("dashboard/article/create"));
				exit();
			}
		}else if ($action == "get_content") {
			$content = Article::get_content($args["article_id"]);
			echo json_encode(["success" => $content["success"], "content" => $content["content"] ?? "", "error" => $content["error"] ?? ""]);
		}else if ($action == "update") {
			$model->set($args);
			$result = $model->save();
			echo json_encode(["success" => $result["success"], "error" => $result["error"] ?? ""]);
		}

		exit();
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
		exit();
	}

});
$router->post("/api/course", function ($args) {

	include_once CLASS_PATH . "permissionscheck.php";

	$request = PermissionsCheck::is_valid_api_request($args, ["create", "delete", "update"]);

	if ($request["success"]) {// checks for token and admin request action

		include MODELS_PATH . "course.php";
		
		$model = new Course();
		$action = $request["action"];
		
		if ($action == "create") {
			
			$result = $model->set($args);

			if ($result["success"]) {

				$inserted = $model->insert($request["id"]);
				// $result = $admin->create_course($args);

				if ($inserted["success"]) {
					header("Location: " . Router::route("dashboard/course/" . $inserted["id"]));
					exit();
				}
				
			}else {

				Course::set_errors_session($result["errors"]);
				Course::set_info_holder_session($result["info_holder"]);

			}

			header("Location: " . Router::route("dashboard/course/create"));

		}/* else if ($action == "get_content") {
		}else if ($action == "update") {
		} */

		exit();
	}else {
		echo "<h1>no token or action</h1>";
		// header("HTTP/1.0 404 Not Found");
		// header("Location: " . Router::route("404"));
		exit();
	}

});
$router->post("/api/upload/image", function ($args) {

	$exts 			= ["image/jpeg", "image/png", "image/webp"];
	$exts_name 	= ["jpeg", "jpg", "png", "webp"];
	$name_arr 	=  explode(".", $args["image"]["name"]);
	$ext 				= end($name_arr);
	$error 			= [];

	if (!in_array($args["image"]["type"], $exts)) {
		$error[] = "file type is not allowed";
	}
	if (!in_array($ext, $exts_name)) {
		$error[] = "file type is not allowed";
	}
	if (!(((intval($args["image"]["size"]) / 1024) / 1024) < 3)) {// size < 3MB
		$error[] = "file size is too large (max = 3MB)";
	}

	if (count($error) > 0) {
		echo json_encode([
			"success" => 0,
			"errors" => $error,
		]);
	}else {
		include_once CLASS_PATH . "image.php";
		$img_name = Image::generate_name(10) . "." . $ext;
		$result = move_uploaded_file($args["image"]["tmp_name"], UPLOADS_IMAGES . $img_name);
		echo json_encode([
			"success" => 1,
			"file" => [
				"url" => Router::route("uploads/img/" . $img_name),
			],
		]);
	}
});
$router->post("/api/fetch/image", function ($args) {

	// $exts 			= ["image/jpeg", "image/png", "image/webp"];
	// $exts_name 	= ["jpeg", "jpg", "png", "webp"];
	// $name_arr 	=  explode(".", $args["image"]["name"]);
	// $ext 				= end($name_arr);
	// $error 			= [];

	// if (!in_array($args["image"]["type"], $exts)) {
	// 	$error[] = "file type is not allowed";
	// }
	// if (!in_array($ext, $exts_name)) {
	// 	$error[] = "file type is not allowed";
	// }
	// if (!(((intval($args["image"]["size"]) / 1024) / 1024) < 3)) {// size < 3MB
	// 	$error[] = "file size is too large (max = 3MB)";
	// }

	// if (count($error) > 0) {
	// 	echo json_encode([
	// 		"success" => 0,
	// 		"errors" => $error,
	// 	]);
	// }else {
	// 	$img_name = Image::generate_name(10) . "." . $ext;
	// 	$result = move_uploaded_file($args["image"]["tmp_name"], UPLOADS_IMAGES . $img_name);
	// 	echo json_encode([
	// 		"success" => 1,
	// 		"file" => [
	// 			"url" => Router::route("uploads/img/" . $img_name),
	// 		],
	// 	]);
	// }
});

$router->add_404(function () {
	header("HTTP/1.0 404 Not Found");
	Template::view("404", 4, ["custom_style" => "404.css"]);
});

$router->run();

?>