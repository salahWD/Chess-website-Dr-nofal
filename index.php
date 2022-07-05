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
	include_once MODELS_PATH . "comment.php";
	include_once MODELS_PATH . "user.php";
	Template::admin_view("dashboard/index", 3, [
		"custom_style" 	=> "dashboard.css",
		"comments" 			=> Comment::get_recent_comments(6),
		"users" 				=> User::get_recent_users(6),
	]);
});
$router->get_admin("/dashboard/articles", function () {
	include_once MODELS_PATH . "blog.php";
	include_once MODELS_PATH . "admin.php";

	$token = Admin::get_admin_session("token");
	$articles = Blog::get_all_article();

	Template::admin_view("dashboard/articles", 3, [
		"custom_style" => "dashboard.css",
		"articles" => $articles,
		"token" => $token
	]);
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
	include_once MODELS_PATH . "article.php";
	
	$token 		= Admin::get_admin_session("token");
	$errors 	= Article::get_errors_session();
	$info 		= Article::get_info_holder_session();

	Template::admin_view("dashboard/article/create", 3, [
		"custom_style" => "dashboard.css",
		"token" 		=> $token,
		"article" 	=> $info,
		"errors" 		=> $errors,
	]);

});
$router->get_admin("/dashboard/courses", function () {

	include_once MODELS_PATH . "course.php";
	include_once MODELS_PATH . "admin.php";

	$courses = Course::get_all_courses();
	$token = Admin::get_admin_session("token");

	Template::admin_view("dashboard/courses", 3, [
		"custom_style" => "dashboard.css",
		"courses" => $courses,
		"token" => $token
	]);
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
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
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
$router->get_admin("/dashboard/users", function () {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "user.php";

	$token = Admin::get_admin_session("token");
	$users = User::get_users();

	Template::admin_view("dashboard/users", 3, [
		"custom_style" => "dashboard.css",
		"token" => $token,
		"users" => $users,
	]);

});
$router->get_admin("/dashboard/user/{id}", function ($args) {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "user.php";

	$token = Admin::get_admin_session("token");
	$user = User::get_user($args["id"]);

	if ($user != false) {
		Template::admin_view("dashboard/users/edit", 3, [
			"custom_style" => "dashboard.css",
			"user" => $user,
			"token" => $token
		]);
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
	}

});
$router->get_admin("/admin/profile", function ($args) {

	include_once MODELS_PATH . "admin.php";

	$admin = Admin::get_admin_session();

	Template::admin_view("dashboard/admin_profile", 3, ["custom_style" => "dashboard.css", "admin" => $admin]);

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
$router->post("/admin/profile", function ($args) {

	include_once MODELS_PATH . "admin.php";
	
	if (Admin::isset_admin_session()) {
		$admin = new Admin();
		$admin->set_id(Admin::get_admin_session("id"));
		$result = $admin->set_data($args);
		if ($result["success"]) {
			$saved = $admin->save();
			if ($saved["success"]) {
				Admin::set_admin_session(Admin::get_admin_db($admin->get_id()));
				header("Location: " . Router::route("admin/profile"));
				exit();
			}else {
				echo json_encode($saved);
				exit();
			}
		}else {
			echo json_encode($result);
			exit();
		}

	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
	}

});
$router->post("/api/article", function ($args) {

	include_once CLASS_PATH . "permissionscheck.php";

	$request = PermissionsCheck::is_valid_api_request($args, ["create", "get_content", "delete", "update"]);
	if ($request["success"]) {

		include MODELS_PATH . "article.php";
		
		$model = new Article();
		$action = $request["action"];
		
		if ($action == "create") {

			$result = $model->set($args);

			if ($result["success"]) {
				$inserted = $model->insert($request["id"]);
				if ($inserted["success"]) {
					header("Location: " . Router::route("dashboard/article/" . $inserted["id"]));
					exit();
				}else {
					Article::set_errors_session($inserted["error"]);
				}
			}else {
				Article::set_errors_session($result["errors"]);
			}
			Article::set_info_holder_session($result["info_holder"]);
			header("Location: " . Router::route("dashboard/article/create"));
			// $result = $admin->create_article($args);// the request coming from admin class to article class automaticly

		}else if ($action == "get_content") {
			$content = Article::get_content($args["article_id"]);
			echo json_encode(["success" => $content["success"], "content" => $content["content"] ?? "", "error" => $content["error"] ?? ""]);
		}else if ($action == "update") {

			$result = $model->set($args);
			if ($result["success"]) {
				$inserted = $model->save();
				echo json_encode(["success" => $inserted["success"], "errors" => $inserted["error"] ?? null]);
			}else {
				echo json_encode($result);
			}

		}else if ($action == "delete") {
			
			if (isset($args["article_id"]) && is_numeric(intval($args["article_id"])) && intval($args["article_id"]) > 0) {
				echo json_encode(Article::delete(intval($args["article_id"])));
			}else {
				echo json_encode(["success" => false]);
			}
			exit();

		}else {
			header("HTTP/1.0 404 Not Found");
			header("Location: " . Router::route("404"));
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
				}else {
					Course::set_errors_session($inserted["error"]);
				}
			}else {
				Course::set_errors_session($result["errors"]);
			}
			Course::set_info_holder_session($result["info_holder"]);
			header("Location: " . Router::route("dashboard/course/create"));
			exit();

		}else if ($action == "update") {

			$result = $model->set($args);
			
			if ($result["success"]) {
				$saved = $model->save();
				echo json_encode($saved);
			}else {
				echo json_encode(["success" => false, "errors" => $result["errors"]]);
			}

		}else if ($action == "delete") {

			if (isset($args["course_id"]) && is_numeric(intval($args["course_id"])) && intval($args["course_id"]) > 0) {
				echo json_encode(Course::delete(intval($args["course_id"])));
			}else {
				echo json_encode(["success" => false]);
			}
			exit();

		}else {
			header("HTTP/1.0 404 Not Found");
			header("Location: " . Router::route("404"));
		}
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
	}

});
$router->post("/api/user", function ($args) {

	include_once CLASS_PATH . "permissionscheck.php";
	$request = PermissionsCheck::is_valid_api_request($args, ["delete", "update"]);

	if ($request["success"]) {// checks for token and admin request action

		include MODELS_PATH . "user.php";
		
		$model = new User();
		$action = $request["action"];
		
		if ($action == "update") {

			$result = $model->set($args);
			
			if ($result["success"]) {
				$saved = $model->save();
				echo json_encode($saved);
			}else {
				echo json_encode(["success" => false, "errors" => $result["errors"]]);
			}

		}else if ($action == "delete") {

			if (isset($args["user_id"]) && is_numeric(intval($args["user_id"])) && intval($args["user_id"]) > 0) {
				echo json_encode(User::delete(intval($args["user_id"])));
			}else {
				echo json_encode(["success" => false]);
			}
			exit();

		}else {
			header("HTTP/1.0 404 Not Found");
			header("Location: " . Router::route("404"));
		}
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
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