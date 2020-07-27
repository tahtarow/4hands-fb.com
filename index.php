<?php
require_once('lib/functions.php');
require_once('lib/lessphp/less.php');
require_once('lib/idiorm-master/idiorm.php');
require_once('lib/class_router.php');
require_once('cfg.php');


define('TOKEN', 'EAAJiNLCP1g8BALw16v5LNdiJJevh4f0AqVDd3ZCDZBdBwgz1mLqK3HJcHMbeDN6KkksVlQtyjILKM3J4DCIkh4DDaHbGj9rISoFH2Q2XBRyuCZCRmp3s5AlT9ViEvuKXLB3YRhybslzaD0XqPwyCrDuhGsjIGPjFBMVaLZCNCgZDZD');

$router = new Router();
$router->controllers_dir = CONTROLLERS;
$router->controller_404 = CONTROLLERS . '/404.php';
$router->run();


include_once $router->patch_to_controller;


