<?php


/**
 * @charset UTF-8
 *
 * Задание 1. Работа с массивами.
 *
 * Есть 2 списка: общий список районов и список районов, которые связаны между собой по географии (соседние районы).
 * Есть список сотрудников, которые работают в определённых районах.
 *
 * Необходимо написать функцию, что выдаст ближайшего сотрудника к искомому району.
 * Если в списке районов, нет прямого совпадения, то должно искать дальше по соседним районам.
 * Необязательное усложение: выдавать список из сотрудников по близости к искомой функции.
 *
 * Функция должна принимать 1 аргумент: название района (строка).
 * Возвращать: логин сотрудника или null.
 *
 */

# Использовать данные:

// Список районов
$areas = array (
	1 => '5-й поселок',
	2 => 'Голиковка',
	3 => 'Древлянка',
	4 => 'Заводская',
	5 => 'Зарека',
	6 => 'Ключевая',
	7 => 'Кукковка',
	8 => 'Новый сайнаволок',
	9 => 'Октябрьский',
	10 => 'Первомайский',
	11 => 'Перевалка',
	12 => 'Сулажгора',
	13 => 'Университетский городок',
	14 => 'Центр',
);

// Близкие районы, связь осуществляется по индентификатору района из массива $areas
$nearby = array (
	1 => array(2,11),
	2 => array(12,3,6,8),
	3 => array(11,13),
	4 => array(10,9,13),
	5 => array(2,6,7,8),
	6 => array(10,2,7,8),
	7 => array(2,6,8),
	8 => array(6,2,7,12),
	9 => array(10,14),
	10 => array(9,14,12),
	11 => array(13,1,9),
	12 => array(1,10),
	13 => array(11,1,8),
	14 => array(9,10),
);

// список сотрудников
$workers = array(
	0 => array(
		'login' => 'login1',
		'area_name' => 'Октябрьский', //9
	),
	1 => array(
		'login' => 'login2',
		'area_name' => 'Зарека', //5
	),
	2 => array(
		'login' => 'login3',
		'area_name' => 'Сулажгора', //12
	),
	3 => array(
		'login' => 'login4',
		'area_name' => 'Древлянка', //3
	),
	4 => array(
		'login' => 'login5',
		'area_name' => 'Центр', //14
	),
);

//Решение - функция find с аргументом $area - название района
function findWorker(string | array $value) {
	global $workers;

	if (is_array($value)) {
		foreach ($value as $v) {
			foreach ($workers as $w) {
				if ($w['area_name'] === $v) {
					echo 'Найден сотрудник соседнего района "' . $v . '" : ' . $w['login'] . PHP_EOL;
				}
			}
		}
	}

	if (is_string($value)) {
		foreach ($workers as $worker) {
			if ($worker['area_name'] === $value) {
				echo 'Найден сотрудник данного района "' . $value . '" : ' . $worker['login'] . PHP_EOL;
			}
		}
	}
}
function find($area)
{
	global $areas;
	global $nearby;
	global $workers;

//	foreach ($workers as $worker) {
//		if ($worker['area_name'] === $area) {
//			echo 'Найден сотрудник данного района "' . $area . '" : ' . $worker['login'] . PHP_EOL;
//		}
//	}

	$arr = [];
	$number_area = array_search($area, $areas);
	foreach ($nearby as $near) {
		$number_near = array_search($number_area, $near);

		if ($number_near > -1) {
			$a = array_search($near, $nearby);
			$arr[] = $a;
		}
	}

	$result = [];
	foreach ($nearby as $key => $n) {
		if(array_search($key, $arr, true)) {
			$result[] = $n;
		}
	}
	$a = [];
	foreach ($result as $item) {
		foreach ($item as $i) {
			$a[] = $i;
		}
	}

	$new_a = array_unique($a, SORT_NUMERIC);
	$new_a = array_diff($new_a, array($number_area));
	$new_arr = [];

	foreach ($new_a as $key => $value) {
		foreach ($areas as $k => $a) {
			if ($value === $k) {
				$new_arr[] = $a;
			}
		}
	}
	findWorker($area);
	findWorker($new_arr);
//	foreach ($new_arr as $value) {
//		foreach ($workers as $worker) {
//			if ($worker['area_name'] === $value) {
//				echo 'Найден сотрудник соседнего района "' . $value . '" : ' . $worker['login'] . PHP_EOL;
//			}
//		}
//	}
}

find('Первомайский');
echo PHP_EOL;
find('Сулажгора');
echo PHP_EOL;
//find('Кукковка');
//echo PHP_EOL;
//find('Древлянка');
//echo PHP_EOL;
//find('Москва');