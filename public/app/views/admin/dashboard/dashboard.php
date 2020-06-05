<div class="row">
    <?php
    if (isset($dashboard_stats_list)) {
        foreach ($dashboard_stats_list as $stat) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-<?= $stat['font-color']; ?>">
                                <span data-counter="counterup" data-value="" class="counter"><?= number_format($stat['number'], 0, ".", " "); ?></span>
                            </h3>
                            <small><?= strtoupper($stat['text']); ?></small>
                        </div>
                        <div class="icon">
                            <i class="<?= $stat['icon']; ?>"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <a href="<?= $stat['uri']; ?>" class="btn btn-default btn-xs <?= $stat['font-color']; ?>">View</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>


<div class="row">
    <div class="col-md-7">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-rocket"></i>
                    <span class="bold"> Events with unconfirmed data</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                // create table
                $this->table->set_template(ftable('unconfirmed_date_table'));
                $this->table->set_heading(["Date","Race","ASA","Time","Mail?"]);
                foreach ($event_list_unconfirmed as $year => $year_list) {
                    foreach ($year_list as $month => $month_list) {
//                        $cell = array('data' => "<b>$month</b>", 'colspan' => 5);
//                        $this->table->add_row($cell);
                        foreach ($month_list as $day => $edition_list) {
                            foreach ($edition_list as $edition) {
                                // column 1
                                $row['date'] = fdateHumanShort($edition['edition_date']);

                                // column 2
                                $url = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                                if ($edition['edition_isfeatured']) {
                                    $row['name'] = "<strong>$url</strong>";
                                } else {
                                    $row['name'] = $url;
                                }

                                // column 3
                                $row['asa_member'] = "<span title='" . $edition['asa_member_name'] . "'>" . $edition['asa_member_abbr'] . "</span>";
                                // column 4
                                $row['timingprovider'] = $edition['timingprovider_abbr'];

                                // column 5
                                $email_link = '/admin/mailer/info_mail/' . $edition['edition_id'];
                                if ($edition['user_email']) {
                                    if ($edition['edition_info_email_sent']) {
                                        $row['info_email'] = '<a href="' . $email_link . '" class="btn btn-xs default" data-toggle="confirmation" data-original-title="Are you sure you want to resend the email? ' . $edition['user_email'] . '"><i class="fa fa-envelope-o"></i> Resend</a>';
                                    } else {
                                        $row['info_email'] = '<a href="' . $email_link . '" class="btn btn-xs blue" data-toggle="confirmation" data-original-title="Confirm send email to organiser? ' . $edition['user_email'] . '"><i class="fa fa-envelope-o"></i> Send</a>';
                                    }
                                } else {
                                    $row['info_email'] = '<a class="btn btn-xs red" title="Add contact to event to send email"><i class="fa fa-user"></i> No contact</a>';
                                }
                                $this->table->add_row($row);
                                unset($row);
                            }
                        }
                    }
                }
                echo $this->table->generate();
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-5">

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-rocket"></i>
                    <span class="bold"> Events with no results</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                // create table
                $this->table->set_template(ftable('editions_noresults_table'));
                if ($event_list_noresults) {
                    foreach ($event_list_noresults as $year => $year_list) {
                        foreach ($year_list as $month => $month_list) {
                            $cell = array('data' => "<b>$month</b>", 'colspan' => 4);
                            $this->table->add_row($cell);
                            foreach ($month_list as $day => $edition_list) {
                                foreach ($edition_list as $edition) {
                                    // column 1
                                    $row['date'] = fdateDay($edition['edition_date']);
                                    // column 2
                                    $link = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                                    if ($edition['edition_isfeatured']) {
                                        $row['name'] = "<b>$link</b>";
                                    } else {
                                        $row['name'] = $link;
                                    }
                                    // column 3
                                    $row['asa_member'] = "<span title='" . $edition['asa_member_name'] . "'>" . $edition['asa_member_abbr'] . "</span>";
                                    // column 4
                                    $row['timingprovider'] = $edition['timingprovider_abbr'];

                                    // generate row
                                    $this->table->add_row($row);
                                    unset($row);
                                }
                            }
                        }
                    }
                }
                echo $this->table->generate();
                ?>
            </div>
        </div>

    </div>
    <?php
//    wts($event_list_noresults);
    ?>          
</div>

