<?php

class Campaign
{
    public
        $id,
        $data,
        $ads,
        $info;

    function __construct($id)
    {
        $this->id = $id;
    }

    function load()
    {
        $feilds = [
            'spend',
            'impressions',
            'cpm',
            'cpc',
            'reach',
            'clicks',
            'actions',
        ];

        $request = '/insights?date_preset=this_year&time_increment=1&fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request .= $t;
            if (isset($feilds[$i + 1])) {
                $request .= ',';
            }
            $i++;
        }

        $this->data = $this->request($request);
    }

    function load_ads()
    {
        $feilds = [
            'name',
            'billing_event',
            'daily_budget',
            'optimization_goal',
            'promoted_object',
        ];

        $request = '/adsets?fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request .= $t;
            if (isset($feilds[$i + 1])) {
                $request .= ',';
            }
            $i++;
        }

        $res = $this->request($request);
        if (!empty($res['data'])) {
            foreach ($res['data'] as $re) {
                $this->ads[$re['id']] = $re;
            }
        }
    }

    private function request($param)
    {
        return Request::get($this->id . $param);
    }
}






//'spend',
//            'impressions',
//            'cpm',
//            'cpc',
//            'reach',
//            'clicks',
//            'action'