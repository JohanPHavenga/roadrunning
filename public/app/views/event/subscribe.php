<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Receive race notifications</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">

                <!-- add box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <p>Would you like to receive a notification email when information is loaded for the race, or when online entries open? How about when results are loaded?
                            Then you are in luck. Insert you email address below and subscribe to the notification service for this race.</p>
                            <form class="form-inline" _lpchecked="1">
                                <div class="mx-lg-3 m-t-20">
                                    <h5>Enter your email:</h5>
                                </div>
                                <div class="form-group mx-sm-3 m-t-20">
                                    <label for="email_sub" class="sr-only">Email</label>
                                    <input class="form-control" id="email_sub" placeholder="info@example.com" type="email">
                                </div>
                                <button type="submit" class="btn m-t-20">Subscribe</button>
                            </form>

                    </div>

                    <!-- end: Product additional tabs -->
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Receive race notification";
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