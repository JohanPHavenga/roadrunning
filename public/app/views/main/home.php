<section class="reservation-form-over no-padding">
    <div class="container">
        <form action="#" method="post">
            <div class="row reservation-form">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Race name</label>
                        <input type="text" placeholder="Not required" name="name" value="">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Where</label>
                        <select id="where" name="where">
                            <option value="">Everywhere </option>
                            <optgroup label="Provinces">
                                <option value="">Western Cape</option>
                                <option value="">Gauteng</option>
                                <option value="">KwaZulu-Natal</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Distance</label>
                        <select id="distance" name="distance">
                            <option value="">All </option>
                            <option value="">Fun Run</option>
                            <option value="">10km</option>
                            <option value="">15km</option>
                            <option value="">Half-Marathon</option>
                            <option value="">Marathon</option>
                            <option value="">Ultra Marathon</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Time filter</label>
                        <select id="time" name="time">
                            <option value="">None</option>
                            <option value="">This weekend</option>
                            <option value="">Next 30days</option>
                            <option value="">Next 3 months</option>
                            <option value="">Next 6 months</option>
                            <option value="">Past 6 months</option>
                        </select>
                    </div>
                </div>
                <!--                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>Round about</label>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input name="fromDate" class="form-control" type="text">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                <div class="col-lg-1">
                    <div class="form-group">
                        <button class="btn m-t-25">Search</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<section style="padding: 0;">
    <div class="container">
        <div class="heading-text heading-section text-center">
            <h2>FEATURED RACES</h2>
        </div>
        <div class="row">
            <div class="col-lg-12">
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
                                if (!array_key_exists(5, $edition['entrytype_list'])) {
                                    ?>
                                    <span class="race-badge">Entry <br>Open</span>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="race-details">
                                <p>
                                    <b>WHEN</b>: <?= fdateHumanFull($edition['edition_date'], true); ?> from <?= ftimeMil($edition['race_time_start']); ?><br>
                                    <b>WHERE</b>: <?= $edition['edition_address'] . ", " . $edition['town_name']; ?><br>
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
        <div class="row">
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
            <div class="col-lg-7">
                <div class="heading-text heading-section">
                    <h2>Upcoming</h2>
                    <span class="lead">Ready to run?</span>
                </div>
                <div class="tabs p-r-20">
                    <ul class="nav nav-tabs nav-justified text-left" id="myTab">
                        <?php
                        foreach ($upcoming_events as $year => $year_list) {
                            foreach ($year_list as $month => $month_list) {
                                foreach ($month_list as $day => $edition_list) {
                                    $unix = strtotime($day . " " . $month . " " . $year);
                                    $act = "";
                                    if ($day === array_key_first($month_list)) {
                                        $act = "active";
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
<!--                        <li class="nav-item"><a href="#day1" class="nav-link active" data-toggle="tab" aria-selected="true"><strong>Saturday</strong> <br>02.11.2019</a></li>
<li class="nav-item"><a href="#day2" class="nav-link" data-toggle="tab"><strong>Sunday</strong> <br>03.11.2019</a></li>
<li class="nav-item"><a href="#day3" class="nav-link" data-toggle="tab"><strong>Wednesday</strong> <br>06.11.2019</a></li>
<li class="nav-item"><a href="#day4" class="nav-link" data-toggle="tab"><strong>Saturday</strong> <br>09.11.2019</a></li>-->
                    </ul>
                    <div class="tab-content">
                        <?php
                        foreach ($upcoming_events as $year => $year_list) {
                            foreach ($year_list as $month => $month_list) {
                                foreach ($month_list as $day => $edition_list) {
                                    $act = "";
                                    if ($day === array_key_first($month_list)) {
                                        $act = "active";
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
                                                    <p class="m-b-0"><?= $edition['town_name']; ?><br><?= implode(" | ", $edition['race_distance_arr']); ?></p>
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
                        <!--                        <div id="day1" class="tab-pane fade show active">
                                                    <a href="">
                                                        <div class="p-10 border-bottom">
                                                            <span><i class="far fa-clock"></i> 07:15 - 10:45</span>
                                                            <h5>Century City Express</h5>
                                                            <p class="m-b-0">Century City, WC<br>21 / 10 / 5 km</p>
                                                        </div>
                                                    </a>
                                                    <a href="">
                                                        <div class="p-10 border-bottom">
                                                            <span><i class="far fa-clock"></i> 11:00 - 11:45</span>
                                                            <h5>Alea Grande</h5>
                                                            <p class="m-b-0">Et harum quidem rerum facilis est et expedita distinctio. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                        </div>
                                                    </a>
                                                    <a href="">
                                                        <div class="p-10 border-bottom">
                                                            <span><i class="far fa-clock"></i> 08:00 - 08:45</span>
                                                            <h5>John Smith</h5>
                                                            <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="day2" class="tab-pane fade">
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 11:00 - 11:45</span>
                                                        <h5>Alea Grande</h5>
                                                        <p class="m-b-0">Et harum quidem rerum facilis est et expedita distinctio. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 08:00 - 08:45</span>
                                                        <h5>John Smith</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 09:00 - 10:45</span>
                                                        <h5>Juna Doe</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
                                                    </div>
                                                </div>
                                                <div id="day3" class="tab-pane fade">
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 08:00 - 08:45</span>
                                                        <h5>John Smith</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 11:00 - 11:45</span>
                                                        <h5>Alea Grande</h5>
                                                        <p class="m-b-0">Et harum quidem rerum facilis est et expedita distinctio. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 08:00 - 08:45</span>
                                                        <h5>John Smith</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 09:00 - 10:45</span>
                                                        <h5>Juna Doe</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
                                                    </div>
                                                </div>
                                                <div id="day4" class="tab-pane fade">
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 11:00 - 11:45</span>
                                                        <h5>Alea Grande</h5>
                                                        <p class="m-b-0">Et harum quidem rerum facilis est et expedita distinctio. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 08:00 - 08:45</span>
                                                        <h5>John Smith</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                                                    </div>
                                                    <div class="p-10 border-bottom">
                                                        <span><i class="far fa-clock"></i> 09:00 - 10:45</span>
                                                        <h5>Juna Doe</h5>
                                                        <p class="m-b-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
                                                    </div>
                                                </div>-->
                    </div>
                </div>
                <div class="text-center  p-t-20 p-b-20">
                    <a href="#" class="btn btn-colored">Show more</a>
                </div>
            </div>
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
                    Join by our growing <span>Newsletter</span> subscriber base
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
                    <input class="form-control" id="email_sub" name="user_email" placeholder="info@example.com" type="email" required="" value="<?= $rr_cookie['sub_email']; ?>">
                </div>
                <?php
                $data = array(
                    'type' => 'submit',
                    'content' => 'Subscribe',
                    'class' => 'btn m-t-20',
                );
                echo form_button($data);
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
            <a href="#" class="btn btn-lg btn-colored">Show me How</a>
        </div>
        <!--                    <div class="row">
                                <div class="col-lg-4">
        
                                </div>
                            </div>-->
    </div>
</section>


<section id="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>HOME</h2>
                <p>Welcome, to the real world</p>

                <h3>Featured Events incl Races</h3>
                <?php
                foreach ($featured_events as $edition_id => $edition) {
                    echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
                }
                ?>
                <h3>Last Edited Events</h3>
                <?php
                foreach ($last_edited_events as $edition_id => $edition) {
                    echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
                }
                ?>
                <h3>Most popular events (month)</h3>
                <?php
                foreach ($history_sum_month as $edition_id => $edition) {
                    echo date("D j M", strtotime($edition['edition_date'])) . " - "
                    . "<a href='" . $edition['edition_url'] . "'>" . $edition['edition_name'] . "</a>"
                    . " - Visits: <b>" . $edition['historysum_countmonth'] . "</b>  ";
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php

//wts($featured_events);
//wts($featured_events_new);
//wts($last_edited_events);
//wts($most_visited_events);