<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <?php
                // CRUMBS WIDGET
                $this->load->view('widgets/crumbs');
                ?>
                <h3 class="text-uppercase">Edit Profile</h3>
                <p>Change your profile data below</p>
                <?php
                if (validation_errors()) {
                    echo "<div class='alert alert-danger' role='alert'><strong><i class='fa fa-exclamation-circle'></i> Validation Error</strong>";
                    echo validation_errors();
                    echo "</div>";
                }
                ?>
                <div class="m-t-30">
                    <?php
                    $attributes = array('class' => 'contact_form', 'role' => 'form');
                    echo form_open($form_url, $attributes);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <?php
                            echo form_label('Name *', 'user_name');
                            echo form_input([
                                'name' => 'user_name',
                                'id' => 'user_name',
                                'value' => set_value('user_name', $logged_in_user['user_name']),
                                'class' => 'form-control required',
                                'placeholder' => 'Enter your Name',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-md-6">
                            <?php
                            echo form_label('Surname *', 'user_surname');
                            echo form_input([
                                'name' => 'user_surname',
                                'id' => 'user_surname',
                                'value' => set_value('user_surname',$logged_in_user['user_surname']),
                                'class' => 'form-control required',
                                'placeholder' => 'Enter your Surname',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-7">
                            <?php
                            echo form_label('Email *', 'user_email');
                            echo form_input([
                                'name' => 'user_email',
                                'id' => 'user_email',
                                'type' => 'email',
                                'value' => set_value('user_email',$logged_in_user['user_email']),
                                'class' => 'form-control required',
                                'placeholder' => 'Enter your Email',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-md-4">
                            <?php
                            echo form_label('Contact Number', 'user_contact');
                            echo form_input([
                                'name' => 'user_contact',
                                'id' => 'user_contact',
                                'value' => set_value('user_contact',$logged_in_user['user_contact']),
                                'class' => 'form-control',
                                'placeholder' => 'Contact Number',
                            ]);
                            ?>
                        </div>
                    </div>
                    <!--<div class="form-group g-recaptcha" data-sitekey="6LcxdoYUAAAAAADszn1zvLq3C9UFfwnafqzMWYoV"></div>-->
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-save"></i>&nbsp;Save',
                        'class' => 'btn',
                    );
                    echo form_button($data);
                    
                    echo "<a href='".base_url("user/profile")."' class='btn btn-light'>Cancel</a>";
                    echo form_close();
                    ?>
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
<!-- end: Contact products -->
