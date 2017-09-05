<?php
	$data = array(
		'1' => array(
			'1' => '<h1>bai 1 - user 1</h1>',
			'2' => '<h2>bai 1 - user 2</h2>',
			'3' => '<h3>bai 1 - user 3</h3>',
			'4' => '<h4>bai 1 - user 4</h4>',
			'5' => '<h5>bai 1 - user 5</h5>',
			'6' => '<h6>bai 1 - user 6</h6>',
		),
		'2' => array(
			'1' => '<h1>bai 2 - user 1</h1>',
			'2' => '<h2>bai 2 - user 2</h2>',
			'3' => '<h3>bai 2 - user 3</h3>',
			'4' => '<h4>bai 2 - user 4</h4>',
			'5' => '<h5>bai 2 - user 5</h5>',
			'6' => '<h6>bai 2 - user 6</h6>',
		),
		'3' => array(
			'1' => '<h1>bai 3 - user 1</h1>',
			'2' => '<h2>bai 3 - user 2</h2>',
			'3' => '<h3>bai 3 - user 3</h3>',
			'4' => '<h4>bai 3 - user 4</h4>',
			'5' => '<h5>bai 3 - user 5</h5>',
			'6' => '<h6>bai 3 - user 6</h6>',
		)
	);

	$res = '<h1>Default data</h1>';
	if(!empty($_GET['bai']) && !empty($_GET['id'])){
		$bai = $_GET['bai'];
		$id = $_GET['id'];
		if(array_key_exists($bai, $data)){
			if(array_key_exists($id, $data[$bai])){
				$res = $data[$bai][$id];
			}
		}
	}
   echo json_encode(array(
      'data' => $res,

   ));