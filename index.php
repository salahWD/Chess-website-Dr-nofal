<?php

include_once "config.php";
include_once "class.php";
include_once "db.php";
include_once MODELS_PATH . "course.php";

$router = new Router();

$router->get("/", function () {
	Template::view("home", 3, ["custom_style" => "style.css"]);
});
$router->get("/about", function () {
	Template::view("about", 3, ["custom_style" => "about.css"]);
});
$router->get("/courses", function () {
	$model = new Course();
	$courses = $model->get_all();
	Template::view("courses", 3, ["custom_style" => "courses.css", "courses" => $courses]);
});
$router->get("/course/{id}", function ($id) {
	$model = new Course();
	$course = $model->get($id[0]);
	Template::view("single-course", 3, ["custom_style" => "single-course.css", "course" => $course]);
});

$router->add_404(function () {
	Template::view("404", 4, ["custom_style" => "404.css"]);
});

$router->run();
?>