<?php

class Request
{
    static function get($param)
    {
        $current_hour = date('dh');
        $url = 'https://graph.facebook.com/v7.0/' . $param . (in_text('?',$param)?'&':'?').'access_token=' . TOKEN;
//        dump($url);
        $cache_id = md5($url);
        $cache = include ROOT . '/cache/fb-graph.txt';

        if ($cache === true) $cache['cache'] = [];
        if ($cache === 1) $cache['cache'] = [];


        if (!isset($cache['cache'][$current_hour])) {
            $cache['cache'] = [];
        }

        if (isset($cache['cache'][$current_hour][$cache_id])) {
            $result = $cache['cache'][$current_hour][$cache_id];
        } else {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if(in_text('batch=',$url)){
                curl_setopt($ch, CURLOPT_POST, 1);
            }

            $result = json_decode(curl_exec($ch), 1);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

//            dump($url);


            $cache['cache'][$current_hour][$cache_id] = $result;
            var_export_to_file($cache, ROOT . '/cache/fb-graph.txt');

        }
        return $result;
    }
}
