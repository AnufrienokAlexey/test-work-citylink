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
		$config = require 'config.php';
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
		header('Location: user.php');
	}

	public function delete($id)
	{
		$delete = $this->link->prepare("DELETE FROM `users` WHERE id = $id");
		$delete->execute();
		$_POST = [];
		header('Location: user.php');
	}

    public function truncate()
    {
        $truncate = $this->link->prepare("TRUNCATE TABLE `task5`.`users`");
        $truncate->execute();
        $_POST = [];
        header('Location: user.php');
    }

    public function createIvanovRandomScore()
    {
		$faker = Faker\Factory::create();
        $addNewUser = $this->link->prepare("INSERT INTO `users` (`id`, `user`, `score`) VALUES (null, :user, :score)");
        $addNewUser->execute(['user' => $faker->name, 'score' => rand(0, 100)]);
        $_POST = [];
        header('Location: user.php');
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

$config = require 'config.php';
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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Олимпиада</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/fonts.css">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/media.css">
    <link rel="icon" href="/img/favicon.svg" type="image/svg+xml">
</head>
<body>
    <h1>Олимпиада</h1>
    <form action="User.php" method="post">
        <div class="mb-3">
            <label for="user" class="form-label">Участники</label>
            <div class="input-group">
                <input type="text" class="form-control" name="user" id="user" placeholder="Введите имена участников через запятую">
                <input class="btn btn-outline-secondary" type="submit" id="button-addon2" name="create" value="Сохранить пользователя">
            </div>
        </div>
    </form>
    <h2>Первый вариант таблицы - сортировка с помощью выпадающего списка</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <label for="sortValue">Сортировать по: </label>
        <select name="sort_value" id="sortValue">
            <option value="id" selected>Номеру (по возврастанию)</option>
            <option value="id DESC">Номеру (по убыванию)</option>
            <option value="user">Имени (от А до Я)</option>
            <option value="user DESC">Имени (от Я до А)</option>
            <option value="score">Очкам (по возврастанию)</option>
            <option value="score DESC">Очкам (по убыванию)</option>
        </select>
        <input type="submit" value="Применить" name="sort_table">
    </form>
    <table>
        <tr>
            <th>id</th>
            <th>Имя</th>
            <th>Очки</th>
        </tr>
        <?php foreach ($result_array as $result_row) {?>
        <tr>
            <td><?=$result_row["id"];?></td>
            <td><?=$result_row["user"];?></td>
            <td><?=$result_row["score"];?></td>
        </tr>
        <?php } ?>
    </table>
    <h2>Второй вариант таблицы - сортировка по клику названию столбца</h2>
    <table>
        <tr>
            <th><a href="<?php echo $_SERVER['PHP_SELF'];?>?column=id&order=<?php echo $asc_or_desc; ?>">id<i class="fas fa-sort<?php echo $column == 'id' ? '-' . $up_or_down : ''; ?>"></i></a></th>
            <th><a href="<?php echo $_SERVER['PHP_SELF'];?>?column=user&order=<?php echo $asc_or_desc; ?>">Имя<i class="fas fa-sort<?php echo $column == 'user' ? '-' . $up_or_down : ''; ?>"></i></a></th>
            <th><a href="<?php echo $_SERVER['PHP_SELF'];?>?column=score&order=<?php echo $asc_or_desc; ?>">Очки<i class="fas fa-sort<?php echo $column == 'score' ? '-' . $up_or_down : ''; ?>"></i></a></th>
        </tr>
		<?php foreach ($result->fetchAll() as $row) {?>
            <tr>
                <td<?php echo $column == 'id' ? $add_class : ''; ?>><?php echo $row['id']; ?></td>
                <td<?php echo $column == 'user' ? $add_class : ''; ?>><?php echo $row['user']; ?></td>
                <td<?php echo $column == 'score' ? $add_class : ''; ?>><?php echo $row['score']; ?></td>
            </tr>
		<?php } ?>
    </table>
    <h2>Третий вариант таблицы - без сортировки</h2>
    <table class="table">
        <thead>
        <tr>
            <th>id</th>
            <th>Имя</th>
            <th>Очки</th>
            <th>Удалить участника</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($user->getUsers() as $row) { ?>
                <tr>
                    <?php for ($i =0; $i < 3 ; $i++) { ?>
                        <td><?= $row[$i]; ?></td>
                    <?php }	?>
                    <td>
                        <form action="User.php" method="post">
                            <button type="submit" class="btn btn-danger" name="deleteId" value="<?= $row['id'];?>">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="m-3">
        <h4>Бонусы для проверки:</h4>
    </div>
    <form action="User.php" method="post">
        <div class="m-3">
            <input class="btn btn-danger" type="submit" name="truncate" value="Очистить таблицу (команда TRUNCATE)" />
        </div>
        <div class="m-3">
            <input class="btn btn-info" type="submit" name="createIvanovRandomScore" value="Создать случайного участника со случайным количеством очков" />
        </div>
    </form>
</body>
</html>