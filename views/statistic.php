<?
//foreach ($ad_accaunts->list as $t){
//    dumpex($t->name);
//}

?>
<style>

    .v-text-wraper {
        /*height: 500px;*/
        /*position: relative;*/
    }

    .board-1 {
        padding: 30px;
    }

    .v-text {
        -webkit-transform: rotate(-180deg);
        -moz-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        -o-transform: rotate(-180deg);
        transform: rotate(-180deg);
        writing-mode: vertical-rl;
        white-space: nowrap;
    }


    .txt-style-1 {
        padding: 5px 0;
        color: black;
        text-decoration: none;
        font-size: 25px;
        font-weight: bold;
    }

    table td {
        /*border: 2px solid black;*/
    }

    .campaign .groups {
        max-height: 800px;
        /*width: 60px;*/
        overflow: auto;
    }

    .align-top {
        vertical-align: top;
    }
</style>


<div class="content">
    <div class="row">
        <div class="col-12 p-2 pl-4">
            <h6>Акаунт</h6>
            <select onchange="top.location=this.value">
                <? foreach ($ad_accaunts->list as $acc) { ?>
                    <option <? if ($_GET['ac_id'] == $acc->id) echo 'selected' ?>
                            value="/statistic?ac_id=<?= $acc->id ?>"><?= $acc->name ?></option>
                <? } ?>
            </select>
        </div>
        <? if (!empty($ad_accaunt->campaings)) { ?>
            <div class="col-12  p-2 pl-4">
                <h6>Кампания</h6>
                <select onchange="top.location=this.value">
                    <? foreach ($ad_accaunt->campaings as $campaing) { ?>
                        <option <? if ($_GET['campaign_id'] == $campaing->info['id']) echo 'selected' ?>
                                style="<? if ($campaing->info['status'] == 'ACTIVE') {
                                    echo 'color:#33c70e';
                                } else {
                                    echo 'color:gray';
                                } ?>"
                                value="/statistic?ac_id=<?= $_GET['ac_id'] ?>&campaign_id=<?= $campaing->info['id'] ?>"><?= $campaing->info['name'] ?></option>
                    <? } ?>
                </select>
            </div>


            <? if (isset($campaign)) { ?>
                <div class="board-1 col-12 campaign">
                    <table>
                        <tr>
                            <td class="border border-dark align-top">
                                <div class="v-text txt-style-1 ">
                                    <?= $campaign->info['name'] ?>
                                    <span class="txt-style-1 text-success"><?= $campaign->info['objective'] ?></span>
                                    <? if (isset($campaign->info['daily_budget'])) { ?>
                                        <span class="txt-style-1 text-primary">(<?= $campaign->info['daily_budget'] ?> р./д.)</span>
                                    <? } ?>
                                    <? if (isset($campaign->info['lifetime_budget'])) { ?>
                                        <span class="txt-style-1 text-primary">(<?= number_format($campaign->info['lifetime_budget'], 0, ',', ' ') ?> р.)</span>
                                    <? } ?>

                                </div>
                            </td>
                            <td class="border border-dark align-top">
                                <div class="v-text ">
                                    <? if (!empty($campaign->ads)) {
                                        $i = 0;
                                        foreach ($campaign->ads as $ad) {
                                            ; ?>
                                            <a href="/statistic?ac_id=<?= $_GET['ac_id'] ?>&campaign_id=<?= $_GET['campaign_id'] ?>&ad_id=<?= $ad['id'] ?>"
                                               class=" txt-style-1 <? if ($i <> 0 or isset($campaign->ads[$i])) echo 'border-top border-dark' ?>">
                                                <?= $ad['name'] ?>
                                            </a>
                                            <? $i++ ?>
                                        <? } ?>
                                    <? } ?>
                                </div>
                            </td>
                            <td class="align-top border border-dark">
                                <table>
                                    <tr>
                                        <td>asdfas</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            <? } ?>
        <? } ?>
    </div>
</div>
