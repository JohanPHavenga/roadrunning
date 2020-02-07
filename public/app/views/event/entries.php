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
                                    isset($edition_data['entrytype_list'][4]) &&
                                    isset($url_list[5]) &&
                                    isset($date_list[3][0]['date_start']) &&
                                    (strtotime($date_list[3][0]['date_start']) < time()) &&
                                    (strtotime($date_list[3][0]['date_end']) > time())
                            ) {
                                ?>
                                <p>
                                    <a href="<?= $url_list[5][0]['url_name']; ?>" class="btn btn-default btn-creative btn-icon-holder btn-shadow btn-light-hover">Enter online
                                        <i class="fa fa-arrow-right"></i></a>
                                </p>
                                <?php
                            }
                            ?>
                            <ul>
                                <?php
                                // Online entries
                                // check vir online entries
                                if (isset($edition_data['entrytype_list'][4])) {
                                    // check if open date has past or not
                                    if (strtotime($date_list[3][0]['date_start']) < time()) {
                                        $text = "opened";
                                    } else {
                                        $text = "will open";
                                    }
                                    // if open date is not equal to the race date
                                    if (strtotime($date_list[3][0]['date_start']) != strtotime($edition_data['edition_date'])) {
                                        echo "<li>Online entries $text on <b style='color: red;'>" . fdateEntries($date_list[3][0]['date_start'], true) . " </b>";
                                    } else {
                                        echo "<li>Online entries will <b>open soon</b>";
                                    }
                                    // check vir closing date
                                    if (strtotime($date_list[3][0]['date_end']) != strtotime($edition_data['edition_date'])) {
                                        $d = '';
                                        // check if already closed
                                        if (strtotime($date_list[3][0]['date_end']) < time()) {
                                            $d = "d";
                                        }
                                        echo "<li>Online entries close$d on <b>" . fdateEntries($date_list[3][0]['date_end']) . "</b></li>";
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
                                    echo "<li class='text-danger'>No entrires avaialble on race day</li>";
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
                                    if
                                    (
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
                                if ($edition_data['edition_entry_funrun_otd']) {
                                    foreach ($race_list as $race) {
                                        if ($race['race_distance'] < 10) {
                                            echo "<li>Entries for the " . $race['race_name'] . " will be taken on the day</li>";
                                        }
                                    }
                                }

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

                                // TSHIRT FEES
                                if ($edition_data['edition_tshirt_amount'] > 0) {
                                    echo "<li>An event <strong>T-Shirt</strong> is available for purchase as part of the entry process for <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong></li>";
                                    if (!empty($edition_data['edition_tshirt_text'])) {
                                        echo "<li>T-Shirt <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong>: " . $edition_data['edition_tshirt_text'] . "</li>";
                                    }
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