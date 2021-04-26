<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Got a question. Check if it is asked and answered below, else <a href="<?= base_url("contact"); ?>">ask me</a> something.
                <p>
                <div class="accordion accordion-shadow">
                    <div class="ac-item">
                        <h5 class="ac-title">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            How to virtual races work?
                        </h5>
                        <div class="ac-content">
                            <p>They all work a little differently from one another, but the basics are mostly the same:<br>
                            You enter a race distance online. Then you run the distance you entered in the date range 
                            required on your own route.<br>
                            You time yourself and send proof of the run in to the organisers.<br>
                            You generally have a few days in which you need to run the race distance in one go.<br>
                            And that is it. Most have a medal they courier to you, but please check individual race 
                            pages for more information.</p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            There are 2 numbers in my race pack. Which goes where?
                        </h5>
                        <div class="ac-content">
                            <p>The race number goes on the front, the temporary license number goes on the back. Generally the Race number will be the one that is better quality, and a lower number than the license number</p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            What do I do with the indemnity declaration slip?
                        </h5>
                        <div class="ac-content">
                            <p>You need to complete it and hand it back to the organisers at a registration point. If you don't know where this is,
                                complete the slip and fold it in under the number</p>
                        </div>
                    </div>
                    <div class="ac-item <?php if ($open == 'license') {
                                            echo 'ac-active';
                                        } ?>" id="license">
                        <h5 class="ac-title">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            What does it mean to be a "licensed athlete"?
                        </h5>
                        <div class="ac-content">
                            <p>
                                Races that are ran under the rules and regulations of the ASA (10km+) requires you to have a running license. You can either
                                buy a temporarily license for the race you want to enter, or join a running club and purchase a permanent license number for the year.
                                If you run more that 3x 10km+ races a year, it starts making financial sense to get a permanent number.
                            </p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            Are we the organisers of all these races?
                        </h5>
                        <div class="ac-content">
                            <p>
                                No we are not. In fact, we do not organise any events. All the events are organisers by different clubs and other event organisers.
                                We gather the information from everyone and post the events in a uniform manner.</p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            How do I enter races?
                        </h5>
                        <div class="ac-content">
                            <p>
                                It depends on the event, especially the size of the event. The smaller races will only do on-the-day entries. The really big
                                events will again only do online entries to ease the admin and make sure tracking chips are registered correctly.
                                Please make sure you read the information around entries and registration carefully to avoid disappointment.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">
                <?php
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: About -->