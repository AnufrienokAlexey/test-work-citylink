Решение задачи №1:
Выбираем поле name из таблицы users. С помощью JOIN обьединяем таблицы orders и users (где у orders поле user_id равно полю id у таблицы users). Группируем по полю name.
Запрос выйглядит так:
_____________________
SELECT u.name
FROM users AS u
INNER JOIN orders AS o ON (o.user_id = u.id)
GROUP BY u.name
_____________________


Решение задачи №2:
Выбираем поле name из таблицы products и name из таблицы catalogs. С помощью JOIN обьединяем таблицы products и catalogs (где у products поле catalog_id равно полю id у таблицы catalogs).
Группируем по полю id.
Запрос выйглядит так:
_____________________
SELECT p.name, c.name
FROM products AS p
INNER JOIN catalogs AS c ON (p.catalog_id = c.id)
GROUP BY p.id
_____________________


Решение задачи №3:
Отключаем автофиксацию, начинаем транзакцию, вставляем поля name и birthday_at в таблицу users базы sample из полей name и birthday_at таблицы users базы shop соответственно,
где id у таблицы users базы shop равен 1 (по условиям задачи). фиксируем (коммитим).
Запрос выйглядит так:
_____________________
SET AUTOCOMMIT=0;
START TRANSACTION;
INSERT INTO sample.users (name, birthday_at)
SELECT shop.users.name, shop.users.birthday_at
FROM shop.users
WHERE (id = 1);
COMMIT;
_____________________

Решение задачи №4:
Выбираем поле name из таблицы users. С помощью JOIN обьединяем таблицу orders и users (по ключу где у orders поле user_id равно полю id таблицы users),
где с помощью функции TIMESTAMPDIFF() возвращаем значение после вычитания текущей даты (вычисляем с помощью CURDATE) и даты рождения, приводим к годам и проверям на условие что полученный возвраст больше 30 (по условиям задачи)
а также принимаем во внимание еще одно обязательное условие (AND) в котором с даты создания (таблица orders поле created_at) до сегодняшнего момента (функция NOW()) прошло меньше 183 дня (полгода по условиям задачи)
группируем по полю name таблицы users у которых количество заказов >= трем (по условиям задачи). выбираем рандомную одну запись
_____________________
SELECT u.name
FROM users AS u
INNER JOIN orders AS o ON (o.user_id = u.id)
WHERE ( TIMESTAMPDIFF( YEAR, u.birthday_at, CURDATE() ) ) > 30 AND
( o.created_at < NOW() - INTERVAL 183 DAY )
GROUP BY u.name
HAVING COUNT(o.id) >= 3
ORDER BY RAND() LIMIT 1
_____________________