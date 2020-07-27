<?php
$ad_accaunts = new Ad_accounts();
$ad_accaunts->load_accaunts();


$account = new Ad_account($ad_accaunts->list['act_380185422600371']);
$account->load_campaigns();


dump($account);

