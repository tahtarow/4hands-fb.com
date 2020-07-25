<?php

class Ad_accaunt
{
    public
        $id,
        $name,
        $data;

    function __construct($id_acc,$name)
    {
        $this->id=$id_acc;
        $this->name=$name;
    }

    function load(){
        $this->data = $this->request('/campaigns?fields=name');
    }

    public function load_statistic()
    {
//        $this->data = $this->request('/insights?date_preset=last_30d&time_increment=1&limit=100&after=MgZDZD');
        $this->data = $this->request('/insights?date_preset=last_90d&time_increment=1&limit=5000');
    }

    private function request($param){
        return Request::get($this->id . $param);
    }


}
