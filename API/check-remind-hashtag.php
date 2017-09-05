<?php
// (json_encode(array('check' => )));
	header('Content-Type: application/json; charset=utf-8');
	$postId = $_REQUEST['postId'];
	require '../db/connect.php';
	$res = $db->checkRemindHashTag($postId);
	if($res == 0){
		$db->saveRemindHashTag($postId);
	}
	$res = array("check" => $res);
	
	echo(json_encode($res));
?>