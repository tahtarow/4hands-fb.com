<?php

class Ad
{
    public
        $id,
        $info,
        $total_statistic,
        $statistics_by_month,
        $statistic;

    function __construct($id)
    {
        $this->id = $id;
    }

    function load()
    {
        $feilds = [
            'id',
            'status',
            'name',
            'campaign',
            'configured_status',
            'creative',
            'source_ad',
        ];

        $request = '?fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request .= $t;
            if (isset($feilds[$i + 1])) {
                $request .= ',';
            }
            $i++;
        }
        $res = $this->request($request);
        $id = $res['id'];
        if (!empty($res)) {
            $this->info = $res;
        }

        $request = '/previews/?ad_format=INSTAGRAM_STANDARD&width=500';
        $res = $this->request($request);
        $this->info['previews'] = $res['data'][0]['body'];
        preg_match_all('!https?://\S+!', $this->info['previews'], $matches);
        $this->info['previews'] = $matches[0];
    }

    function load_statistic($year_from = '', $month_from = '', $year_to = '', $month_to = '')
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

        if (empty($year_from)) {
            $time_interval = 'date_preset=last_30d';
        } else {
            $from = $year_from . '-' . $month_to . '-' . '01';
            $to = $year_to . '-' . $month_to . '-' . '31';
            $time_interval = 'time_range[since]=' . $from . '&time_range[until]=' . $to;
        }



        $request1 = '/insights?' . $time_interval . '&time_increment=1&limit=5000&fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request1 .= $t;
            if (isset($feilds[$i + 1])) {
                $request1 .= ',';
            }
            $i++;
        }
        $res = $this->request($request1);
        if (isset($res['data'])) {
            $this->statistic = $res['data'];
        }

        $request2 = '/insights?time_range[since]=2000-01-01&time_range[until]=9999-01-01&fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request2 .= $t;
            if (isset($feilds[$i + 1])) {
                $request2 .= ',';
            }
            $i++;
        }
        $res = $this->request($request2);
        if (isset($res['data'][0])) {
            $this->total_statistic = $res['data'][0];
        }
        $request3 = '/insights?time_range[since]=2000-01-01&time_range[until]=9999-01-01&time_increment=30&fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request3 .= $t;
            if (isset($feilds[$i + 1])) {
                $request3 .= ',';
            }
            $i++;
        }
        $res = $this->request($request3);
        $this->statistics_by_month = $res['data'];


//        dumpex($this->id.$request1);
//        dump(Request::get($this->id.'?include_headers=false&batch=['.
//            '{"method":"GET","relative_url":"'.$this->id.$request1.'"},'.
//            '{"method":"GET","relative_url":"me/adaccounts"}'.
//            ']'));



    }

    private function request($param)
    {
        return Request::get($this->id . $param);
    }
}