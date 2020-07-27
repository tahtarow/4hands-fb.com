<?php

class Ad_account
{
    public
        $id,
        $name,
        $campaings,
        $data;

    function __construct($id_acc, $name = '')
    {
        if (is_object($id_acc)) {
            $this->id = $id_acc->id;
            $this->name = $id_acc->name;
            $this->data = $id_acc->data;
        } else {
            $this->id = $id_acc;
            $this->name = $name;
        }
    }

    function load_campaigns()
    {
        $feilds = [
            'name',
            'bid_strategy',
            'buying_type',
            'objective',
            'daily_budget',
            'lifetime_budget',
            'spend_cap',
            'status',
            'promoted_object',

        ];

        $request = '/campaigns?fields=';
        $i = 0;
        foreach ($feilds as $t) {
            $request .= $t;
            if (isset($feilds[$i + 1])) {
                $request .= ',';
            }
            $i++;
        }

        $res = $this->request($request)['data'];
        foreach ($res as $r) {
            $this->campaings[$r['id']] = new Campaign($r['id']);
            if (isset($r['daily_budget'])){
                $r['daily_budget'] = substr($r['daily_budget'],0,-2);
            }
            if (isset($r['lifetime_budget'])){
                $r['lifetime_budget'] = substr($r['lifetime_budget'],0,-2);
            }
            $this->campaings[$r['id']]->info = $r;
        }
//        dumpex($this->campaings);
    }

    private function request($param)
    {
        return Request::get($this->id . $param);
    }


}
