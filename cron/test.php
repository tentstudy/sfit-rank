<pre>
<?php 
	require_once '../db/connect.php';
	$data = $db->getInsightGroup('1796364763915932');
	// $data = json_decode($data);
	$members = json_decode($data['json']);
	print_r($members);
?>