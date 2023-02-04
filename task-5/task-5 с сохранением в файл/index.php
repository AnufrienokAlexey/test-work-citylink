<!-- Задача 5. Html. css. javascript
Создать HTML страницу с адаптивным дизайном под любое устройство. Можно использовать bootstrap.

По центру:
- Заголовок "Олимпиада"
- Поле ввода с лейблом "Участники", плейсхолдер "введите имена участников через запятую" (доступны только кириллические буквы и запятая)
- Кнопка "Добавить" (должна реагировать не только на щелчок мышью, но и на нажатие enter)

При первом нажатии на кнопку должна появляться таблица с полями:
 id, имя, очки. Поле ввода "Участники" должно становиться пустым. Плейсхолдер при этом прежний.
- id - это порядковый номер в списке участников, который ввёл пользователь.
- очки для каждого участника сгеренировать рандомно на стороне php.

Все столбцы таблицы должны быть сортируемыми при нажатии на заголовок столбца.

Если таблица уже отобразилась, то снова при нажатии на кнопку "Добавить" в таблицу должны добавляться участники, которых дописали.
Для генерации очков участников реализовать метод ajax-запроса, в файле php делать генерацию очков и возвращать обратно в js, полученные очки должны выводиться в таблице

Все ошибки пользователя при добавлении участников выводить через модальное окно с небольшим описанием, чтобы пользователь мог исправиться. -->
<?php

if (isset($_POST['names'])) {
    include 'includes/send_participants.php';
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
    <form action="index.php" method="post">
        <div class="mb-3">
            <label for="names" class="form-label">Участники</label>
            <div class="input-group">
                <input type="text" class="form-control" name="names" id="names" placeholder="Введите имена участников через запятую">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Добавить</button>
            </div>
        </div>
    </form>

    <?php if (isset($_POST['participants'])) {?>
    <table class="table">
        <thead>
        <tr>
            <th>id</th>
            <th>Имя</th>
            <th>Очки</th>
        </tr>
        </thead>
        <tbody class="table-group-divider">
		<?php foreach ($_POST['participants'] as $item) {?>
            <tr>
                <td><?=$item['id'];?></td>
                <td><?=$item['name'];?></td>
                <td><?=$item['score'];?></td>
            </tr>
		<?php };?>
        </tbody>
    </table>
    <?php } ?>
</body>
</html>