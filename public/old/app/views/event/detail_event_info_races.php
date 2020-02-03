<?php
foreach ($event_detail['race_list'] as $race_id => $race) {

//    wts($race);

    switch ($race['race_distance']) {
        case 42.2:
            $t_prog_text = "Marathon Training Program";
            $t_prog_link = "https://coachparry.com/marathon-training-roadmap/?ref=9";
            break;
        case 21.1:
            $t_prog_text = "Half-Marathon Training Program";
            $t_prog_link = "https://coachparry.com/half-marathon-training-roadmap/?ref=9";
            break;
        case 10:
            $t_prog_text = "10K Training Program";
            $t_prog_link = "https://coachparry.com/10k-training-roadmap/?ref=9";
            break;
        case 5:
            $t_prog_text = "Training Program";
            $t_prog_link = "https://coachparry.com/join-coach-parry/?ref=9";
            break;
        default:
            $t_prog_text = "Training Program";
            $t_prog_link = "https://coachparry.com/join-coach-parry/?ref=9";
            break;
    }

    if ($race['race_date'] > 0) {
        $race_date = "<span style='color:red'>".fdateHuman($race['race_date'])."</span>";
    } else {
        $race_date = fdateHuman($race['edition_date']);
    }
    ?>
    <div class="c-content-box c-size-sm <?= $box_color; ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="c-content-title-1 c-title-pricing-1">
                        <h3 class="c-font-uppercase c-font-bold">
                            <?php
                            echo $race['race_name'];
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="c-content-pricing-1 c-opt-1">
                    <div class="col-md-3 c-sm-hidden">
                        <div class="c-content c-column-odd c-padding-adjustment">
                            <div class="c-row c-title c-font-19">Date</div>
                            <div class="c-row c-title c-font-19">Start Time</div>
                            <?php
                            if ($race['race_time_end'] > 0) {
                                ?>
                                <div class="c-row c-title c-font-19">Cut-off Time</div>
                                <?php
                            }
                            ?>
                            <div class="c-row c-title c-font-19">Distance</div>
                            <div class="c-row c-title c-font-19">Race Type</div>
                            <?php
                            if ($race['race_fee_flat'] > 0) {
                                echo '<div class="c-row c-title c-font-19">Race fee</div>';
                            } else {
                                if ($race['race_fee_senior_licenced'] > 0) {
                                    echo '<div class="c-row c-title c-font-19">Licenced Athlete</div>';
                                }
                                if ($race['race_fee_senior_unlicenced'] > 0) {
                                    echo '<div class="c-row c-title c-font-19">Unlicenced Athlete</div>';
                                }
                                if ($race['race_fee_junior_licenced'] > 0) {
                                    echo '<div class="c-row c-title c-font-19">Licenced Junior</div>';
                                }
                                if ($race['race_fee_junior_unlicenced'] > 0) {
                                    echo '<div class="c-row c-title c-font-19">Unicenced Junior</div>';
                                }
                            }
                            if ($race['race_isover70free']) {
                                echo '<div class="c-row c-title c-font-19">Licenced Athlete 70+</div>';
                            }
                            if ($race['race_minimum_age'] > 0) {
                                echo '<div class="c-row c-title c-font-19">Minimum age</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="c-content c-column-even c-padding-adjustment">
                            <div class="c-row c-font-19">
                                <span class="c-sub-title">Date: </span> 
                                <span class="c-font-19 c-font-bold"><?= $race_date ?></span>
                            </div>
                            <div class="c-row c-font-19">
                                <span class="c-sub-title">Start Time: </span> 
                                <span class="c-font-19 c-font-bold"><?= ftimeSort($race['race_time_start']); ?></span>
                            </div>
                            <?php
                            if ($race['race_time_end'] > 0) {
                                ?>
                                <div class="c-row c-font-19">
                                    <span class="c-sub-title">Cut-off Time: </span> 
                                    <span class="c-font-19 c-font-bold"><?= ftimeSort($race['race_time_end']); ?></span>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="c-row c-font-19">
                                <span class="c-sub-title">Distance: </span>
                                <span class="c-font-19 c-font-bold"><?= fraceDistance($race['race_distance']); ?></span>
                            </div>
                            <div class="c-row c-font-19">
                                <span class="c-sub-title">Race Type: </span> 
                                <span class="c-font-19 c-font-bold"><?= $race['racetype_name']; ?></span>
                            </div>
                            <?php
                            if ($race['race_fee_flat'] > 0) {
                                echo '<div class="c-row c-price">';
                                echo '<span class="c-sub-title">Race fee: </span>';
                                echo '<span class="c-dollar c-font-19">R</span>';
                                echo '<span class="c-font-19 c-font-bold">';
                                echo $race['race_fee_flat'] + 0;
                                echo '</span></div>';
                            } else {
                                if ($race['race_fee_senior_licenced'] > 0) {
                                    ?>
                                    <div class="c-row c-price">
                                        <span class="c-sub-title">Licenced Athlete: </span> 
                                        <span class="c-dollar c-font-19">R</span>
                                        <span class="c-font-19 c-font-bold"><?= $race['race_fee_senior_licenced'] + 0; ?></span>
                                    </div>
                                    <?php
                                }
                                if ($race['race_fee_senior_unlicenced'] > 0) {
                                    ?>
                                    <div class="c-row c-price">
                                        <span class="c-sub-title">Unlicenced Athlete: </span> 
                                        <span class="c-dollar c-font-19">R</span>
                                        <span class="c-font-19 c-font-bold"><?= $race['race_fee_senior_unlicenced'] + 0; ?></span>
                                    </div>
                                    <?php
                                }
                                if ($race['race_fee_junior_licenced'] > 0) {
                                    ?>
                                    <div class="c-row c-price">
                                        <span class="c-sub-title">Licenced Junior: </span> 
                                        <span class="c-dollar c-font-19">R</span>
                                        <span class="c-font-19 c-font-bold"><?= $race['race_fee_junior_licenced'] + 0; ?></span>
                                    </div>
                                    <?php
                                }
                                if ($race['race_fee_junior_unlicenced'] > 0) {
                                    ?>
                                    <div class="c-row c-price">
                                        <span class="c-sub-title">Unlicenced Junior: </span> 
                                        <span class="c-dollar c-font-19">R</span>
                                        <span class="c-font-19 c-font-bold"><?= $race['race_fee_junior_unlicenced'] + 0; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            if ($race['race_isover70free']) {
                                ?>
                                <div class="c-row c-price">
                                    <span class="c-sub-title">Licenced Athlete 70+: </span> 
                                    <span class="c-font-19 c-font-bold">Free</span>
                                </div>
                                <?php
                            }
                            if ($race['race_minimum_age'] > 0) {
                                ?>
                                <div class="c-row c-price">
                                    <span class="c-sub-title">Minimum age: </span> 
                                    <span class="c-font-19 c-font-bold"><?= $race['race_minimum_age']; ?></span>
                                    <span class="c-dollar c-font-19"> years</span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6 c-sm-hidden">
                        <div class="row">
                            <div class="col-md-3 c-md-hidden"></div>
                            <div class="col-md-6">
                                <div class="c-content-v-center c-bg-<?= $race['race_color']; ?>" data-height="height" style="height: 100px;">
                                    <div class="c-wrapper">
                                        <div class="c-body">
                                            <h1 class="c-font-white c-font-bold c-font-uppercase c-font-center"><?= fraceDistance($race['race_distance']); ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="c-content-v-center c-bg-grey-3" data-height="height" style="height: 100px;">
                                    <div class="c-wrapper">
                                        <div class="c-body">
                                            <h1 class="c-font-white c-font-bold c-font-uppercase c-font-center" title="<?= $race['racetype_name']; ?>"><?= $race['racetype_abbr']; ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9 c-margin-t-20">
                                <ul>
                                    <?php
                                    // LIMIT
                                    if ($race['race_entry_limit']>0) {
                                        echo "<li>The entry limit for this race <strong>".$race['race_entry_limit']."</strong> entrants</li>";
                                    }
                                    // OVER 70
                                    if ($race['race_isover70free']) {
                                        $fee70 = $race['race_fee_senior_unlicenced'] - $race['race_fee_senior_licenced'];
                                        echo "<li>Unlicenced Athlete 70+: R <strong>$fee70</strong></li>";
                                    }
                                    // LIMIT
                                    if (!empty($race['race_address'])) {
                                        echo "<li><span style='color:red'><strong>NOTE</strong></span> that the <strong>starting address</strong> for this race differs from the end address:<br><strong>".$race['race_address']."</strong></li>";
                                    }
                                    ?>
                                </ul>
                                <?= $race['race_notes']; ?>
                                <?php
                                if ($race['race_distance'] > 4) {
                                    ?>
                                    <div class="btn-group" style="float: right">
                                        <a href="<?= $t_prog_link; ?>" target="_blank" class="btn btn-md c-btn-border-2x c-theme-btn c-btn-uppercase c-btn-bold c-margin-t-20">
                                            <i class="fa fa-calendar-o"></i> <?= $t_prog_text ?></a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="row">
                <div class="c-content-pricing-1 c-opt-1">
                    <div class="col-md-12 c-lg-hidden">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 10px;">
                                <ul>
                                    <?php
                                    if ($race['race_isover70free']) {
                                        $fee70 = $race['race_fee_senior_unlicenced'] - $race['race_fee_senior_licenced'];
                                        echo "<li>Unlicenced Athlete 70+: R <strong>$fee70</strong></li>";
                                    }
                                    ?>
                                </ul>
                                <?= $race['race_notes']; ?>
                            </div>
                            <?php
                            if ($race['race_distance'] > 4) {
                                ?>
                                <div class="col-md-12">
                                    <div class="btn-group">
                                        <a href="<?= $t_prog_link; ?>" target="_blank" class="btn btn-md c-btn-border-2x c-theme-btn c-btn-uppercase c-btn-bold c-margin-t-10">
                                            <i class="fa fa-calendar-o"></i> <?= $t_prog_text; ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // NEXT AND PREVIOUS BUTTONS
            $button_class = "btn btn-md c-btn-border-2x c-btn-square btn-theme c-btn-uppercase c-btn-bold c-margin-t-20";
            // check for last iteration of the loop
            if (!next($event_detail['race_list'])) {
                $sub_id = "subscribe";
            } else {
                $sub_id = "";
            }
            ?>
            <div class="row" id="<?= $sub_id; ?>"></div>

                <!--            <div class="row" id="<?= $sub_id; ?>">
                   <div class="col-md-6 col-sm-6 col-xs-6">
            <?php
            if ($prev_race_list[$race_id]) {
                echo '<a href="' . $prev_race_list[$race_id]['url'] . '" title="' . $prev_race_list[$race_id]['edition_name'] . '" class="' . $button_class . ' btn-default previous-btn"><i class="fa fa-angle-left"></i> Previous ' . intval($prev_race_list[$race_id]['race_distance']) . 'k</a>';
            }
            ?>
                   </div>
                   <div class="col-md-6 col-sm-6 col-xs-6">
                       <div class="c-pull-right">
            <?php
            if ($next_race_list[$race_id]) {
                echo '<a href="' . $next_race_list[$race_id]['url'] . '" title="' . $next_race_list[$race_id]['edition_name'] . '" class="' . $button_class . ' btn-default next-btn">Next ' . intval($prev_race_list[$race_id]['race_distance']) . 'k <i class="fa fa-angle-right"></i></a>';
            }
            ?>
                       </div>
                   </div>
                </div>-->
        </div>
    </div>  
    <?php
    // set box_color
    if ($box_color == "c-bg-grey-1") {
        $box_color = '';
    } else {
        $box_color = "c-bg-grey-1";
    }
}
?>