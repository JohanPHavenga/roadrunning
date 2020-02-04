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

                <!-- ad box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <?php
                        // LANDSCAPE ADS WIDGET
                        $this->load->view('widgets/horizontal_ad');
                        ?>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <p>Would you like to receive a notification email when information is loaded for the race, or when online <b>entries open</b>?
                            How about when <b>results are loaded</b>?</p>
                        <p>
                            Then you are in luck. Insert you email address below to be added mailing list for this race.</p>
                        <?php
                        $attributes = array('class' => 'form-inline', 'role' => 'form');
                        echo form_open($subscribe_url, $attributes);
                        ?>
                        <div class="mx-lg-3 m-t-20">
                            <h5>Email:</h5>
                        </div>
                        <div class="form-group mx-sm-3 m-t-20">
                            <label for="email_sub" class="sr-only">Email</label>
                            <input class="form-control" id="email_sub" name="user_email" placeholder="info@example.com" type="email" required="" value="<?=$rr_cookie['sub_email'];?>">
                        </div>
                        <?php
                        $data = array(
                            'type' => 'submit',
                            'content' => 'Add to mailing list',
                            'class' => 'btn m-t-20',
                        );
                        echo form_button($data);
                        echo form_close();
                        ?>

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
<!-- end: Shop products -->