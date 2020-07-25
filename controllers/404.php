<?php


errors::$list[] = localisation::txt('Страница ненайдена');

if (!$user->logged){
    include 'login.php';
}