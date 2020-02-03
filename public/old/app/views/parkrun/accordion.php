<div class="c-content-accordion-1">

    <div class="panel-group" id="accordionParkrun" role="tablist" style="padding-top:10px;">
        <?php
        if ($parkrun_list) {
        foreach ($parkrun_list as $id => $parkrun) {
            // if first
            reset($parkrun_list);
            if ($id === key($parkrun_list)) {  $expanded = "in"; }
            // set map url
            $map_url = "https://www.google.com/maps/place/" . $parkrun['latitude_num'] . "," . $parkrun['longitude_num'];
            ?>
            <div class="panel">
                <div class="panel-heading" role="tab" id="heading_<?= $id; ?>">
                    <h4 class="panel-title">
                        <a class="" data-toggle="collapse" data-parent="#accordionParkrun" href="#collapse_<?= $id; ?>" aria-expanded="false" aria-controls="collapse_<?= $id; ?>">
                            <table class="accordian" style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td style="width: 10px;">
                                            <i class="c-font-green fa fa-check-square" title="Gathering event inforamtion"></i> 
                                        </td>
                                        <td style="color:#333;"><?= $parkrun['parkrun_name']?></td>
                                        <td class="badges hidden-xs">
                                            <span class="badge c-bg-yellow">5</span> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </a>
                    </h4>
                </div>
                <div id="collapse_<?= $id; ?>" class="panel-collapse collapse <?= @$expanded; ?>" role="tabpanel" aria-labelledby="heading_<?= $id; ?>" aria-expanded="false">
                    <div class="panel-body">
                        <p style="margin:0 0 5px;">
                            <span class="visible-xs">
                                <span class="badge c-bg-yellow">5</span> 
                            </span>
                        </p>
                        <p>
                            <b>When: </b>Every Saturday<br>
                            <b>Where: </b><?= $parkrun['parkrun_address']; ?><br>
                            <b>Distance: </b>5km<br>
                            <b>Start Time: </b>08:00<br>
                        </p>
                        <p>
                        <div class='btn-group'>
                            <a href="<?= $parkrun['parkrun_url']; ?>" target="_blank" class="btn c-theme-btn c-btn-border-2x">DETAIL</a>
                            <a href="mailto:<?= $parkrun['user_email']; ?>" class="btn c-theme-btn c-btn-border-2x">CONTACT</a>
                            <a href="<?= $map_url; ?>" target="_blank" class="btn c-theme-btn c-btn-border-2x">MAP</a>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        unset($expanded);
        }
        } else {
            echo "<p>No Parkruns availble in this area";
        }
        ?>
    </div> <!-- .panel-group -->
</div> <!-- .c-content-accordion-1 -->
