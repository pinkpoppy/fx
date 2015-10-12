<?php
	//--- 数据库连接 ---
	header('Content-Type: text/html; charset=utf-8');
	$servername = "127.0.0.1"; 
	$username = "root";	
	$db = "winesite";

	// $password = "1qaz2wsx";
	// $port = 3307;

	$password = "fQHvxT6ytETyAa9R";
	
	// $password = "000000";
	
	// $password = "";
	// $conn = new mysqli($servername, $username, $password, $db,$port);
	$conn = new mysqli($servername, $username, $password, $db);
	if ($conn->connect_error) {
		die("出现了一点问题,刷新试试..");
	} 
	$conn->query("set names 'utf8mb4'");

	function showFavNum($num) {
		$res = "";
		if ($num < 1000) {
			$res .= $num;
		} else {
			$a = intval($num / 1000); // 整除
			$a = intval($num / 100);
			$res = $a / 10 . "k";
		}
		return $res;
	}
	
?>
