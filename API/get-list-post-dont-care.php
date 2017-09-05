<?php 
	session_start();
	/*chưa đăng nhập thì next*/
	if (!$_SESSION['login']) {
		die('');
	}
	require __DIR__ . '/../db/connect.php';
	if(empty($_POST)){
		echo $db->getListPostsDontCare('100006907028797');
		exit();
	}


?>