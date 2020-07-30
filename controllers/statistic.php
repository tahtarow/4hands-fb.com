<?php
//dumpex(Request::get('me?batch=  [ { "method":"GET","relative_url":"me" }]'));

//dumpex(Request::get('me?include_headers=false&batch=%20%20%5B%20{%20"method":"GET","relative_url":"me"%20},%20{%20"name":"asdasdasd","method":"GET","relative_url":"me%2Fadaccounts"%20}%20%5D'));


//dump(Request::get('me?include_headers=false&batch=['.
//    '{"method":"GET","relative_url":"me"},'.
//    '{"method":"GET","relative_url":"me/adaccounts"}'.
//    ']'));



//$ch = curl_init();
//
//curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v7.0/me?include_headers=false&batch=%20%20%5B%20{%20"method":"GET","relative_url":"me"%20},%20{%20"name":"asdasdasd","method":"GET","relative_url":"me%2Fadaccounts"%20}%20%5D&access_token=EAAJiNLCP1g8BAOP0ZB5fj6ghuvZCrLCO7nFKEx9fu0ikZA6Txd5jZCTEQfoNuuRtdHAidbUvspojabaC69S45ogATYUZBFxbZAW0YofqUQ0jnm5jze19mIakt8KbKGKtioXGCZAlHmrO8tSZAG03MxR4RHnvZBPeNxkNwVRi3v43wiK3ozw7Wn5QqPw79WXMwo5sZD');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_POST, 1);
//
//$result = curl_exec($ch);
//if (curl_errno($ch)) {
//    echo 'Error:' . curl_error($ch);
//}
//curl_close($ch);
//dumpex($result);




$ad_accaunts = new Ad_accounts();
$ad_accaunts->load_accaunts();



if (isset($_GET['year'])){
    $current_year = $_GET['year'];
}else{
    $current_year = Calendar::get_current_year();
    $_GET['year'] = $current_year;
}

if (isset($_GET['month'])){
    $current_month = $_GET['month'];
}else{
    $current_month = Calendar::get_current_month();
    $_GET['month'] = $current_month;
}


if ($current_month==12){
    $next_month_y = $current_year + 1;
    $next_month = 1;
}else{
    $next_month_y = $current_year;
    $next_month = $current_month + 1;
}

if ($current_month==1){
    $previous_month_y = $current_year - 1;
    $previous_month = 12;
}else{
    $previous_month_y = $current_year;
    $previous_month = $current_month - 1;
}

$current_month_name = Calendar::get_month_name($current_month);



if (isset($_GET['ac_id'])) {


    //region загрузка акаунта и его компаний
    $ad_accaunt = $ad_accaunts->list[$_GET['ac_id']];
    $ad_accaunt->load_campaigns();
    if (!isset($_GET['campaign_id'])) {
        if (!empty($ad_accaunt->campaings)) {
            $_GET['campaign_id'] = current($ad_accaunt->campaings)->id;
        }
    }
    //endregion загрузка акаунта и его компаний

    //region загрузка компании и ее груп
    if (!isset($_GET['campaign_id'])) {
        if (!empty($ad_accaunt->campaings)) {
            $_GET['campaign_id'] = current($ad_accaunt->campaings)->id;
        }
    }
    if (isset($_GET['campaign_id'])) {
        $campaign = $ad_accaunt->campaings[$_GET['campaign_id']];
        $campaign->load();
        $campaign->load_groups();
    }
    //endregion загрузка  компании и ее груп

    //region загрузка групы и ее обявлений
    if (!isset($_GET['group_id'])) {
        if (!empty($campaign->groups)) {
            $_GET['group_id'] = current($campaign->groups)->id;
        }
    }
    if (isset($_GET['group_id'])) {
        $group = $campaign->groups[$_GET['group_id']];
        $ads = $group->load_ads();
        foreach ($ads as &$ad){
            $ad->load_statistic($previous_month_y,$previous_month,$current_year,$current_month);
        }
    }
    //endregion загрузка групы и ее обявлений

    //    foreach ($ad->statistic as $stat) {
    //        foreach ($stat['actions'] as $action) {
    //            dumpex($action);
    //
    //        }
    //    }

    }


include_once VIEWS . '/includes/header1.php';
include_once VIEWS . '/statistic.php';
include_once VIEWS . '/includes/footer1.php';
