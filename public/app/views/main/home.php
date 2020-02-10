<?php
$this->load->view('templates/search_form');
?>

<section style="padding-top: 30px;">
    <div class="container">
        <div class="heading-text heading-section text-center">
            <h2>FEATURED RACES</h2>
        </div>
        <div class="row body">
            <div class="col-lg-12">
                <?php
                if ($featured_events) {
                    ?>
                    <div class="races carousel" data-items="3" data-margin="20" data-dots="false">
                        <?php
                        foreach ($featured_events as $edition_id => $edition) {
                            ?>
                            <div class="race">
                                <div class="race-image">
                                    <a href='<?= $edition['edition_url']; ?>'>
                                        <img src="<?= $edition['img_url']; ?>" alt="<?= $edition['edition_name']; ?>"></a>
                                    <div class="race-title"><?= $edition['edition_name']; ?></div>
                                    <?php
                                    // soek vir online entries
                                    if ((array_key_exists(4, $edition['entrytype_list'])) && (array_key_exists(3, $edition['date_list']))) {
                                        if (strtotime($edition['date_list'][3][0]['date_end']) > time()) {
                                            ?>
                                            <span class="race-badge">Entry <br>Open</span>
                                            <?php
                                        } else {
                                            ?>
                                            <span class="race-badge">Entry <br>Close</span>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="race-details">
                                    <p>
                                        <b>WHEN</b>: <?= fdateHumanFull($edition['edition_date'], true); ?> from <?= ftimeMil($edition['race_time_start']); ?><br>
                                        <b>WHERE</b>: <?= $edition['edition_address'] . ", " . $edition['town_name'] . ", " . $edition['province_abbr']; ?><br>
                                        <b>DISTANCES</b>: <?= implode(" | ", $edition['race_distance_arr']); ?><br>
                                    </p>
                                    <div class="float-left">
                                        <a href="<?= $edition['edition_url']; ?>" class="btn btn-colored">View</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <p>There are no featured races in the <a href="<?= base_url("region/switch"); ?>">regions you have selected</a>. We are in the process of loading more races across the country. Please check again later.
                        <?php
                    }
                    ?>
            </div>
        </div>
    </div>
</section>

<section class="background-grey">
    <div class="container">
        <div class="heading-text heading-section text-center">
            <h2>WHAT WE DO</h2>
            <span class="lead">Our mission is to list running events in a standard way, on a modern multi-platform capable website. 
                Giving the user an easy way to find and compare races and allow for easy entry via the various 3rd party entry portals.</span>
        </div>
        <div class="row body">
            <div class="col-lg-4">
                <div>
                    <h4>Race listings</h4>
                    <p>We source information from various places and formulate the information into a standard template.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <h4>Publish Results</h4>
                    <p>In the same vain we collect result sets from various sources to make your results easy to find.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <h4>Race Notifications</h4>
                    <p>Receive updates via email when entries open, info change and when results are posted.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <h4>Contact Organisers</h4>
                    <p>Got a question for a particular race? Contact the organisers through the site to get answers.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <h4>FAQ</h4>
                    <p>I try and answer some general running questions in the FAQ section, but if you can't find it, <a href='/contact'>ask me</a>.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <h4>Continuous Improvements</h4>
                    <p>I am always tweaking and prodding at the site. Expect new functionality to pop-up over time.</p>
                </div>
            </div>

        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <?php
            if ($upcoming_events) {
                ?>
                <div class="col-lg-7">
                    <div class="heading-text heading-section">
                        <h2>Upcoming</h2>
                        <span class="lead">Ready to run?</span>
                    </div>
                    <div class="tabs p-r-20">
                        <ul class="nav nav-tabs nav-justified text-left" id="myTab">
                            <?php
                            $lock = false;
                            foreach ($upcoming_events as $year => $year_list) {
                                foreach ($year_list as $month => $month_list) {
                                    foreach ($month_list as $day => $edition_list) {
                                        $unix = strtotime($day . " " . $month . " " . $year);
                                        $act = "";
                                        if (($day === array_key_first($month_list)) && (!$lock)) {
                                            $act = "active";
                                            $lock = true;
                                        }
                                        ?>
                                        <li class="nav-item">
                                            <a href="#day<?= $day; ?>" class="nav-link <?= $act; ?>" data-toggle="tab" aria-selected="true">
                                                <strong><?= date("l", $unix); ?></strong> <br><?= date("Y.m.d", $unix); ?></a>
                                        </li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                        <div class="tab-content">
                            <?php
                            $lock = false;
                            foreach ($upcoming_events as $year => $year_list) {
                                foreach ($year_list as $month => $month_list) {
                                    foreach ($month_list as $day => $edition_list) {
                                        $act = "";
                                        if (($day === array_key_first($month_list)) && (!$lock)) {
                                            $act = "active";
                                            $lock = true;
                                        }
                                        ?>
                                        <div id="day<?= $day; ?>" class="tab-pane fade show <?= $act; ?>">
                                            <?php
                                            foreach ($edition_list as $edition_id => $edition) {
                                                ?>
                                                <a href="<?= $edition['edition_url']; ?>">
                                                    <div class="p-10 border-bottom">
                                                        <span>
                                                            <i class="far fa-clock"></i> <?= ftimeMil($edition['race_time_start']); ?>
                                                            <?php
                                                            if ($edition['edition_info_prizegizing'] != "00:00:00") {
                                                                echo " - " . ftimeMil($edition['edition_info_prizegizing']);
                                                            }
                                                            ?>
                                                        </span>
                                                        <h5><?= $edition['edition_name']; ?></h5>
                                                        <p class="m-b-0"><?= $edition['town_name'] . ", " . $edition['province_abbr']; ?><br><?= implode(" | ", $edition['race_distance_arr']); ?></p>
                                                    </div>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="text-center  p-t-20 p-b-20">
                        <a href="<?= base_url('race/upcoming'); ?>" class="btn btn-colored">Show more</a>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="col-lg-5">
                <div class="heading-text heading-section">
                    <h2>Contact Us</h2>
                    <span class="lead">Get in touch.</span>
                </div>
                <?php
                $attributes = array('class' => 'contact-form', 'role' => 'form');
                echo form_open(base_url("contact"), $attributes);
                ?>
                <div class="row">
                    <div class="form-group col-md-5">
                        <?php
                        echo form_label('Name *', 'user_name');
                        echo form_input([
                            'name' => 'user_name',
                            'id' => 'user_name',
                            'value' => set_value('user_name'),
                            'class' => 'form-control required',
                            'placeholder' => 'Enter your Name',
                            'required' => '',
                        ]);
                        ?>
                    </div>
                    <div class="form-group col-md-7">
                        <?php
                        echo form_label('Surname *', 'user_surname');
                        echo form_input([
                            'name' => 'user_surname',
                            'id' => 'user_surname',
                            'value' => set_value('user_surname'),
                            'class' => 'form-control required',
                            'placeholder' => 'Enter your Surname',
                            'required' => '',
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <?php
                        echo form_label('Email', 'user_email');
                        echo form_input([
                            'name' => 'user_email',
                            'id' => 'user_email',
                            'type' => 'email',
                            'value' => set_value('user_email'),
                            'class' => 'form-control required',
                            'placeholder' => 'Enter your Email',
                            'required' => '',
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <?php
                        echo form_label('Message', 'user_message');
                        echo form_textarea([
                            'name' => 'user_message',
                            'id' => 'user_message',
                            'value' => set_value('user_message'),
                            'class' => 'form-control required',
                            'placeholder' => 'Enter your Comment',
                            'required' => '',
                            'rows' => 5,
                        ]);
                        ?>
                    </div>
                </div>
                <?php
                $data = array(
                    'id' => 'form-submit',
                    'type' => 'submit',
                    'content' => '<i class="fa fa-paper-plane"></i>&nbsp;Send',
                    'class' => 'btn',
                );
                echo form_button($data);
                $data = array(
                    'id' => 'form-clear',
                    'type' => 'reset',
                    'content' => '<i class="fa fa-eraser"></i>&nbsp;Clear',
                    'class' => 'btn btn-light',
                );
                echo form_button($data);
                echo form_close();
                ?>

                <!--                <form class="widget-contact-form" action="include/contact-form.php" role="form" method="post">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name" placeholder="Enter your Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email" placeholder="Enter your Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea name="widget-contact-form-message" rows="7" class="form-control required" placeholder="Enter your Message"></textarea>
                                    </div>
                                    <div class="form-group text-center">
                                        <button class="btn button-light" type="submit" id="form-submit">Send message</button>
                                    </div>
                                </form>-->
            </div>
        </div>
    </div>
</section>

<div class="call-to-action p-t-50 p-b-50 mb-0 call-to-action-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h3>
                    Join our growing <span>Newsletter</span> subscriber base
                </h3>
                <p>
                    You will be kept up to date of any general news as well as receive a monthly update of results loaded, 
                    plus a list of upcoming events over the next two months in your selected region(s).
                </p>
            </div>
            <div class="col-lg-4">
                <?php
                $subscribe_url = base_url("user/subscribe/newsletter");
                $attributes = array('class' => 'form-inline', 'role' => 'form');
                echo form_open($subscribe_url, $attributes);
                ?>
                <div class="form-group mx-sm-3 m-t-20">
                    <label for="email_sub" class="sr-only">Email</label>
                    <input class="form-control m-r-20 m-b-10" id="email_sub" name="user_email" placeholder="info@example.com" type="email" required="" value="<?= $rr_cookie['sub_email']; ?>">
                    <?php
                    $data = array(
                        'type' => 'submit',
                        'content' => 'Subscribe',
                        'class' => 'btn m-b-10',
                    );
                    echo form_button($data);
                    ?>

                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>

<section class="background">
    <div class="container">
        <div class="heading-text heading-section text-center">
            <h2>CHOOSE YOUR REGION</h2>
            <span class="lead">To customise the experience for you we have introduces regions. 
                Once set it will show only information from the region(s) you have selected.
                This will allow for a more customised, focused view for you regarding races in your area.
            </span>
        </div>
        <div class="text-center p-b-20">
            <a href="<?= base_url("region/switch"); ?>" class="btn btn-lg btn-colored">Show me How</a>
        </div>
        <!--                    <div class="row">
                                <div class="col-lg-4">
        
                                </div>
                            </div>-->
    </div>
</section>