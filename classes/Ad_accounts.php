<?php

class Ad_accounts
{
    public
        $data,
        $list;

    function load_accaunts(){
        $result = Request::get('me/adaccounts?fields=name,currency');
        foreach ($result['data'] as $acc) {
            $this->list[$acc['id']] = new Ad_account($acc['id'], $acc['name']);
            $this->list[$acc['id']]->data = $acc;
        }
    }




}
