<?php
require_once 'includes/connect.php';
require_once 'vendor/autoload.php';

class User {
	private $user;
	private $link;

	public function __construct($user) {
		$this->connect();
		$this->user = $user;
	}

	private function connect()
	{
		$config = require 'includes/config.php';
		$dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
		$this->link = new PDO($dsn, $config['username'], $config['password']);
	}

	public function getUsers()
	{
		$getUsers = $this->link->prepare("SELECT * FROM `users`");
		$getUsers->execute();
		return $getUsers->fetchAll();
	}

	public function create()
	{
		$addNewUser = $this->link->prepare("INSERT INTO `users` (`id`, `user`, `score`) VALUES (null, :user, :score)");
		$addNewUser->execute(['user' => $this->user, 'score' => rand(0, 100)]);
		$_POST = [];
		header('Location: index.php');
	}

	public function delete($id)
	{
		$delete = $this->link->prepare("DELETE FROM `users` WHERE id = $id");
		$delete->execute();
		$_POST = [];
		header('Location: index.php');
	}

    public function truncate()
    {
        $truncate = $this->link->prepare("TRUNCATE TABLE `task5`.`users`");
        $truncate->execute();
        $_POST = [];
        header('Location: index.php');
    }

    public function createIvanovRandomScore()
    {
		$faker = Faker\Factory::create();
        $addNewUser = $this->link->prepare("INSERT INTO `users` (`id`, `user`, `score`) VALUES (null, :user, :score)");
        $addNewUser->execute(['user' => $faker->name, 'score' => rand(0, 100)]);
        $_POST = [];
        header('Location: index.php');
    }
}

if (!empty($_POST['user'])) {
    $arr = explode(',', $_POST['user']);
	foreach ($arr as $item) {
		$item = trim($item);
		$user = new User($item);
		$user->create();
	}
} else {
	$user = new User('');
}

if (!empty($_POST['deleteId'])) {
	$user->delete($_POST['deleteId']);
}

if (!empty($_POST['truncate'])) {
	$user->truncate();
}

if (!empty($_POST['createIvanovRandomScore'])) {
	$user->createIvanovRandomScore();
}

if (!empty($_POST['sortById'])) {
    $user->sortById();
}

//Первая таблица
if(empty($_POST['sort_value'])){
	$sort_value = "id";
} else {
	$sort_value = $_POST['sort_value'];
}

$config = require 'includes/config.php';
$dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
$link = new PDO($dsn, $config['username'], $config['password']);
$sort = $link->prepare("SELECT * FROM `users` ORDER BY $sort_value");
$sort->execute();
$result_array = $sort->fetchAll();

//Вторая таблица
$columns = array('id','user','score');
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
$sth = new PDO($dsn, $config['username'], $config['password']);
$result = $sth->prepare('SELECT * FROM `users` ORDER BY ' .  $column . ' ' . $sort_order);
$result->execute();
$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order);
$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
$add_class = ' class="highlight"';
