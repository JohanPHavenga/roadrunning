<div class="c-layout-page">

    <?= $title_bar; ?>
    <?php
    if ($this->session->flashdata('alert')) {
        $alert_msg = $this->session->flashdata('alert');
        if (!($this->session->flashdata('status'))) {
            $status = 'warning';
        } else {
            $status = $this->session->flashdata('status');
        }
        echo "<div class='alert alert-$status' role='alert' style='margin-bottom:0'><div class='container'>$alert_msg</div></div>";
    } else {
        echo $notice;
    }
    ?>

    <div class="c-content-box c-size-sm c-bg-img-top c-no-padding c-pos-relative">
        <div class="container">
            <div class="c-content-contact-1 c-opt-1">
                <div class="row" data-auto-height=".c-height" style="min-height: 628px;">
                    <div class="col-sm-7 c-desktop"></div>
                    <div class="col-sm-5" style="padding: 0;">
                        <div class="c-body" style="padding: 45px 45px 35px 40px;">
                            <div class="c-section">
                                <h3><?= $event_detail['edition_name_clean']; ?></h3>
                            </div>
                            <div class="c-section">
                                <div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">General</div>
                                <p>
                                    <b><?= date("d F Y", strtotime($event_detail['edition_date'])); ?></b><br>
                                    <?= $event_detail['edition_address_end']; ?><br>
                                    <?= $event_detail['town_name']; ?><br>
                                    <?= $event_detail['summary']['race_time_start']; ?> Race<br>
                                </p>
                            </div>
                            <div class="c-section">
                                <div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">Entries</div>
                                <p>                                    
                                    <?php
                                    // show link in summary info
                                    if ($event_detail['calc_edition_urls']) {
                                        $url_segments = parse_url($event_detail['calc_edition_urls'][0]['url']);
                                        echo "<a href='" . $event_detail['calc_edition_urls'][0]['url'] . "' target='_blank'>" . $url_segments['host'] . "</a><br>";
                                    }

                                    // show email
                                    if (isset($event_detail['user_email'])) {
                                        $contact_email = $event_detail['user_email'];
                                    } else {
                                        $contact_email = "info@roadrunning.co.za";
                                    }
                                    ?>
                                    <a href="mailto:<?= $contact_email; ?>?subject=Enquiry regarding <?= $event_detail['event_name']; ?> from roadrunning.co.za"><?= $contact_email; ?></a>
                                </p>
                            </div>
                            <div class="c-section">
                                <div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">More</div>
                                <br/>
                                <ul class="c-content-iconlist-1 c-theme">
                                    <li>
                                        <a href="/event/ics/<?= $event_detail['edition_id']; ?>" title="Outlook Calender Reminder Download">
                                            <i class="fa fa-calendar-plus-o"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $event_detail['google_cal_url'] ?>" target="_blank" title="Google Calender Reminder">
                                            <i class="fa fa-google"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="leaflet_map_bg" class="c-content-contact-1-gmap" style="height: 630px; z-index:0;"></div>
        <!--<div id="gmapbg" class="c-content-contact-1-gmap" style="height: 630px;"></div>-->
    </div>




