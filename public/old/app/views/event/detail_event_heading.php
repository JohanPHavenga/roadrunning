<div class="c-content-box c-size-sm c-bg-grey-1">
    <div class="container">
        <div class="c-content-bar-2 c-opt-1">
            <div class="row" data-auto-height="true">
                <div class="col-md-8">
                    <!-- Begin: Title 1 component -->
                    <div class="c-content-title-1" data-height="height">
                        <h3 class="c-font-uppercase c-font-bold"><?= $event_detail['edition_name_no_date']; ?></h3>
                    </div>
                    <p class="c-font-sbold">                            
                        Annual <?= $event_detail['event_name']; ?> 
                        <?php
                        if ($event_detail['club_id'] != 8) {
                            echo " organized by ";
                            if (isset($event_detail['club_url_list'][0])) {
                                echo "<a href='".$event_detail['club_url_list'][0]['url_name']."' target='_blank' title='Visit athletics club website' class='link'>".$event_detail['club_name']."</a>";
                            } else {
                                echo $event_detail['club_name'];
                            }
                            
                        }
                        echo " on <strong>" . date("d F Y", strtotime($event_detail['edition_date'])) . "</strong>";

                        if ((!empty($event_detail['sponsor_name'])) && ($event_detail['sponsor_name'] != "No sponsor")) {
                            echo "<br>Brought to you by ";
                            if (isset($event_detail['sponsor_url_list'][0])) {
                                echo "<a href='".$event_detail['sponsor_url_list'][0]['url_name']."' target='_blank' title='Visit sponsor website' class='link'>".$event_detail['sponsor_name']."</a>";
                            } else {
                                echo $event_detail['sponsor_name'];
                            }
                        }
                        ?>
                    </p>
                    <?php
                    // INTRO
                    if (strlen(strip_tags($event_detail['edition_intro_detail'])) > 5) {
                        echo $event_detail['edition_intro_detail'];
                    }

                    // ASA MEMBERSHIP
                    if ($event_detail['asa_member_id'] > 0) {
                        echo "<p style='font-size: 0.9em;'>This event is held under the rules and regulations of "
                        . "<u><a href='https://www.athletics.org.za/' target='_blank' title='Athletics South Africa'>ASA</a></u> "
                        . "and <u><a href='" . $event_detail['asa_member_url'] . "' target='_blank' title='" . $event_detail['asa_member_abbr'] . "'>"
                        . "" . $event_detail['asa_member_name'] . "</a></u></p>";
                    }
                        
                    // Edition BUTTONS
                    $this->load->view("/event/buttons_edition", $event_detail);
//                    echo "<br>";
                    // Race BUTTONS
                    $this->load->view("/event/buttons_race", $event_detail);
                    ?>
                    <!-- End-->

                </div>
                <div class="col-md-4 edition-logo">
                    <?php
                    if (@$event_detail['file_list'][1]) {
                        $img_url = base_url("uploads/edition/" . $event_detail['edition_id'] . "/" . $event_detail['file_list'][1][0]['file_name']);
                        echo "<img src='$img_url' style='max-width: 100%; float:right;'>";

                        // =================================
                        // #toberemoved                      
                        // =================================
//                    } elseif (strlen($event_detail['edition_logo']) > 3) {
//                        $img_url = base_url("uploads/admin/edition/" . $event_detail['edition_id'] . "/" . $event_detail['edition_logo']);
//                        echo "<img src='$img_url' style='max-height: 250px; max-width: 400px;'>";
                    }
                    ?>
                </div>
            </div>
            <?php
//            wts($event_history);
                if ($event_history) {
                $button_class="btn btn-md c-btn-border-2x c-btn-square btn-theme c-btn-uppercase c-btn-bold c-margin-t-20";
            ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <?php
                    if (@$event_history["past"]) {
                        echo '<a href="'.$event_history["past"]['edition_url'].'" title="" class="'.$button_class.' btn-default previous-btn"><i class="fa fa-angle-left"></i> Previous '.$event_detail['event_name'].'</a>';  
                    }
                    ?>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="c-pull-right">
                    <?php
                    if (@$event_history["future"]) {
                        echo '<a href="'.$event_history["future"]['edition_url'].'" title="" class="'.$button_class.' btn-default next-btn">Next '.$event_detail['event_name'].'<i class="fa fa-angle-right"></i></a>';   
                    }
                    ?>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>