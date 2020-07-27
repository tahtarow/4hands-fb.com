<?php

class Request
{
    static function get($param)
    {
        $current_hour = date('dh');
        $url = 'https://graph.facebook.com/v7.0/' . $param . '&access_token=' . TOKEN;
        $cache_id = md5($url);
        $cache = include ROOT . '/cache/fb-graph.txt';

        if ($cache === true) $cache['cache'] = [];


        if (!isset($cache['cache'][$current_hour])) {
            $cache['cache'] = [];
        }

        if (isset($cache['cache'][$current_hour][$cache_id])) {
            $result = $cache['cache'][$current_hour][$cache_id];
        } else {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = json_decode(curl_exec($ch), 1);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $cache['cache'][$current_hour][$cache_id] = $result;
            var_export_to_file($cache, ROOT . '/cache/fb-graph.txt');

        }
        return $result;
    }
}
