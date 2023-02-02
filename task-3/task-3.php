<?php

/**
 * @charset UTF-8
 *
 * Задание 3
 * В данный момент компания X работает с двумя перевозчиками
 * 1. Почта России
 * 2. DHL
 * У каждого перевозчика своя формула расчета стоимости доставки посылки
 * Почта России до 10 кг берет 100 руб, все что cвыше 10 кг берет 1000 руб
 * DHL за каждый 1 кг берет 100 руб
 * Задача:
 * Необходимо описать архитектуру на php из методов или классов для работы с
 * перевозчиками на предмет получения стоимости доставки по каждому из указанных
 * перевозчиков, согласно данным формулам.
 * При разработке нужно учесть, что количество перевозчиков со временем может
 * возрасти. И делать расчет для новых перевозчиков будут уже другие программисты.
 * Поэтому необходимо построить архитектуру так, чтобы максимально минимизировать
 * ошибки программиста, который будет в дальнейшем делать расчет для нового
 * перевозчика, а также того, кто будет пользоваться данным архитектурным решением.
 *
 */

# Использовать данные:
# любые

//Вводим абстракный класс Transporter для построения будущих классов с заданными условиями задания.
abstract class Transporter {
    abstract public function transport_price($weight);
}

//Описываем класс перевозчика DHL. Расширяется от абстрактного класса Transporter.
class Transporter_dhl extends Transporter {
    public function transport_price($weight) {
        return $weight*100;
    }
}

//Описываем класс перевозчика Почты России. Расширяется от абстрактного класса Transporter.
class Transporter_ruspost extends Transporter {
    public function transport_price($weight) {
        if($weight <= 10){
            return 100;
        }
        return 1000;
    }
}

// При добавления новой транспортной компании
class Transporter_ska extends Transporter {
    public function transport_price($weight) {
        return $weight*250;
    }
}

$weight = 9;
$ska = new Transporter_ska();
$dhl = new Transporter_dhl();
$ruspost = new Transporter_ruspost();
echo 'Затраты на перевозку в СКА - ' . $ska->transport_price($weight) . ' рублей'. PHP_EOL;
echo 'Затраты на перевозку в DHL - ' . $dhl->transport_price($weight) . ' рублей'. PHP_EOL;
echo 'Затраты на перевозку в Почта России - ' . $ruspost->transport_price($weight) . ' рублей'. PHP_EOL;

