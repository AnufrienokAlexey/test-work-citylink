<?php
include 'User.php';
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
