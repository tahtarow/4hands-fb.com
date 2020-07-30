<?


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

    .campaign iframe {
        overflow: hidden;
    }

    .campaign iframe {
        overflow: hidden;
    }

    .campaign .groups {
        max-height: 800px;
        /*width: 60px;*/
        overflow: auto;
    }

    .align-top {
        vertical-align: top;
    }

    .frame {
        -ms-zoom: 0.5;
        -moz-transform: scale(0.5);
        -moz-transform-origin: 0 0;
        -o-transform: scale(0.5);
        -o-transform-origin: 0 0;
        -webkit-transform: scale(0.5);
        -webkit-transform-origin: 0 0;
    }

    .active_group {
        background-color: lightblue;
    }

    .ad_table td {
        vertical-align: top;
    }

    .ad_table .total_statistic {
        font-size: 10px;
    }

    .ad_table {
        display: block;
        /*overflow: auto;*/
        width: 100%;
        /*max-width: 1000px;*/
    }

    .bg-gray {
        background-color: #ebebeb;
    }

    .float-wrap {
        position: relative;
    }

    .float {
        float: left;
    }

    .clear-float {
        clear: bottom;
    }

</style>


<div class="content">
    <div class="row">
        <div class="col-12 p-2 pl-4">
            <h6>Акаунт</h6>
            <select onchange="top.location=this.value">
                <option value="">-----</option>
                <? foreach ($ad_accaunts->list as $acc) { ?>
                    <option <? if (isset($_GET['ac_id']) and $_GET['ac_id'] == $acc->id) echo 'selected' ?>
                            value="/statistic?ac_id=<?= $acc->id ?>"><?= $acc->name ?></option>
                <? } ?>
            </select>
        </div>
        <? if (!empty($ad_accaunt->campaings)) { ?>

            <div class="col-12  p-2 pl-4">
                <h6>Кампания</h6>
                <select onchange="top.location=this.value">
                    <? foreach ($ad_accaunt->campaings as $campaing) { ?>
                        <option <? if (isset($_GET['group_id']) and $_GET['group_id'] == $campaing->info['id']) echo 'selected' ?>
                                style="<? if ($campaing->info['status'] == 'ACTIVE') {
                                    echo 'color:#33c70e';
                                } else {
                                    echo 'color:gray';
                                } ?>"
                                value="/statistic?ac_id=<?= $_GET['ac_id'] ?>&campaign_id=<?= $campaing->info['id'] ?>"><?= $campaing->info['name'] ?> </option>
                    <? } ?>
                </select>
            </div>


            <? if (isset($campaign)) { ?>
                <div class="board-1 col-12 campaign">
                    <div>

                        <a href="/statistic?campaign_id=<?= $_GET['campaign_id'] ?>&ac_id=<?= $_GET['ac_id'] ?>&group_id=<?= $_GET['group_id'] ?>&year=<?= $current_year - 1 ?>&month=<?= $current_month ?>"><<<</a>
                        <?= $current_year ?>
                        <a href="/statistic?campaign_id=<?= $_GET['campaign_id'] ?>&ac_id=<?= $_GET['ac_id'] ?>&group_id=<?= $_GET['group_id'] ?>&year=<?= $current_year + 1 ?>&month=<?= $current_month ?>">>>></a>

                        <a href="/statistic?campaign_id=<?= $_GET['campaign_id'] ?>&ac_id=<?= $_GET['ac_id'] ?>&group_id=<?= $_GET['group_id'] ?>&year=<?= $previous_month_y ?>&month=<?= $previous_month ?>"><<<</a>
                        <?= $current_month_name ?>
                        <a href="/statistic?campaign_id=<?= $_GET['campaign_id'] ?>&ac_id=<?= $_GET['ac_id'] ?>&group_id=<?= $_GET['group_id'] ?>&year=<?= $next_month_y ?>&month=<?= $next_month ?>">>>></a>

                    </div>
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
                                    <? if (!empty($campaign->groups)) {
                                        $i = 0;
                                        foreach (array_reverse($campaign->groups) as $group) {
                                            ; ?>
                                            <a href="/statistic?campaign_id=<?= $_GET['campaign_id'] ?>&ac_id=<?= $_GET['ac_id'] ?>&group_id=<?= $group->info['id'] ?>"
                                               class=" txt-style-1 <? if ($i <> 0 or isset($campaign->groups[$i])) echo 'border-top border-dark' ?> <? if (isset($_GET['group_id']) and $_GET['group_id'] == $group->info['id']) echo 'active_group' ?>">
                                                <?= $group->info['name'] ?>
                                            </a>
                                            <? $i++ ?>
                                        <? } ?>
                                    <? } ?>
                                </div>
                            </td>
                            <td class="align-top border border-dark ad_table_wrap">
                                <table class="ad_table">
                                </table>

                                <table class="ad_table">

                                    <?

                                    if (!empty($ads)) {
                                        foreach ($ads as $ad) { ?>
                                            <tr>
                                                <td></td>
                                                <td>

                                                    <? if (!empty($ad->statistics_by_month)) {

                                                        ?>
                                                        <span>Активность</span>
                                                        <select name="ad_activity" id=""
                                                                onchange="top.location=this.value">
                                                            <option value="">-----</option>
                                                            <? $iii = 0;
                                                            foreach ($ad->statistics_by_month as $month_stat) {
                                                                $t = explode('-', $month_stat['date_start']);
                                                                $year_start = $t[0];
                                                                $mont_start = $t[1];
                                                                $t = explode('-', $month_stat['date_stop']);
                                                                $year_stop = $t[0];
                                                                $mont_stop = $t[1];
                                                                $url = '/statistic?campaign_id=' . $_GET['campaign_id'] . '&ac_id=' . $_GET['ac_id'] . '&group_id=' . $_GET['group_id'];
                                                                if ($iii > 0) {
                                                                    if (isset($ad->statistics_by_month[$iii]) and explode('-', $ad->statistics_by_month[$iii - 1]['date_stop'])[1] <> $mont_start) {
                                                                        if (!empty($month_stat['date_start'])) { ?>
                                                                            <option <? if ($_GET['year'] . $_GET['month'] == $year_start . $mont_start) echo ' selected ' ?>
                                                                                    value="<?= $url ?>&year=<?= $year_start ?>&month=<?= $year_start ?>">
                                                                                <?= $year_start ?>-<?= $mont_start ?>
                                                                            </option>
                                                                            <?
                                                                        }
                                                                        if (!empty($month_stat['date_stop']) and $mont_start <> $mont_stop) { ?>
                                                                            <option <? if ($_GET['year'] . $_GET['month'] == $year_stop . $mont_stop) echo ' selected ' ?>
                                                                                    value="<?= $url ?>&year=<?= $year_stop ?>&month=<?= $mont_stop ?>">
                                                                                <?= $year_stop ?>-<?= $mont_stop ?>
                                                                            </option>
                                                                            <?
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (!empty($month_stat['date_start'])) { ?>
                                                                        <option <? if ($_GET['year'] . $_GET['month'] == $year_start . $mont_start) echo ' selected ' ?>
                                                                                value="<?= $url ?>&year=<?= $year_start ?>&month=<?= $mont_start ?>">
                                                                            <?= $year_start ?>-<?= $mont_start ?>
                                                                        </option>
                                                                        <?
                                                                    }
                                                                    if (!empty($month_stat['date_stop']) and $mont_start <> $mont_stop) { ?>
                                                                        <option <? if ($_GET['year'] . $_GET['month'] == $year_stop . $mont_stop) echo ' selected ' ?>
                                                                                value="<?= $url ?>&year=<?= $year_stop ?>&month=<?= $mont_stop ?>">
                                                                            <?= $year_stop ?>-<?= $mont_stop ?>
                                                                        </option>
                                                                        <?
                                                                    }
                                                                }
                                                                $iii++;
                                                            } ?>
                                                        </select>
                                                    <? } ?>

                                                </td>
                                                <td>Средние значения</td>
                                                <td>За все время</td>
                                            </tr>
                                            <tr class="ad-tr">
                                                <td class=" border-bottom border-dark">
                                                    <div>
                                                        <a href="/statistic?campaign_id=<?= $_GET['campaign_id'] ?>&ac_id=<?= $_GET['ac_id'] ?>&group_id=<?= $_GET['group_id'] ?>&ad_id=<?= $ad->info['id'] ?>"><?= $ad->info['name'] ?></a>
                                                    </div>
                                                    <div style="position: relative;height: 250px;width: 150px">
                                                        <div class="scrol-blocker"
                                                             style="z-index: 1;width: 100%;height: 100%;position: absolute"></div>
                                                        <iframe class="frame" src="<?= $ad->info['previews'][0] ?>"
                                                                frameborder="0" scrolling="no" height="500"></iframe>
                                                    </div>
                                                </td>
                                                <td class="float-wrap">
                                                    <?
                                                    $spend = 0;
                                                    $impressions = 0;
                                                    $cpm = 0;
                                                    $reach = 0;
                                                    $clicks = 0;
                                                    $total_action = [];
                                                    $ii = 0;
                                                    if (!empty($ad->statistic)) {
                                                        foreach ($ad->statistic as $stat) {
                                                            $ii++;
                                                            $spend += $stat['spend'];
                                                            $impressions += $stat['impressions'];
                                                            $cpm += $stat['cpm'];
                                                            $reach += $stat['reach'];
                                                            $clicks += $stat['clicks'];
                                                            ?>
                                                            <div class="bg-gray border-dark p-2 float"
                                                                 style="border:1px dashed whitesmoke;position:relative;">


                                                                <div style="font-size: 10px;color: darkslategray"><?= $stat['date_start'] ?></div>
                                                                <div title="spend"><?= $stat['spend'] ?></div>
                                                                <div title="impressions"><?= $stat['impressions'] ?></div>
                                                                <div title="cpm"><?= number_format($stat['cpm'], 2, ',', ' ') ?></div>
                                                                <div title="reach"><?= $stat['reach'] ?></div>
                                                                <div title="clicks"><?= $stat['clicks'] ?></div>
                                                                <div onclick=" $ ('.dop-info').hide();$ (this).find('.dop-info').show()">
                                                                    другое
                                                                    <div class="dop-info"
                                                                         style="border:1px solid black;padding:10px;position: absolute;background-color: white;z-index: 1;display: none">
                                                                        <?
                                                                        foreach ($stat['actions'] as $action) {
                                                                            if (!isset($total_action[$action['action_type']])) {
                                                                                $total_action[$action['action_type']] = 0;
                                                                            }
                                                                            $total_action[$action['action_type']] = $total_action[$action['action_type']] + $action['value'];
                                                                            ?>
                                                                            <div onclick=" $ ('.dop-info').hide();"><?= $action['action_type'] ?>
                                                                                : <?= $action['value'] ?></div>
                                                                            <?
                                                                        }


                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <? }
                                                    }
                                                    if ($ii > 0) {
                                                        $spend = $spend / $ii;
                                                        $impressions = $impressions / $ii;
                                                        $cpm = $cpm / $ii;
                                                        $reach = $reach / $ii;
                                                        $clicks = $clicks / $ii;
                                                        foreach ($total_action as $action => $value) {
                                                            $total_action[$action] = $value / $ii;
                                                        }
                                                    }
                                                    ?>
                                                    <div class="clear-float"></div>
                                                </td>
                                                <td class="total_statistic">
                                                    <div>spend:<span
                                                                style="color: coral;font-weight: bold"><?= number_format($spend, 2, ',', ' ') ?></span>
                                                    </div>
                                                    <div>impressions:<span
                                                                style="color: coral;font-weight: bold"><?= number_format($impressions, 2, ',', ' ') ?></span>
                                                    </div>
                                                    <div>cpm:<span
                                                                style="color: coral;font-weight: bold"><?= number_format($cpm, 2, ',', ' ') ?></span>
                                                    </div>
                                                    <div>reach:<span
                                                                style="color: coral;font-weight: bold"><?= number_format($reach, 2, ',', ' ') ?></span>
                                                    </div>
                                                    <div>clicks:<span
                                                                style="color: coral;font-weight: bold"><?= number_format($clicks, 2, ',', ' ') ?></span>
                                                    </div>

                                                    <?
                                                    foreach ($total_action as $stat2 => $val) { ?>
                                                        <div><?= $stat2 ?>:<span
                                                                    style="color: coral;font-weight: bold"><?= number_format($val, 2, ',', ' ') ?></span>
                                                        </div>
                                                    <? } ?>
                                                </td>
                                                <td class="total_statistic">
                                                    <? foreach ($ad->total_statistic as $stat => $val) {
                                                        if (!is_array($val)) {
                                                            ?>
                                                            <div><?= $stat ?>:<span
                                                                        style="color: coral;font-weight: bold"><?= $val ?></span>
                                                            </div>
                                                        <? } else { ?>
                                                            <? foreach ($val as $stat2) { ?>
                                                                <div><?= $stat2['action_type'] ?>:<span
                                                                            style="color: coral;font-weight: bold"><?= $stat2['value'] ?></span>
                                                                </div>
                                                            <? } ?>
                                                        <? } ?>
                                                    <? } ?>
                                                </td>

                                            </tr>
                                        <? } ?>
                                    <? } ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            <? } ?>
        <? } ?>
    </div>
</div>

<script type="text/javascript">

</script>