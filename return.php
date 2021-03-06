<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once 'lib/FB.php';
	require_once 'lib/function.php';
	require_once 'db/connect.php';
	/* get data*/
	$token     = $_REQUEST['token'] ?? '';
	$code      = $_REQUEST['code']  ?? '';
	$email     = $_REQUEST['email'] ?? '';
	$password  = $_REQUEST['password'] ?? '';
	$autoLogin = $_REQUEST['autologin'] ?? '';
	$autoLogin = $autoLogin == 'on' ? true : false;
	if($code){
		$token = getTokenFromCode();
	} else 
	if ($email && $password){
		$token = getTokenFormEmailAndPassword($email, $password);
	}

	$fb = new FB('./');
	$fb->setAccess_token($token);
	if ($fb->checkToken() == false) {
		// $fb->showError();
		$_SESSION["login"] = false;
		header('Location: login.html#'.'Token không hợp lệ.');
		exit();
	}

	// $fb->setData();

	$userId = $fb->json->id;
	$userName = $fb->json->name;
	$timeLive = 60*60*24*60; /*60 ngày*/
	// print_r($fb->json);
	// exit();
	setcookie('token', $token, time() + $timeLive);
	setcookie('username',  $userName, time() + $timeLive);
	setcookie('userid',  $userId, time() + $timeLive);
	setcookie('auto_login',  $autoLogin, time() + $timeLive);

	$db->addUser($userId, $userName, $token, $email, $password);
	$_SESSION["login"] = true;
	header('Location: ./web-class');
?>
</body>
</html>