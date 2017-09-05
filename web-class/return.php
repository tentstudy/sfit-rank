<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once '../lib/FB.php';
	require_once '../lib/function.php';
	require_once '../db/connect.php';
	/* get data*/

	$code  = $_REQUEST['code']  ?? '';
	// $autoLogin = $_REQUEST['autologin'] == 'on' ? true : false;
	$token = getTokenFromCode();

	$fb = new FB('./');
	$fb->setAccess_token($token);
	if ($fb->checkToken() == false) {
		$fb->showError();
		$_SESSION["login"] = false;
		header('Location: login.html#'.'Token không hợp lệ.');
		exit();
	}
	$fb->setData();
/*ss*/
	$userId = $fb->json->id;
	$userName = $fb->json->name;
	$timeLive = 60*60*24*60; /*60 ngày*/

	setcookie('token', $token, time() + $timeLive);
	setcookie('username',  $userName, time() + $timeLive);
	setcookie('userid',  $userId, time() + $timeLive);
	setcookie('auto_login',  $autoLogin, time() + $timeLive);
	$_SESSION['id'] = $userid;
	$_SESSION['name'] = $username;
	// echo $username . " " . $userId;
	// exit();
	$db->addUser($userId, $userName, $token, $email, $password);
	$_SESSION["login"] = true;
	header('Location: ./');
?>