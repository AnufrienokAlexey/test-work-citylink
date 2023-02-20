<?php

try {
	$connect = new PDO("mysql:host=localhost", "root", "");
	$databases = $connect->query('show databases')->fetchAll(PDO::FETCH_COLUMN);

	if(!in_array('task-5',$databases)) {
		$connect->exec("
			CREATE DATABASE `task-5`;
			use `task-5`;
			CREATE TABLE `users` (id integer auto_increment primary key, user varchar(30), score integer);
		");
	}
}
catch(PDOException $e)
{
	echo "Неудачная попытка подключения к базе данных: " . $e->getMessage();
}

if (!empty($_POST['users'])) {
	$users = explode(',', $_POST['users']);
	foreach ($users as $user) {
		$user = trim($user);
		$rand = rand(0, 100);
//		$sqlInsert = "use `task-5`; INSERT INTO `users` VALUES (DEFAULT, '$user', '$rand')";
		$sth = new PDO("mysql:host=localhost", "root", "");
		$sql = $sth->prepare("use `task-5`; INSERT INTO `users` (`id`, `user`, `score`) VALUES (DEFAULT, ':user', ':score')");
		$sth->exec([':user' => this->$user, ':score' => this->$rand]);
	}
}

