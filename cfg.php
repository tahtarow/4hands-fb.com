<?php

autoload_class_from('classes/');


//ini_set('max_execution_time', 99999999999);
//ini_set('memory_limit', '100000M');
mb_internal_encoding('UTF-8');

//setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');

errors::on();
session_start();

define('ROOT', __DIR__);
define('CLASSES', __DIR__ . '/classes');
define('VIEWS', __DIR__ . "/views");
define('CONTROLLERS', __DIR__ . "/controllers");
define('DOMAIN', $_SERVER['SERVER_NAME']);


$db_host = 'localhost';
$db_name = 'visittime';
$db_user = 'root';
$db_pass = 'tahtarow';
//ORM::configure('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8');
//ORM::configure('return_result_sets', true);
//ORM::configure('username', $db_user);
//ORM::configure('password', $db_pass);



$email = new Email();
$email->my_mail = 'email@retten-sie-leben.ch';





