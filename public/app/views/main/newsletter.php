<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <?php
                if ($this->session->flashdata('status')!='success') { 
                ?>
                <div class="row">
                    <div class="m-b-40 col-lg-12">
                        <p>Please enter your email address below to subscribe to our monthly newsletter</p>
                        <?php
                        $subscribe_url = base_url("user/subscribe/newsletter");
                        $attributes = array('class' => 'form-inline', 'role' => 'form');
                        echo form_open($subscribe_url, $attributes);
                        ?>
                        <div class="mx-lg-3 m-t-20">
                            <h5>Email:</h5>
                        </div>
                        <div class="form-group mx-sm-3 m-t-20">
                            <label for="email_sub" class="sr-only">Email</label>
                            <input class="form-control" id="email_sub" name="user_email" placeholder="info@example.com" type="email" required="" value="<?= $rr_cookie['sub_email']; ?>">
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
                <?php
                }
                ?>
                <div class="row">
                    <div class="col-lg-12">

                        <h4 class="text-uppercase">Why subscribe?</h4>
                        <p>
                            If you subscribe to our newsletter you will receive a <b>monthly</b> update of results loaded for the events that was, 
                            plus a list of upcoming events over the next two months.</p>
                        <p>
                            It is still a <b>work in progress</b>, so please, if there is any suggestions out there to make it better, hit the 
                            reply button and give me a piece of your mind. </p>
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