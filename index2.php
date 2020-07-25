<?php

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v7.0/23844570296250482/previews/?ad_format=INSTAGRAM_STANDARD&access_token=EAAJiNLCP1g8BALw16v5LNdiJJevh4f0AqVDd3ZCDZBdBwgz1mLqK3HJcHMbeDN6KkksVlQtyjILKM3J4DCIkh4DDaHbGj9rISoFH2Q2XBRyuCZCRmp3s5AlT9ViEvuKXLB3YRhybslzaD0XqPwyCrDuhGsjIGPjFBMVaLZCNCgZDZD');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
$result = json_decode($result,1);
echo($result['data'][0]['body']);
