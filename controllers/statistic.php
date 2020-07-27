<?php
$ad_accaunts = new Ad_accounts();
$ad_accaunts->load_accaunts();


if (isset($_GET['ac_id'])) {
    $ad_accaunt = $ad_accaunts->list[$_GET['ac_id']];
    $ad_accaunt->load_campaigns();
    if (!isset($_GET['campaign_id'])) {
        if (!empty($ad_accaunt->campaings)) {
            $_GET['campaign_id'] = current($ad_accaunt->campaings)->id;
        }
    }

    if (isset($_GET['campaign_id'])){
        $campaign = $ad_accaunt->campaings[$_GET['campaign_id']];
        $campaign->load();
        $campaign->load_ads();
    }

//dump($campaign->ads);

}


include_once VIEWS . '/includes/header1.php';
include_once VIEWS . '/statistic.php';
include_once VIEWS . '/includes/footer1.php';
