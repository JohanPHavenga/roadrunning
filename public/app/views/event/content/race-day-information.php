<?php
if (!in_array(3, $edition_data['regtype_list'])) {
?>
    <div class="row m-b-40">
        <div class="col-lg-12">
            <div class="heading-text heading-line">
                <h4 class="text-uppercase">Registration / Number Collection</h4>
            </div>
            <ul>
                <?php
                // OTD Reg
                if (isset($edition_data['regtype_list'][1])) {
                    echo "<li>Registration will take place <b>on the day</b> from <b>" .
                        ftimeMil($date_list[9][0]['date_start']);
                    if (!time_is_midnight($date_list[9][0]['date_end'])) {
                        echo " - " . ftimeMil($date_list[9][0]['date_end']);
                    }
                    echo "</b></li>";
                } else {
                    echo "<li class='red em'>No number collection on race day</li>";
                }

                // PRE Reg
                if (isset($edition_data['regtype_list'][2])) {
                    echo "<li><b>Registration / Number collection will take place on:</b><ul>";
                    foreach ($date_list[10] as $date) {
                        echo "<li>" . fdateHumanFull($date['date_start'], true, true) . "-" . ftimeMil($date['date_end']) . " @ " . $date['venue_name'] . "</li>";
                    }
                    echo "</ul></li>";
                }
                ?>
            </ul>
            <?php
            // always show what is in the box
            if (strlen($edition_data['edition_reg_detail']) > 10) {
                echo $edition_data['edition_reg_detail'];
            }
            ?>
        </div>
    </div>
<?php
}
?>
<div class="row m-b-40">
    <div class="col-lg-12">
        <?php
        // if you touvh this, update the summary  view as well
        if (
            (strlen($edition_data['edition_general_detail']) > 10) ||
            ($edition_data['edition_info_medals']) ||
            ($edition_data['edition_info_togbag']) ||
            ($edition_data['edition_info_headphones']) ||
            ($edition_data['edition_info_prizegizing'] != "00:00:00")
        ) {
        ?>
            <div class="heading-text heading-line">
                <h4 class="text-uppercase">General Information</h4>
            </div>
            <ul>
                <?php
                // MEDALS
                if ($edition_data['edition_info_medals']) {
                    echo "<li><b>MEDALS:</b> ";
                    if (!empty($edition_data['edition_info_medals_text'])) {
                        echo $edition_data['edition_info_medals_text'];
                    } else {
                        echo "Medals will be awarded to all finishers within cut-off times";
                    }
                    echo "</li>";
                } elseif (!empty($edition_data['edition_info_medals_text'])) {
                    echo "<li><b>HANDOUTS:</b> " . $edition_data['edition_info_medals_text'];
                }

                // PRIZE-GIVING
                if (!is_null($edition_data['edition_info_prizegizing'])) {
                    if ($edition_data['edition_info_prizegizing'] != "00:00:00") {
                        echo "<li><b>PRIZE-GIVING:</b> Scheduled to start at " . ftimeMil($edition_data['edition_info_prizegizing']) . "</li>";
                    }
                }

                // LUCKY DRAWS
                if ($edition_data['edition_info_luckydraw']) {
                    echo "<li><b>LUCKY DRAWS:</b> Many lucky draws will be available</li>";
                }

                // TOG BAG
                if ($edition_data['edition_info_togbag']) {
                    echo "<li><b>TOG BAG:</b> Tog bag facilities will be available, used at own risk</li>";
                }

                // REFRESHMENTS
                if ($edition_data['edition_info_refreshments']) {
                    echo "<li><b>REFRESHMENTS:</b> Refreshments will be on sale at the event</li>";
                }

                // SOCIAL WALKERS
                if ($edition_data['edition_info_socialwalkers']) {
                    echo "<li><b>WALKERS:</b> Walkers are welcome</li>";
                }

                // HEADPHONES
                if ($edition_data['edition_info_headphones']) {
                    echo "<li><b>HEADPHONES:</b> The use of music players with headphones is not allowed and may result in disqualification</li>";
                }
                ?>
            </ul>
        <?php
            // GENERAL INFORMATION
            echo $edition_data['edition_general_detail'];
        } else {
        ?>
            <p class='text-warning'><b>No more information</b> regarding this race has been released yet.</p>
            <p>Do you want to get notified once information is released? Add yourself to the <a href="<?=base_url("event/".$edition_data['edition_slug']."/subscribe");?>">mailing list</a> for the event.</p>
        <?php
        }
        ?>
    </div>
</div>
<?php
if ($edition_data['edition_status'] != 17) {
?>
    <!-- <div class="row m-b-40">
        <div class="col-lg-12">
            <div class="heading-text heading-line">
                <h4 class="text-uppercase">Location Map</h4>
            </div>
            <iframe width="100%" height="350" frameborder="0" style="border:0; margin-bottom: 10px;" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBERO5xjCtTOmjQ_zSSUvlp5YN_l-4yKQw&q=<?= $address_nospaces; ?>" allowfullscreen>
            </iframe>
            <p>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_address'] . "," . $edition_data['town_name']; ?>" class="btn btn-light">
                    <i class="fa fa-map"></i> Get Directions</a>
                <?php
                // If not in the past
                if (!$in_past) {
                ?>
                    <a href="<?= base_url("event/" . $slug . "/accommodation"); ?>" class="btn btn-light"><i class="fa fa-bed"></i> Find Accommodation</a>
                <?php
                }
                ?>
            </p>
        </div>
    </div> -->
<?php
}
?>
