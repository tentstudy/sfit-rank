<?php
	session_start();
	$login = $_SESSION["login"] ?? false;
	require_once __DIR__ . '/lib/FB.php';
	require_once __DIR__ . '/lib/function.php';
	$fb = new FB('./');
	$userName;
	$userId;
	// echo "<pre>";
	// print_r($_COOKIE);
	// exit();
	/* chưa đăng nhập nhưng autologin*/
	// if(!$login){
	// 	$fb->setToken($_COOKIE['token'] ?? '');
	// 	if(!$fb->checkToken()){
	// 		$_SESSION["login"] = $login = false;
	// 		setcookie('auto_login',  'false', time() - 5184000);
	// 		header('Location: ./login.html');
	// 		echo "không ngu";
	// 		exit();	
	// 	} else {
	// 		if(empty($fb->json->name) || empty($fb->json->id)){
	// 			die(header('Location: ./login.html'));
	// 		}
	// 		$_SESSION["login"] = $login = true;
	// 		$_SESSION["name"] = $userName = $fb->json->name;
	// 		$_SESSION["id"] = $userId = $fb->json->id;
	// 	}
	// } else{
	// 	// $userName = $_SESSION["name"];
	// 	// $userId = $_SESSION["id"];
	// }
	// // print_r($fb);
	// // exit();
	// /* nếu chưa đăng nhập*/
	// if (!$login){
	// 	die(header('Location: ./login.html'));
	// }
	$userName = $_COOKIE['username'];
	$userId = $_COOKIE['userid'];
?>