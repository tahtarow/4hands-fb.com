<?php

class Ad_campaign
{
    public
        $id,
        $data,
        $groups,
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



    function load_groups()
    {
        $feilds = [
            'name',
            'billing_event',
            'daily_budget',
            'optimization_goal',
            'promoted_object',
            'status',
            'targeting',
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
                $this->groups[$re['id']] = new Ad_group($re['id']);
                $this->groups[$re['id']]->info = $re;
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