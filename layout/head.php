<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Meta Data -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Title -->
	<title>Dr.nofl Chess page</title>
	<!-- swiper -->
	<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
	<!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Marcellus&display=swap">
	<!-- Css Animation -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
	<!-- Custom Style -->
	<link rel="stylesheet" href="<?php echo CSS_URL;?>custom-lib.css">
	<link rel="stylesheet" href="<?php echo CSS_URL;?>global.css">
	<?php if (isset($custom_style) && !empty($custom_style)):?>
		<link rel="stylesheet" href="<?php echo CSS_URL . $custom_style;?>">
	<?php endif;?>
</head>
<body>