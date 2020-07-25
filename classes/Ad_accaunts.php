<?php

class Ad_accaunts
{
    public
        $data,
        $list;

    function load_accaunts(){
        $result = Request::get('me/adaccounts?fields=name');
        foreach ($result['data'] as $acc) {
            $this->list[$acc['id']] = new Ad_accaunt($acc['id'], $acc['name']);
        }
    }




}
