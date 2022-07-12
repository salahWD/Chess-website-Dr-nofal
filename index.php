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
$router->get("/lecture/{param}", function ($params) {
	include_once MODELS_PATH . "lecture.php";
	
	if (Router::isset_session("user")) {
		include_once MODELS_PATH . "user.php";
		
		$user = Router::get_user();
		$lecture = Lecture::get_lecture_title($params["param"]);
	
		if (!empty($lecture) && get_class($lecture) == "Lecture") {
			Template::view("lecture", 3, ["custom_style" => "lecture.css", "lecture" => $lecture]);
		}else {
			header("HTTP/1.0 404 Not Found");
			header("Location: " . Router::route("404"));
			exit();
		}
		
	}else {
		header("HTTP/1.0 404 Not Found");
		header("location: " . Router::route("404"));
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
	include_once MODELS_PATH . "article.php";
	
	if (Router::isset_session("user")) {
		include_once MODELS_PATH . "user.php";
		$user = Router::get_user();
	}

	$article = Blog::get_article_title($params["param"]);
	$article->is_liked($user->id ?? null);
	$comments = Article::get_comments($article->id);

	if ($article !== false) {
		Template::view("article", 3, [
			"custom_style" => "article.css",
			"article" => $article,
			"user" => $user ?? null,
			"comments" => $comments,
		]);
	}else {
		header("Location: " . Router::route("404"));
		exit();
	}
});
$router->get("/contact", function () {
	Template::view("contact", 3, ["custom_style" => "contact.css"]);
});
$router->get("/login", function () {
	include_once MODELS_PATH . "user.php";

	if (!Router::isset_session("user")) {

		if (Router::isset_session("signup/stage")) {
			Template::view("signup-stage-two", 4, [
				"custom_style" => "login.css",
				"signup_errors" => Router::get_session("user/signup/errors", true),
				"signup_info" => Router::get_session("user/signup/info", true)
			]);
		}else {
			Template::view("login", 4, [
				"custom_style" 	=> "login.css",
				"login_errors" 	=> Router::get_session("user/login/errors", true),
				"signup_errors" => Router::get_session("user/signup/errors", true),
				"login_info" 		=> Router::get_session("user/login/info", true),
				"signup_info"		=> Router::get_session("user/signup/info", true),
			]);
		}
	}else {
		header("location: " . Router::route(""));
		exit();
	}

});
$router->get("/user/profile", function () {
	include_once MODELS_PATH . "user.php";

	if (Router::isset_session("user")) {

		$errors = Router::get_session("user/errors", true);

		Template::view("profile", 4, [
			"custom_style" 	=> "login.css",
			"errors" 				=> $errors,
		]);

	}else {
		header("HTTP/1.0 404 Not Found");
		header("location: " . Router::route("404"));
		exit();
	}

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

	$articles = Blog::get_all_article();

	Template::admin_view("dashboard/articles", 3, [
		"custom_style" => "dashboard.css",
		"articles" => $articles,
	]);
});
$router->get_admin("/dashboard/article/{id}", function ($args) {
	include_once MODELS_PATH . "article.php";
	$article = Article::get_article($args["id"]);
	if ($article != false) {
		Template::admin_view("dashboard/article/edit", 3, ["custom_style" => "dashboard.css", "article" => $article]);
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
	}
});
$router->get_admin("/dashboard/article/create", function ($args) {
	
	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "article.php";
	
	$errors 	= Article::get_errors_session();
	$info 		= Article::get_info_holder_session();

	Template::admin_view("dashboard/article/create", 3, [
		"custom_style" => "dashboard.css",
		"article" 	=> $info,
		"errors" 		=> $errors,
	]);

});
$router->get_admin("/dashboard/courses", function () {

	include_once MODELS_PATH . "course.php";
	include_once MODELS_PATH . "admin.php";

	$courses = Course::get_all_courses();

	Template::admin_view("dashboard/courses", 3, [
		"custom_style" => "dashboard.css",
		"courses" => $courses,
	]);
});
$router->get_admin("/dashboard/course/{id}", function ($args) {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "course.php";

	$course = Course::get_course($args["id"]);

	if ($course != false) {
		Template::admin_view("dashboard/course/edit", 3, [
			"custom_style" => "dashboard.css",
			"course" => $course,
		]);
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("404"));
	}

});
$router->get_admin("/dashboard/course/create", function ($args) {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "course.php";

	$errors 	= Course::get_errors_session();
	$info 		= Course::get_info_holder_session();

	Template::admin_view("dashboard/course/create", 3, [
		"custom_style" => "dashboard.css",
		"errors" => $errors,
		"info_holder" => $info,
	]);

});
$router->get_admin("/dashboard/users", function () {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "user.php";

	$users = User::get_users();

	Template::admin_view("dashboard/users", 3, [
		"custom_style" => "dashboard.css",
		"users" => $users,
	]);

});
$router->get_admin("/dashboard/user/{id}", function ($args) {

	include_once MODELS_PATH . "admin.php";
	include_once MODELS_PATH . "user.php";

	$user = User::get_user($args["id"]);

	if ($user != false) {
		Template::admin_view("dashboard/users/edit", 3, [
			"custom_style" => "dashboard.css",
			"user" => $user,
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

	include_once MODELS_PATH . "admin.php";

	if (Router::isset_session("admin")) {
		header("Location: " . Router::route("dashboard"));
	}else {
		$errors = Admin::get_errors();
		Template::admin_view("dashboard/login", 2, ["custom_style" => "login.css", "errors" => $errors]);
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

$router->post("/login", function ($args) {
	include_once MODELS_PATH . "user.php";
	
	$user = new User();
	$res = $user->login($args);
	if ($res["success"]) {
		Router::set_session("user", base64_encode(serialize($res["user"])));
		header("Location: " . Router::route(""));
		exit();
	}else {
		Router::set_session("user/login/info", $res["login_holder"]);
		Router::set_session("user/login/errors", $res["login_errors"]);
		header("Location: " . Router::route("login"));
	}
	exit();

});
$router->post("/signup", function ($args) {
	
	include_once MODELS_PATH . "user.php";
	$user = new User();

	if (Router::isset_session("signup/stage")) {
		
		$res = $user->signup_stage_two($args);
		
		if ($res["success"]) {
			Router::set_session("user", base64_encode(serialize($res["user"])));
			header("Location: " . Router::route(""));
			exit();
		}
	}else {

		$res = $user->signup_stage_one($args);

		if ($res["success"]) {

			header("Location: " . Router::route("login"));
			exit();
		}
	}

	Router::set_session("user/signup/errors", $res["errors"]);
	Router::set_session("user/signup/info", $res["signup_holder"]);
	header("Location: " . Router::route("login#signup"));
		
});
$router->post("/user/profile", function ($args) {
	
	include_once MODELS_PATH . "user.php";
	if (Router::isset_session("user")) {
		// $user = new User();
		$user = Router::get_user();

		$args["user_id"] = $user->id;
		$res = $user->set_update($args);
		
		if ($res["success"]) {
			$res = $user->save();
			if ($res["success"]) {
				$user = User::get_user($user->id);
				Router::set_session("user", base64_encode(serialize($user)));
				header("Location: " . Router::route("user/profile"));
				exit();
			}else {
				Router::set_session("user/errors", $res["errors"]);
				header("Location: " . Router::route("user/profile"));
				exit();
			}
			exit();
		}else {
			Router::set_session("user/errors", $res["errors"]);
			header("Location: " . Router::route("user/profile"));
			exit();
		}
	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route("login"));
		exit();
	}
	
});
$router->post("/dashboard/login", function ($args) {
	
	include_once MODELS_PATH . "admin.php";
	$admin = new Admin();
	$res = $admin->login($args);
	if ($res["success"]) {
		session_start();
		Admin::set_admin_session($res["admin"]);
		header("Location: " . Router::route("dashboard"));
	}else {
		Admin::set_errors($res["errors"]);
		header("Location: " . Router::route("dashboard/login"));
	}
	exit();

});
$router->post("/add/comment", function ($args) {
	
	include_once MODELS_PATH . "user.php";
	include_once MODELS_PATH . "comment.php";
	
	$user = Router::get_user();
	if ($user != null) {
		$comment = $args["comment"] ?? null;
		if (!empty($comment) && is_string($comment) && strlen($comment) > 0) {

			$comment = Comment::valid_comment($comment);
			
			if (is_string($comment) && strlen($comment) > 0) {
				$replaid_id = isset($args["replied_id"]) ? abs(intval($args["replied_id"])) : null;
				$article_id = isset($args["article_id"]) ? abs(intval($args["article_id"])) : null;
				if (is_numeric($replaid_id) && $replaid_id > 0) {
					$res = Comment::add_comment($user->id, $comment, $article_id, $replaid_id);
				}else {
					$res = Comment::add_comment($user->id, $comment, $article_id);
				}
				if ($res) {
					echo json_encode(["success" => true,]);
				}else {
					echo json_encode(["success" => false,"errors" => "something went wrong!"]);
					exit();
				}
			}else {
				echo json_encode(["success" => false,"errors" => "comment is not valid!"]);
				exit();
			}
		}else {
			echo json_encode(["success" => false,"errors" => "comment cannot be sent empty!"]);
			exit();
		}
		exit();
	}else {
		header("HTTP/1.0 404 Not Found");
		echo json_encode(["success" => false]);
		exit();
	}

});
$router->post("/comment/replies", function ($args) {
	
	include_once MODELS_PATH . "user.php";
	include_once MODELS_PATH . "comment.php";
	
	$comment_id = intval($args["comment_id"]) ?? null;
	if (!empty($comment_id) && is_numeric($comment_id) && $comment_id > 0) {
		echo json_encode(Comment::get_replies($comment_id, intval($args["offset"])));
	}else {
		header("HTTP/1.0 404 Not Found");
		echo json_encode(["success" => false]);
		exit();
	}

});
$router->post("/add/like", function ($args) {
	
	if (Router::isset_session("user")) {

		include_once MODELS_PATH . "user.php";
		include_once MODELS_PATH . "article.php";
		
		$article_id = intval($args["article_id"]) ?? null;
		$user = Router::get_user();
	
		if (!empty($article_id) && is_numeric($article_id) && $article_id > 0) {
			echo json_encode(Article::like($article_id, $user->id));
		}else {
			header("HTTP/1.0 404 Not Found");
			echo json_encode(["success" => false]);
			exit();
		}

	}else {
		
	}

});
$router->post("/admin/profile", function ($args) {

	include_once MODELS_PATH . "admin.php";
	
	if (Router::isset_session("admin")) {
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

	$actions = ["create", "get_content", "delete", "update"];
	if (Router::isset_session("admin") && isset($args["action"]) && in_array($args["action"], $actions)) {

		include_once MODELS_PATH . "article.php";
		include_once MODELS_PATH . "admin.php";

		$id = Admin::get_admin_session("id");

		$model = new Article();
		$action = $args["action"];
		
		if ($action == "create") {

			$result = $model->set($args);

			if ($result["success"]) {
				$inserted = $model->insert($id);
				if ($inserted["success"]) {
					header("Location: " . Router::route("dashboard/article/" . $inserted["id"]));
					exit();
				}else {
					Article::set_errors_session($inserted["errors"]);
				}
			}else {
				Article::set_errors_session($result["errors"]);
			}
			Article::set_info_holder_session($result["info_holder"]);
			header("Location: " . Router::route("dashboard/article/create"));
			exit();
			// $result = $admin->create_article($args);// the request coming from admin class to article class automaticly

		}else if ($action == "get_content") {
			if (isset($args["article_id"]) && is_numeric(intval($args["article_id"])) && intval($args["article_id"]) > 0) {
				$content = Article::get_content($args["article_id"]);
				echo json_encode(["success" => $content["success"], "content" => $content["content"] ?? "", "error" => $content["error"] ?? ""]);
			}else {
				header("HTTP/1.1 404 Not Found");
				echo json_encode(["success" => false, "content" => "", "error" => 1]);
				exit();
			}
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

	$actions = ["create", "update", "delete"];
	if (Router::isset_session("admin") && isset($args["action"]) && in_array($args["action"], $actions)) {

		include MODELS_PATH . "course.php";

		$admin = Admin::get_admin_session();
		$model = new Course();
		$action = $args["action"];
		
		if ($action == "create") {
			
			$result = $model->set($args);
			if ($result["success"]) {
	
				$inserted = $model->insert($admin->id);
	
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
		exit();
	}


});
$router->post("/api/user", function ($args) {

	$actions = ["update", "delete"];
	if (Router::isset_session("admin") && isset($args["action"]) && in_array($args["action"], $actions)) {
		
		include MODELS_PATH . "user.php";
		$action = $request["action"];
		$model = new User();
		
		if ($action == "update") {

			$result = $model->set_update($args);
			
			if ($result["success"]) {
				echo json_encode($model->save());
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

	include_once CLASS_PATH . "formvalidate.php";
	$res = Form::valid_image($args["image"]);

	if ($res["success"]) {
		$uploaded = move_uploaded_file($res["info"]["tmp_name"], UPLOADS_IMAGES . $res["info"]["new_name"]);
		echo json_encode(["success" => 1,"file" => ["url" => Router::route("uploads/img/" . $res["info"]["new_name"])]]);
	}else {
		echo json_encode($res);
	}

});
$router->post("/api/fetch/image", function ($args) {

	if (Router::isset_session("admin")) {

		include_once CLASS_PATH . "image.php";
		$request_body = file_get_contents('php://input');
		$json_url = json_decode($request_body);
		
		$url_arr 				= explode("/", $json_url->url);
		$file_name 			= end($url_arr);
		$file_ext_arr 	= explode(".", $file_name);
		$file_ext 			= end($file_ext_arr);
		$exts_name 			= ["jpeg", "jpg", "png", "webp"];
	
		if (in_array($file_ext, $exts_name)) {
	
			$name = Image::generate_name(10) . "." . $file_ext;
		
			try {

				$ch = curl_init($json_url->url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$rawdata	=	curl_exec($ch);
				curl_close($ch);
				
				$fp = fopen(UPLOADS_IMAGES . $name,'w');
				fwrite($fp, $rawdata);
				fclose($fp);

				echo json_encode(["success" => 1, "file" => ["url" => Router::route("uploads/img/$name")]]);

			} catch (\Throwable $th) {
				echo json_encode(["success" => 0]);
			}
			
		}else {
			echo json_encode(["success" => 0]);
		}

	}else {
		header("HTTP/1.0 404 Not Found");
		header("Location: " . Router::route(""));
		exit();
	}

});

$router->add_404(function () {
	header("HTTP/1.0 404 Not Found");
	Template::view("404", 4, ["custom_style" => "404.css"]);
});

$router->run();

?>