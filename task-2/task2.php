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
	'11:00-13:00',
	'15:00-16:00',
	'17:00-20:00',
	'20:30-21:30',
	'21:30-22:30',
);

//Решение:
$str = '11:10-09:00';

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
	echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Вызов функции с интервалом $str >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>" . PHP_EOL;
	if (validateTime($str)) {
		foreach ($list as $l) {
			$time = explode('-', $l);
			$strTime = explode('-', $str);

			$startTime = new DateTimeImmutable($time[0]);
			$endTime = new DateTimeImmutable($time[1]);
			$startStrTime = new DateTimeImmutable($strTime[0]);
			$endStrTime = new DateTimeImmutable($strTime[1]);

//			Вывод для удобства проверки
//			echo '-----------------------------------------------------' . PHP_EOL;
//			echo "Время начала интервала в массиве list: \$startTime = " . $startTime->format('H:i') . PHP_EOL;
//			echo "Время окончания интервала в массиве list: \$endTime = " . $endTime->format('H:i') . PHP_EOL;
//			echo "Время начала искомого интервала: \$startStrTime = " . $startStrTime->format('H:i') . PHP_EOL;
//			echo "Время окончания искомого интервала: \$endStrTime = " . $endStrTime->format('H:i') . PHP_EOL;

			if (
				(($startStrTime >= $startTime) AND ($startStrTime < $endTime))
				OR
				(($endStrTime > $startTime) AND ($endStrTime <= $endTime))
				OR
				(($startStrTime < $startTime) AND ($endStrTime >= $endTime))
				OR
				(($startStrTime < $startTime) AND ($endStrTime < $startStrTime))
			){
				echo "\"$l\" => произошло наложение \"$l\" и \"$str\"" . PHP_EOL;
			} else {
				echo "Нет наложения \"$l\" и \"$str\"" . PHP_EOL;
			}
		}
		return;
	}
	echo "Проверьте валидность искомого интервала $str." . PHP_EOL;
}

validateInterval('08:560-09:00');
validateInterval('08:00-08:59');
validateInterval('08:00-09:00');
validateInterval('08:59-11:01');
validateInterval('09:00-11:00');
validateInterval('09:01-10:59');
validateInterval('11:00-09:00');
validateInterval('11:01-08:59');


