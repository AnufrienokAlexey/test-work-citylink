<?php

/**
 * @charset UTF-8
 *
 * Задание 2. Работа с массивами и строками.
 *
 * Есть список временных интервалов (интервалы записаны в формате чч:мм-чч:мм).
 *
 * Необходимо написать две функции:
 *
 *
 * Первая функция должна проверять временной интервал на валидность
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 * 	возвращать boolean
 *
 *
 * Вторая функция должна проверять "наложение интервалов" при попытке добавить новый интервал в список существующих
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм). Учесть переход времени на следующий день
 *  возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием одного интервала,
 *   встречается начало, окончание или то и другое одновременно, другого интервала
 *
 *
 *
 *  пример:
 *
 *  есть интервалы
 *  	"10:00-14:00"
 *  	"16:00-20:00"
 *
 *  пытаемся добавить еще один интервал
 *  	"09:00-11:00" => произошло наложение
 *  	"11:00-13:00" => произошло наложение
 *  	"14:00-16:00" => наложения нет
 *  	"14:00-17:00" => произошло наложение
 */

# Можно использовать список:

$list = array (
	'09:00-11:00',
//	'11:00-13:00',
//	'15:00-16:00',
//	'17:00-20:00',
//	'20:30-21:30',
//	'21:30-22:30',
);

//Решение:
$str = '04:50-14:30';

//Функция validateDate проверяет валидность значений времени и дат
function validateDate($date, $format = 'Y-m-d H:i:s'): bool
{
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

//Функция validateTime проверяет значения интервалов на валидность
function validateTime($str): bool
{
	$is_validate = true;
	$timeArray = explode('-', $str);
	foreach ($timeArray as $time) {
		if (!(validateDate($time, 'H:i'))) {
			$is_validate = false;
		}
	}
	return $is_validate;
}

//Функция validateInterval проверяет интервал на добавление интервала $str в существующий массив интервалов $list
function validateInterval($str): void
{
	global $list;
	if (validateTime($str)) {
		foreach ($list as $l) {
			$time = explode('-', $l);
			$startTime = date_parse($time[0]);
			$endTime = date_parse($time[1]);

			echo $time[0] . PHP_EOL;
			echo $time[1] . PHP_EOL;

//			print_r($startTime);
//			print_r($endTime);

			$strTime = explode('-', $str);
			$startStrTime = date_parse($strTime[0]);
			$endStrTime = date_parse($strTime[1]);

			echo $strTime[0] . PHP_EOL;
			echo $strTime[1] . PHP_EOL;

//			print_r($startStrTime);
//			print_r($endStrTime);

			echo $startStrTime['hour'] . PHP_EOL;
			echo $startStrTime['minute'] . PHP_EOL;
			echo $endStrTime['hour'] . PHP_EOL;
			echo $endStrTime['minute'] . PHP_EOL;

			echo $startTime['hour'] . PHP_EOL;
			echo $startTime['minute'] . PHP_EOL;
			echo $endTime['hour'] . PHP_EOL;
			echo $endTime['minute'] . PHP_EOL;

			$firstTime = new DateTimeImmutable($time[0]);
			$secondTime = new DateTimeImmutable($time[1]);
			$firstStrTime = new DateTimeImmutable($strTime[0]);
			$secondStrTime = new DateTimeImmutable($strTime[1]);
//			echo $firstTime->format('H:i');

			if (($firstStrTime >= $firstTime) AND ($firstStrTime < $secondTime) OR
				(($secondStrTime > $firstTime) AND ($secondStrTime <= $secondTime))){
				echo 'НАЛОЖЕНИЕ' . PHP_EOL;
			}

//			if (($startStrTime['hour'] > $endTime['hour']) AND ($endStrTime['hour'] < $startTime['hour'])) {
////				AND	($startStrTime['minute'] > $endTime['minute']) AND ($endStrTime['minute'] < $startTime['minute'])) {
//				echo "Наложения нет, можно добавить интервал $str в список $l" . PHP_EOL;
//			}
		}

		return;
	}
	echo "Проверьте валидность искомого интервала $str.";
}

validateInterval($str);