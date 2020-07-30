<?php

class Ad_group
{
    public
        $info,
        $ads,
        $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    function load_ads()    {
        $feilds = [
            'name',
            'asset_feed_spec',
        ];

        $request = '/ads?fields=';
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
                $this->ads[$re['id']] = new Ad($re['id']); ;
                $this->ads[$re['id']]->info = $re;
                $this->ads[$re['id']]->load();
            }
        }
        return $this->ads;
    }


    private function request($param)
    {
        return Request::get($this->id . $param);
    }

}
