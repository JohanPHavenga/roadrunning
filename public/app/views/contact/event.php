<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
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
                    echo form_open($contact_url, $attributes);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-3">
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
                        <div class="form-group col-md-4">
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
                        <div class="form-group col-md-5">
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
                    <div class="form-group">
                        <?php
                        echo form_label('Query', 'user_message');
                        echo form_textarea([
                            'name' => 'user_message',
                            'id' => 'user_message',
                            'value' => set_value('user_message'),
                            'class' => 'form-control required',
                            'placeholder' => 'Enter your Query',
                            'required' => '',
                            'rows' => 5,
                        ]);
                        // hidden field for the slug
                        echo form_input([
                            'name' => 'edition_name',
                            'type' => 'hidden',
                            'value' => $edition_data['edition_name'],
                        ]);
                        ?>
                    </div>
                    <div class="form-group g-recaptcha" data-sitekey="6LcxdoYUAAAAAADszn1zvLq3C9UFfwnafqzMWYoV"></div>
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-paper-plane"></i>&nbsp;Send message',
                        'class' => 'btn',
                    );

                    echo form_button($data);
                    echo "<a href='$cancel_url' class='btn btn-light'><i class='fa fa-minus-circle'></i>&nbsp;Cancel</a>";
                    $data = array(
                        'id' => 'form-clear',
                        'type' => 'reset',
                        'content' => '<i class="fa fa-eraser"></i>&nbsp;Clear',
                        'class' => 'btn btn-light',
                    );
                    echo form_button($data);
                    echo form_close();
                    ?>
                </div>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
//                $data_to_widget['title'] = "Receive race notification";
//                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Contact products -->
