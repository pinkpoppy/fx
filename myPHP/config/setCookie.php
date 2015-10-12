<?php

	function setCustomerCookie() {
		setCookie("userId",$_GET["userId"],time()+(86400 * 30),"/");
		$_COOKIE["userId"] = $_GET["userId"];

		setCookie("userType",$_GET["userType"],time()+(86400 * 30),"/");
		$_COOKIE["userType"] = $_GET["userType"];

		setCookie("city",$_GET["city"],time()+(86400 * 30),"/");
		$_COOKIE["city"] = $_GET["city"];

		setCookie("lng",$_GET["lng"],time()+(86400 * 30),"/");
		$_COOKIE["lng"] = $_GET["lng"];

		setCookie("lat",$_GET["lat"],time()+(86400 * 30),"/");
		$_COOKIE["lat"] = $_GET["lat"];

		setCookie("width",$_GET["width"],time()+(86400 * 30),"/");
		$_COOKIE["width"] = $_GET["width"];
	}
	
	// function setCustomerCookie() {
	// 	$id = "userId";
	// 	$ty = "userType";
	// 	$pro = "province";
	// 	$cy = "city";
	// 	$aa = "sublocal";
	// 	$rd = "road";
	// 	$ln = "lng";
	// 	$lt = "lat";
	// 	$w = "width";

	// 	if (isset($_GET["userId"])) {
	// 		$userID = $_GET["userId"];
	// 		setcookie($id,$userID,time() + (86400 * 30),"/");
	// 		$_COOKIE["userId"] = $userID;
	// 	}
	// 	if (isset($_GET["userType"])) {
	// 		$userType = $_GET["userType"];
	// 		setcookie($ty,$userType,time() + (86400 * 30),"/"); $_COOKIE[$ty] = $userType;
	// 	}
	// 	if (isset($_GET["province"])) {
	// 		$province = $_GET["province"];
	// 		setcookie($pro,$province,time() + (86400 * 30),"/"); $_COOKIE[$pro] = $province;
	// 	}
	// 	if (isset($_GET["city"])) {
	// 		$city = $_GET["city"];
	// 		setcookie($cy,$city,time() + (86400 * 30),"/"); $_COOKIE[$cy] = $city;
	// 	}
	// 	if (isset($_GET["sublocal"])) {
	// 		$area = $_GET["sublocal"];
	// 		setcookie($aa,$area,time() + (86400 * 30),"/"); $_COOKIE[$aa] = $area;
	// 	}
	// 	if (isset($_GET["road"])) {
	// 		$road = $_GET["road"];
	// 		setcookie($rd,$road,time() + (86400 * 30),"/"); $_COOKIE[$rd] = $road;
	// 	}
	// 	if (isset($_GET["lng"])) {
	// 		$lng = $_GET["lng"];
	// 		setcookie($ln,$lng,time() + (86400 * 30),"/");  $_COOKIE[$ln] = $lng;
	// 	}
	// 	if (isset($_GET["lat"])) {
	// 		$lat = $_GET["lat"];
	// 		setcookie($lt,$lat,time() + (86400 * 30),"/");  $_COOKIE[$lt] = $lat;
	// 	}
	// 	if (isset($_GET["width"])) {
	// 		$width = $_GET["width"];
	// 		setcookie($w,$width,time() + (86400 * 30),"/"); $_COOKIE[$w] = $width;
	// 	}

	// 	$userID = '2155963685';
	// 	$userType = '2';
	// 	$city = 'x';
	// 	$width = '320';
	// 	$province = '广东省';	
	// 	$area = '天河区';
	// 	$road = '黄埔大道100号';
	// 	$lng = '121.506538';
	// 	$lat = '31.307096';
		

	// 	// $userID = 'x';
	// 	// $userType = 'x';
	// 	// $province = 'x';
	// 	// $city = 'x';
	// 	// $area = 'x';
	// 	// $road = 'x';
	// 	// $lng = 'x';
	// 	// $lat = 'x';
	// 	// $width = '375';
		
	// 	setcookie($id,$userID,time() + (86400 * 30),"/"); $_COOKIE[$id] = $userID;
	// 	setcookie($ty,$userType,time() + (86400 * 30),"/"); $_COOKIE[$ty] = $userType;
	// 	setcookie($pro,$province,time() + (86400 * 30),"/"); $_COOKIE[$pro] = $province;
	// 	setcookie($cy,$city,time() + (86400 * 30),"/"); $_COOKIE[$cy] = $city;
	// 	setcookie($aa,$area,time() + (86400 * 30),"/"); $_COOKIE[$aa] = $area;
	// 	setcookie($rd,$road,time() + (86400 * 30),"/"); $_COOKIE[$rd] = $road;
	// 	setcookie($ln,$lng,time() + (86400 * 30),"/");  $_COOKIE[$ln] = $lng;
	// 	setcookie($lt,$lat,time() + (86400 * 30),"/");  $_COOKIE[$lt] = $lat;
	// 	setcookie($w,$width,time() + (86400 * 30),"/"); $_COOKIE[$w] = $width;
	// }
?>
