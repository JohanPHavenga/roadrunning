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
                            $show_text=true;
                            if ($_POST) {
                                if (!@$email_send) {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo validation_errors();
                                    echo '</div>';
                                    $show_text=false;
                                } else {
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo "Thank you for contacting us. <b>Your message has been sent successfully.</b><br>We will get back to you as soon as we can.";
                                    echo '</div>';
                                }
                            }
                            ?>
                    </div>
                </div>
                <div class="row"> 
                    <?php
                    if ($show_text) {
                    ?>
                    <div class="col-md-6">
                        <div class="c-container c-bg-green c-bg-img-bottom-right" style="background-image:url(img/feedback_box_1.png)">
                            <div class="c-content-title-1 c-inverse">
                                <h3 class="c-font-uppercase c-font-bold">Need to know more?</h3>
                                <div class="c-line-left"></div>
                                <p class="c-font-lowercase">
                                    Try visiting our FAQ page to learn more about Road Running races and the lingo around them. <br>
                                    It you fail to get an answer, please complete the form below and we will get back to you as soon as we can.</p>
                                <a href="/faq" class="btn btn-md c-btn-border-2x c-btn-white c-btn-uppercase c-btn-square c-btn-bold">Learn More</a>
                            </div>
                        </div>
                        <div class="c-container c-bg-grey-1 c-bg-img-bottom-right" style="background-image:url(img/feedback_box_2.png)">
                            <div class="c-content-title-1">
                                <h3 class="c-font-uppercase c-font-bold">Who am I contacting?</h3>
                                <div class="c-line-left"></div>
                                <p>We are RoadRunning.co.za pride ourselves on being the middleman.<br>
                                    We source information on running events all over and try our best to consolidate that information into a uniform way 
                                    so that information on the biggest event in presented in a similar fashion to the smallest event. </p>
                                <p>
                                    <strong>We are not the event organisers</strong>.
                                    We will do our best to answer whatever queries you might have, but ultimately if we can't help, we will put you in 
                                    touch with the organisers of the event in question.
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="col-md-6" id="contact">
                        <div class="c-contact">
                            <div class="c-content-title-1">
                                <h3 class="c-font-uppercase c-font-bold">Keep in touch</h3>
                                <div class="c-line-left"></div>
                                <p class="c-font-lowercase">Please send us a message using the form below.</p>
                            </div>
                            <?php
//                            if ($_POST) {
//                                if (!@$email_send) {
//                                    echo '<div class="alert alert-danger" role="alert">';
//                                    echo validation_errors();
//                                    echo '</div>';
//                                } else {
//                                    echo '<div class="alert alert-success" role="alert">';
//                                    echo "Thank you for contacting us. Your message has successfully been send.<br>We will get back to you as soon as we can.";
//                                    echo '</div>';
//                                }
//                            }
                            if ($this->session->flashdata('last_visited_event')) {
                                $form_data['devent'] = $this->session->flashdata('last_visited_event');
                            }
                            
                            echo form_open('contact'); 
                            ?>
                            <div class="form-group">
                                <label for="dname">Your name *</label>
                                <?php 
                                echo form_input(
                                        [
                                            'id' => 'dname', 
                                            'name' => 'dname', 
                                            'value' => @$form_data['dname'],
                                            'placeholder' => 'John Smith', 
                                            'class' => 'form-control c-square c-theme input-lg'
                                        ]
                                ); 
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="demail">Your email address *</label>
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
                            <div class="form-group">
                                <label for="devent">Select event (if applicable)</label>
                                <?php
                                echo form_dropdown(
                                        'devent', 
                                        $edition_dropdown, 
                                        @$form_data['devent'], 
                                        ["id" => "edition_id", "class" => "form-control c-square c-theme input-lg"]
                                );
                                ?>
                            </div>
                            <div class="form-group">                                    
                                <label for="dmsg">Comment *</label>
                                <?php 
                                echo form_textarea(
                                        [
                                            'id' => 'dmsg', 
                                            'name' => 'dmsg', 
                                            'value' => @$form_data['dmsg'], 
                                            'placeholder' => 'Write comment here ...', 
                                            'rows' => '8', 
                                            'class' => 'form-control c-square c-theme input-lg',
                                        ]
                                ); ?>
                            </div>
                            <div class="form-group g-recaptcha" data-sitekey="6LcxdoYUAAAAAADszn1zvLq3C9UFfwnafqzMWYoV"></div>
                                <?php 
                                echo form_button(
                                        [
                                            'type' => 'submit', 
                                            'class' => 'btn c-theme-btn c-btn-uppercase btn-lg c-btn-bold c-btn-round', 
                                            'content' => 'Submit'
                                        ]
                                ); 
                                echo form_hidden('dto',@$form_data['dto']); 
                                echo form_hidden('dreturn_url',@$form_data['dreturn_url']); 
                                echo form_close(); 
                            ?>
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
