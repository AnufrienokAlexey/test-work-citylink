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
		$config = require_once 'config.php';
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

if (!empty($_POST['create'])) {
	$user = new User("$_POST[user]");
	$user->create();
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

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Олимпиада</title>
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