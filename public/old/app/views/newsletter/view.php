<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
    <?= $title_bar; ?>

    <!-- BEGIN: CONTENT/CONTACT/FEEDBACK-1 -->
    <div class="c-content-box c-size-sm c-bg-white">
        <div class="container">
            <div class="c-content-feedback-1 c-option-1">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($this->session->flashdata('alert')) {
                            $alert_msg = $this->session->flashdata('alert');
                            if (!($this->session->flashdata('status'))) {
                                $status = 'warning';
                            } else {
                                $status = $this->session->flashdata('status');
                            }
                            echo "<div class='alert alert-$status' role='alert'>$alert_msg</div>";
                        }
                        // flash data here
                        // 
                        if (@_POST['button']=="subscribe-btn") {
                            if (validation_errors()) {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo validation_errors();
                                echo '</div>';
                                $show_text=false;
                            } else {
                                echo '<div class="alert alert-success" role="alert">';
                                echo "Thank you for <b>subscribing</b>! Expect an confirmation email sent to your inbox soon.";
                                echo '</div>';
                            }
                        }
//                        wts($_POST);
                        ?>
                    </div>
                </div>
                <div class="row"> 

                    <div class="col-md-7" id="contact">
                        <div class="c-content-media-1 c-bordered">

                            <div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">Subscribe Now</div>
                            <a href="#" class="c-title c-font-uppercase c-theme-on-hover c-font-bold">Newsletter Subscription</a>
                            <p class="c-font-lowercase">Please complete the form below to subscribe to our monthly newsletter</p>

                            <?php
                            echo form_open('newsletter');
                            ?>
                            <div class="form-group">
                                <label for="dname">First Name *</label>
                                <?php
                                echo form_input(
                                        [
                                            'id' => 'dname',
                                            'name' => 'dname',
                                            'value' => @$form_data['dname'],
                                            'placeholder' => 'John',
                                            'class' => 'form-control c-square c-theme input-lg'
                                        ]
                                );
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="dname">Last name *</label>
                                <?php
                                echo form_input(
                                        [
                                            'id' => 'dsurname',
                                            'name' => 'dsurname',
                                            'value' => @$form_data['dsurname'],
                                            'placeholder' => 'Smith',
                                            'class' => 'form-control c-square c-theme input-lg'
                                        ]
                                );
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="demail">Email address *</label>
                                <?php
                                echo form_input(
                                        [
                                            'id' => 'demail',
                                            'name' => 'demail',
                                            'type' => 'email',
                                            'value' => @$form_data['demail'],
                                            'placeholder' => 'name.surname@example.com',
                                            'class' => 'form-control c-square c-theme input-lg'
                                        ]
                                );
                                ?>
                            </div>
                            <div class="form-group g-recaptcha" data-sitekey="6LcxdoYUAAAAAADszn1zvLq3C9UFfwnafqzMWYoV"></div>
                            <?php
                            echo form_button(
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn c-theme-btn c-btn-uppercase btn-lg c-btn-bold c-btn-round',
                                        'content' => 'Subscribe',
                                        'name' => 'button',
                                        'value' => 'subscribe-btn'
                                    ]
                            );
                            echo form_hidden('dto', @$form_data['dto']);
                            echo form_hidden('dreturn_url', @$form_data['dreturn_url']);
                            echo form_close();
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="c-content-media-1 c-bordered">
                            <div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">Why Subscribe</div>
                            <a href="#" class="c-title c-font-uppercase c-theme-on-hover c-font-bold">What will I get?</a>
                            <p>If you subscribe to our newsletter you will receive a <b>monthly</b> update of results loaded for the events that was, 
                                plus a list of upcoming events over the next two months.</p>
                            <p>It will remain a <b>work in progress</b> so please, if there is any suggestions out there to make it better, 
                                hit the reply button and give me a piece of your mind.                            
                            </p>
                            <div class="btn-group">
                                <a class="btn c-theme-btn c-btn-uppercase btn-sm c-btn-bold c-margin-t-20" href="/contact">
                                    <i class="icon-bubble"></i>
                                    Give us a piece of your mind</a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- END: CONTENT/CONTACT/FEEDBACK-1 -->


    <!-- END: PAGE CONTENT -->
</div>
<!-- END: PAGE CONTAINER -->

<?php
//wts($form_data); 
?>
