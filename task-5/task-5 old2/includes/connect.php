<?php

try {
	$connect = new PDO("mysql:host=localhost", "root", "");
	$databases = $connect->query('show databases')->fetchAll(PDO::FETCH_COLUMN);

	if(!in_array('task5',$databases)) {
		$connect->exec("
			CREATE DATABASE `task5`;
			use `task5`;
			CREATE TABLE `users` (id integer auto_increment primary key, user varchar(30), score integer);
		");
	}
}
catch(PDOException $e)
{
	echo "Неудачная попытка подключения к базе данных: " . $e->getMessage();
}