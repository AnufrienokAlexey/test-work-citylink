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
//Решение:

//Функция printNearArray выводит список соседних районов (сделал для удобства проверки).
function printNearArray(array $nearArray, string $area): void
{
	if ($nearArray) {
		echo "Найдены следующие районы, соседние с районом \"$area\":" . PHP_EOL;
		foreach ($nearArray as $n) {
			echo "\"$n\"" . PHP_EOL;
		}
		return;
	}
	echo "Не найдено ни одного соседнего района с районом \"$area\"." . PHP_EOL;
}

//Функция findWorker выводит логин сотрудника искомого района и логины сотрудников соседних районов.
function findWorker(string | array $value): void
{
	global $workers;

	if (is_string($value)) {
		foreach ($workers as $worker) {
			if ($worker['area_name'] === $value) {
				echo "Найден сотрудник данного района \"$value\": \"" . $worker['login'] . "\"" . PHP_EOL;
				return;
			}
		}
		echo "В данном районе \"$value\" нет сотрудников." . PHP_EOL;
	}

	if (is_array($value)) {
		foreach ($value as $v) {
			foreach ($workers as $worker) {
				if ($worker['area_name'] === $v) {
					echo "Найден сотрудник соседнего района \"$v\": \"" . $worker['login'] . "\"" . PHP_EOL;
				}
			}
		}
	}
}

//Функция find с параметром $area - название района
function find($area): void
{
	global $areas;
	global $nearby;

	$arrayKeysOfNearby = []; //$arrayKeysOfNearby - массив ключей массива $nearby, в значениях которого есть искомый район
	$indexOfAreas = array_search($area, $areas);
	foreach ($nearby as $near) {
		if (array_search($indexOfAreas, $near) > -1) {
			$arrayKeysOfNearby[] = array_search($near, $nearby);
		}
	}

	$arrayValuesOfNearby = []; //$arrayValuesOfNearby - массив значений массива $nearby, в значениях которого есть искомый район
	foreach ($nearby as $key => $n) {
		if(array_search($key, $arrayKeysOfNearby, true)) {
			$arrayValuesOfNearby[] = $n;
		}
	}

	$arr = []; //Пересобираем двухмерный массив $arrayValuesOfNearby в одномерный $arr
	foreach ($arrayValuesOfNearby as $item) {
		foreach ($item as $i) {
			$arr[] = $i;
		}
	}

	//Убираем из массива повторяющиеся значения районов и убираем из массива искомый район, так как мы создаем массив
	$numbersOfNearArray = array_diff(array_unique($arr, SORT_NUMERIC), array($indexOfAreas)); //$numbersOfNearArray - массив номеров районов, соседних с искомым
	$nearArray = []; //$nearArray - - массив названий районов, соседних с искомым

	foreach ($numbersOfNearArray as  $value) {
		foreach ($areas as $k => $a) {
			if ($value === $k) {
				$nearArray[] = $a;
			}
		}
	}

	findWorker($area);
	printNearArray($nearArray, $area);
	findWorker($nearArray);
}

find('Первомайский');
echo PHP_EOL;
find('Сулажгора');
echo PHP_EOL;
find('Кукковка');
echo PHP_EOL;
find('Древлянка');
echo PHP_EOL;
find('Москва');
echo PHP_EOL;
find('Ключевая');