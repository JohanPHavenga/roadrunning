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
    <div class="col-md-6">
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
                $this->table->set_template(ftable('editions_unconfirmed_table'));
                foreach ($event_list_unconfirmed as $year => $year_list) {
                    foreach ($year_list as $month => $month_list) {
                        $cell = array('data' => "<b>$month</b>", 'colspan' => 3);
                        $this->table->add_row($cell);
                        foreach ($month_list as $day => $edition_list) {
                            foreach ($edition_list as $edition) {
                                $row['date'] = fdateDay($edition['edition_timestamp']);

                                $url = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                                if ($edition['edition_isfeatured']) {
                                    $row['name'] = "<strong>$url</strong>";
                                } else {
                                    $row['name'] = $url;
                                }


                                $email_link = '/admin/mailer/info_mail/' . $edition['edition_id'];
                                if ($edition['user_email']) {
                                    if ($edition['edition_info_email_sent']) {
                                        $row['info_email'] = '<a href="' . $email_link . '" class="btn btn-xs default" data-toggle="confirmation" data-original-title="Are you sure you want to resend the email? ' . $edition['user_email'] . '"><i class="fa fa-envelope-o"></i> Resend Email</a>';
                                    } else {
                                        $row['info_email'] = '<a href="' . $email_link . '" class="btn btn-xs blue" data-toggle="confirmation" data-original-title="Confirm send email to organiser? ' . $edition['user_email'] . '"><i class="fa fa-envelope-o"></i> Send Email</a>';
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

    <div class="col-md-6">

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
                            $cell = array('data' => "<b>$month</b>", 'colspan' => 3);
                            $this->table->add_row($cell);
                            foreach ($month_list as $day => $edition_list) {
                                foreach ($edition_list as $edition) {
//                                if (!$edition['results_file']) {
                                    $row['date'] = fdateDay($edition['edition_timestamp']);
                                    $row['name'] = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                                    $this->table->add_row($row);
                                    unset($row);
//                                }
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
</div>

<?php
//wts($event_list_unconfirmed);
?>            
