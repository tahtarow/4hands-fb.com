<?php
$ad_accaunts = new Ad_accaunts();
$ad_accaunts->load_accaunts();


$account = $ad_accaunts->list['act_380185422600371'];
$account->load();
$account->load_statistic();

dump($account);

