<?php

if (!empty($_POST['names'])) {
	$arr = explode(',', $_POST['names']);
	$_POST['names'] = [];
	foreach ($arr as $item) {
		$item = trim($item);
		$currentFile = file_get_contents('people.txt');
		$currentFile .= $item . ',';
		file_put_contents('people.txt', $currentFile);
	}

	$currentFileNew = trim(file_get_contents('people.txt'));
	$current = explode(',', $currentFileNew);
	array_pop($current);

	echo '<pre>';
	print_r($current);
	echo  '</pre>';
	foreach ($current as $key => $item) {
		$_POST['participants'][] = [
			'id' => $key + 1,
			'name' => $item,
			'score' => rand(0, 100)
		];
	}
}

//$host  = $_SERVER['HTTP_HOST'];
//$uri   = rtrim(dirname($_SERVER['PHP_SELF'], 2), '/\\');
//$extra = 'index.php';
//header("Location: http://$host$uri/$extra");
//exit;