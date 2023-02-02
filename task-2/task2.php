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
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 *  возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием одного интервала,
 *   встречается начало, окончание или то и другое одновременно, другого интервала
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
$str = '15:00-16:30';

//Первая функция - функция валидации временного диапазона
function validate($str)
{
	$arr = [];
	$time = explode('-', $str);

	foreach($time as $time_exploded) {
		$value = explode(':', $time_exploded);
		foreach($value as $value_exploded) {
			$arr[] = $value_exploded;
		}
	}

	function validHour($hour)
	{
		if ($hour >=0 AND $hour <= 23) {
			return $hour;
		}
		return false;
	}

	function validMinute($minute)
	{
		if ($minute >=0 AND $minute <= 59) {
			return $minute;
		}
		return false;
	}

	foreach ($arr as $key => &$item) {
		if (!($key & 1)) {
			$item = validHour($item);
		} else {
			$item = validMinute($item);
		}
	}

	if (!(in_array(false, $arr))) {
		echo 'Время - ' . $str . ' успешно прошло валидацию' . PHP_EOL;
		return true;
	}

	echo 'Время - ' . $str . ' не прошло валидацию. Проверьте значения времени.' . PHP_EOL;
	return false;

};

//Еще одно решение первой функции - функция check_interval с использованием регулярных выражений
function conversion_time($tm)
{
	$n = preg_match_all("!^([0-9][0-9]){1}:([0-9][0-9]){1}$!sUi", $tm, $res);
	if(($n>0) AND ($res[1][0]<24 && $res[2][0]<60)){
		return $res[1][0]*60 + $res[2][0];
	}
	return false;
}

function check_interval($time_inter)
{
	$n = preg_match_all("!^([0-9][0-9]){1}:([0-9][0-9]){1}-([0-9][0-9]){1}:([0-9][0-9]){1}$!sUi", $time_inter, $res);
	if($n>0) {
//		Проверяем валидность введеных значений (часы от 0 до 23 включительно и минуты от 0 до 59 включительно)
		if($res[1][0]>23 || $res[2][0]>59 || $res[3][0]>23 || $res[4][0]>59){
			return false;
		}
		if( (conversion_time($res[3][0] . ':' . $res[4][0]) - conversion_time($res[1][0] . ':' . $res[2][0])) <= 0) {
			return false;
		}
	} else {
		return false;
	}
	return true;
}

//Вторая функция - проверка наложения интервалов при попытке добавить новый интервал в список существующих
function check_intersection($interval) {
	global $list;

	$isValid = check_interval($interval);
	if($isValid === false)
		return false;

	for($i = 0; $i < count($list); $i++) {
		$n = preg_match_all("!^([0-9][0-9]){1}:([0-9][0-9]){1}-([0-9][0-9]){1}:([0-9][0-9]){1}$!sUi", $list[$i], $res);
		if($n>0) {
			$n2 = preg_match_all("!^([0-9][0-9]){1}:([0-9][0-9]){1}-([0-9][0-9]){1}:([0-9][0-9]){1}$!sUi", $interval, $res2);
			if($n2>0) {
				if( ( conversion_time($res2[3][0].':'.$res2[4][0]) <= conversion_time($res[1][0].':'.$res[2][0]) ) ||
					( conversion_time($res2[1][0].':'.$res2[2][0]) >= conversion_time($res[3][0].':'.$res[4][0]) ) ) {
						echo('Время ' . $interval.' - наложения нет '.$list[$i] . PHP_EOL);
					}else {
						echo('Время ' . $interval.' - произошло наложение, возвращено false '.$list[$i] . PHP_EOL);
						return false;
				}
			}
		}
	}

	$list[] = $interval;
	echo 'Добавлен элемент массива '.$interval.' в массив $list, возвращено true' ;
	return true;
}

check_intersection('14:00-15:30');
echo PHP_EOL;
check_intersection('13:05-13:10');
