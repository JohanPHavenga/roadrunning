<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Got a question. Check if it is asked and answered below, else <a href="<?= base_url("contact"); ?>">ask me</a> something.<p>
                <div class="accordion accordion-shadow">
                    <div class="ac-item">
                        <h5 class="ac-title">   
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            There are 2 numbers in my race pack. Which goes where?
                        </h5>
                        <div class="ac-content" style="display: none;">
                            <p>The race number goes on the front, the temporary license number goes on the back. Generally the Race number will be the one that is better quality, and a lower number than the license number</p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">   
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            What do I do with the indemnity declaration slip?
                        </h5>
                        <div class="ac-content" style="display: none;">
                            <p>You need to complete it and hand it back to the organisers at a registration point. If you don't know where this is, 
                                complete the slip and fold it in under the number</p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">   
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            What does it mean to be a "licensed athlete"?
                        </h5>
                        <div class="ac-content" style="display: none;">
                            <p>
                                Races that are ran under the rules and regulations of the ASA (10km+) requires you to have a license. You can either join 
                                a running club and purchase a permanent license, or buy a temporarily license for the race you want to enter. 
                                If you run more that 3x 10km+ races a year, it starts making financial sense to get a permanent number.
                            </p>
                        </div>
                    </div>
                    <div class="ac-item">
                        <h5 class="ac-title">   
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            Are we the organisers of all these races?
                        </h5>
                        <div class="ac-content" style="display: none;">
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
                        <div class="ac-content" style="display: none;">
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