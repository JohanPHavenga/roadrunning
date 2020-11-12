<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>How to Enter</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">

                <!-- ad box -->
                <div class="row m-b-10 m-t-10">
                    <div class="col-lg-12">
                        <?php
                        // LANDSCAPE ADS WIDGET
                        $this->load->view('widgets/horizontal_ad');
                        ?>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <?php
                        if ((strlen($edition_data['edition_entry_detail']) < 10) && (in_array(5, $edition_data['entrytype_list']))) {
                        ?>
                            <p class='text-info'><b>No details</b> regarding the entries for this race has been published yet.</p>
                            <p>Want to get notified once entries open? Enter your email below or to the right.</p>
                            <?php
                        } else {
                            if (
                                isset($edition_data['entrytype_list'][4]) && // entrytype for online entries
                                isset($url_list[5]) && // there is a url loaded for online entries
                                isset($date_list[3][0]['date_start']) && // online entries open date exists
                                (strtotime($date_list[3][0]['date_start']) < time())    // online entries date has passed
                            ) {
                                // check if entries has closed
                                if (strtotime($date_list[3][0]['date_end']) < time()) {
                                    $btn_state = "danger";
                                    $btn_text = "Online entries closed";
                                    $icon = "minus-circle";
                                } else {
                                    $btn_state = "default";
                                    $btn_text = "Enter online";
                                    $icon = "arrow-right";
                                }
                            ?>
                                <p>
                                    <a href="<?= $url_list[5][0]['url_name']; ?>" class="btn btn-<?= $btn_state; ?> btn-creative btn-icon-holder btn-shadow btn-light-hover"><?= $btn_text; ?>
                                        <i class="fa fa-<?= $icon; ?>"></i></a>
                                </p>
                            <?php
                            }
                            //                            echo "Now: ".time();
                            //                            echo "<br>Close date: ".strtotime($date_list[3][0]['date_end']);
                            ?>
                            <ul>
                                <?php
                                // Online entries
                                // check vir online entries
                                if (isset($edition_data['entrytype_list'][4])) {
                                    // if open date is not equal to the race date
                                    if (strtotime($date_list[3][0]['date_start']) != strtotime($edition_data['edition_date'])) {
                                        if (strtotime($date_list[3][0]['date_start']) > time()) {
                                            echo "<li>Online entries will open on <b style='color: red;'>" . fdateEntries($date_list[3][0]['date_start'], true) . " </b>";
                                        }
                                    } else {
                                        echo "<li>Online entries will <b>open soon</b>";
                                    }
                                    // check vir closing date
                                    if (strtotime($date_list[3][0]['date_end']) != strtotime($edition_data['edition_date'])) {
                                        $d = 's';
                                        // check if already closed
                                        if (strtotime($date_list[3][0]['date_end']) < time()) {
                                            $d = "d";
                                        }
                                        echo "<li>Online entries close$d on <b style='color: red;'>" . fdateEntries($date_list[3][0]['date_end']) . "</b></li>";
                                    }
                                } else {
                                    echo "<li class='text-danger'>No online entries available</li>";
                                }

                                // OTD entries
                                if (isset($edition_data['entrytype_list'][1])) {
                                    // check if time has been set
                                    if (strtotime($date_list[6][0]['date_start']) != strtotime($edition_data['edition_date'])) {
                                        echo "<li>Entries will be taken <span class='text-danger'><b>on the day</b></span> from <b>" .
                                            ftimeMil($date_list[6][0]['date_start']);
                                        if (!time_is_midnight($date_list[6][0]['date_end'])) {
                                            echo " - " . ftimeMil($date_list[6][0]['date_end']);
                                        }
                                        echo "</b></li>";
                                    }
                                } else {
                                    echo "<li class='text-danger'><b>No entries available on race day</b>";
                                    if ($edition_data['edition_entry_funrun_otd']) {
                                        echo " expect for the Fun Run";
                                    }
                                    echo "</li>";
                                }

                                // Manual entries
                                if (isset($edition_data['entrytype_list'][2])) {
                                    // check if values are set
                                    if (strtotime($date_list[5][0]['date_start']) != strtotime($edition_data['edition_date'])) {
                                        if (!empty($date_list[5][0]['venue_name'])) {
                                            echo "<li>Pre-Entries can also be completed at " . $date_list[5][0]['venue_name'] . "</li>";
                                        }
                                        echo "<li>Closing date for manual pre-entries is <u>" . fdateEntries($date_list[5][0]['date_end'], true, true) . "</u></li>";
                                    }
                                }

                                // PRE entries
                                if (isset($edition_data['entrytype_list'][3])) {
                                    // check if date is set, if closetime is not midnight and if venue is set
                                    if (
                                        (strtotime($date_list[4][0]['date_start']) != strtotime($edition_data['edition_date'])) &&
                                        (!time_is_midnight($date_list[4][0]['date_end'])) &&
                                        (!empty($date_list[4][0]['venue_name']))
                                    ) {
                                        echo "<li><b>Entries will be taken on:</b><ul>";
                                        foreach ($date_list[4] as $date) {
                                            echo "<li>" . fdateEntries($date['date_start'], true, true) . "-" . ftimeMil($date['date_end']) . " @ " . $date['venue_name'] . "</li>";
                                        }
                                        echo "</ul></li>";
                                    }
                                }


                                // OTD entries for Fun Run
                                //                                if ($edition_data['edition_entry_funrun_otd']) {
                                //                                    foreach ($race_list as $race) {
                                //                                        if ($race['race_distance'] < 10) {
                                //                                            echo "<li>Entries for the " . $race['race_name'] . " will be taken on the day</li>";
                                //                                        }
                                //                                    }
                                //                                }
                                // ENTRY LIMIT
                                if (!empty($edition_data['edition_entry_limit'])) {
                                    echo "<li><strong>NOTE</strong> that the entry limit for this event is <u>" . $edition_data['edition_entry_limit'] . " entrants</u></li>";
                                }

                                // ADMIN FEES
                                if (!empty($edition_data['edition_admin_fee'])) {
                                    echo "<li>An admin fee of <strong>" . $edition_data['edition_admin_fee'] . "</strong> will be levied for " . $edition_data['edition_admin_option'] . " entries</li>";
                                }

                                // Entries Non-refundable
                                if ($edition_data['edition_entry_nonrefund']) {
                                    echo "<li>Entry fees are non-refundable</li>";
                                }

                                // BULK entries
                                if ($edition_data['edition_entry_bulk']) {
                                    echo "<li>For bulk entries (5+) please contact the organisers: "
                                        . "<a href='mailto:" . $edition_data['user_email'] . "?subject=Bulk entries for " . $edition_data['edition_name'] . "' class='link'>"
                                        . $edition_data['user_email'] . "</a></li>";
                                }

                                // Subsitutions
                                if ($edition_data['edition_entry_nosubstitution']) {
                                    echo "<li><strong>No substitutions</strong>";
                                    if (strtotime($date_list[7][0]['date_end']) < strtotime($edition_data['edition_date'])) {
                                        echo " after " . fdateEntries($date_list[7][0]['date_end'], true, true);
                                    }
                                    echo "</li>";
                                }

                                // Up/Downgrades
                                if ($edition_data['edition_entry_nodowngrade']) {
                                    echo "<li><strong>No up- or downgrades will be entertained</strong>";
                                    if (strtotime($date_list[8][0]['date_end']) < strtotime($edition_data['edition_date'])) {
                                        echo " after " . fdateEntries($date_list[8][0]['date_end'], true, true);
                                    }
                                    echo "</li>";
                                }
                                ?>
                            </ul>
                        <?php
                            // always show what is in the box
                            if (strlen($edition_data['edition_entry_detail']) > 10) {
                                echo $edition_data['edition_entry_detail'];
                            }
                        }
                        ?>
                        <div class="row m-t-30">
                            <div class="col-lg-12">
                                <?php
                                if (isset($tshirt['edition'])) {
                                ?>
                                    <a href="<?= $tshirt['edition']['url']; ?>" class="btn btn-light">
                                        <i class="fa fa-<?= $tshirt['edition']['icon']; ?>"></i> <?= $tshirt['edition']['text']; ?></a>

                                <?php
                                }
                                ?>
                                <ul>
                                    <?php
                                    // TSHIRT
                                    if ($edition_data['edition_tshirt_amount'] > 0) {
                                        if (!empty($edition_data['edition_tshirt_text'])) {
                                            echo "<li>T-Shirt <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong>: " . $edition_data['edition_tshirt_text'] . "</li>";
                                        } else {
                                            echo "<li>An event <strong>T-Shirt</strong> is available for purchase as part of the entry process for <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong></li>";
                                        }
                                    } elseif (!empty($edition_data['edition_tshirt_text'])) {
                                        echo "<li><b>T-Shirt</b>:  " . $edition_data['edition_tshirt_text'] . "</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <?php
                        if (!in_array(3, $edition_data['regtype_list'])) {
                        ?>
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <ul>
                                        <?php
                                        // OTD Reg
                                        if (isset($edition_data['regtype_list'][1])) {
                                            echo "<li>Registration & number collection will take place <b>on the day</b> from <b>" .
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
                                    if (strlen($edition_data['edition_reg_detail']) > 15) {
                                        echo $edition_data['edition_reg_detail'];
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="row m-t-30">
                            <div class="col-lg-12">
                                <?php
                                if (isset($flyer['edition'])) {
                                ?>
                                    <a href="<?= $flyer['edition']['url']; ?>" class="btn btn-default">
                                        <i class="fa fa-<?= $flyer['edition']['icon']; ?>"></i> <?= $flyer['edition']['text']; ?></a>

                                <?php
                                }
                                if (isset($entry_form['edition'])) {
                                ?>
                                    <a href="<?= $entry_form['edition']['url']; ?>" class="btn btn-light">
                                        <i class="fa fa-<?= $entry_form['edition']['icon']; ?>"></i> <?= $entry_form['edition']['text']; ?></a>

                                <?php
                                }
                                // website link
                                if (isset($url_list[1])) {
                                ?>
                                    <a href="<?= $url_list['1'][0]['url_name']; ?>" class="btn btn-light">
                                        <i class="fa fa-link"></i> Race Website</a>

                                <?php
                                }
                                // press release
                                if (isset($file_list[10])) {
                                ?>
                                    <a href="<?= base_url("file/edition/" . $slug . "/press release/" . $this->data_to_views['file_list'][10][0]['file_name']); ?>" class="btn btn-light">
                                        <i class="fa fa-file-pdf"></i> Official Press Release</a>

                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        

                    </div>

                    <!-- end: Product additional tabs -->
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Add yourself to the race mailing list";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->

<?php
//    wts($file_list);
?>