<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Use the form below to send me information regarding a running event you would like listed on the site.<br>
                    I will do my best to get back to you as soon as I can so we can complete the listing and get it on the site asap.<p>
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
                    echo form_open("event/add", $attributes);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <?php
                            echo form_label('Event Name *', 'event_name');
                            echo form_input([
                                'name' => 'event_name',
                                'id' => 'event_name',
                                'value' => set_value('event_name'),
                                'class' => 'form-control required',
                                'placeholder' => 'Event Name',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-md-3">
                            <?php
                            echo form_label('Event Date *', 'event_date');
                            echo form_input([
                                'name' => 'event_date',
                                'id' => 'event_date',
                                'value' => set_value('event_date'),
                                'class' => 'form-control required',
                                'placeholder' => 'Date of the event',
                                'type' => 'date',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-md-2">
                            <?php
                            echo form_label('Start Time *', 'event_time');
                            echo form_input([
                                'name' => 'event_time',
                                'id' => 'event_time',
                                'type' => 'time',
                                'value' => set_value('event_time'),
                                'class' => 'form-control required',
                                'placeholder' => 'Time',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-9">
                            <?php
                            echo form_label('Event Address *', 'event_address');
                            echo form_input([
                                'name' => 'event_address',
                                'id' => 'event_address',
                                'value' => set_value('event_address'),
                                'class' => 'form-control required',
                                'placeholder' => 'Address of the finish line',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-md-3">
                            <?php
                            echo form_label('Town *', 'town_name');
                            echo form_input([
                                'name' => 'town_name',
                                'id' => 'town_name',
                                'value' => set_value('town_name'),
                                'class' => 'form-control required',
                                'placeholder' => 'Name of town',
                                'required' => '',
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <?php
                            echo form_label('Contact Name *', 'user_name');
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
                            echo form_label('Contact Surname *', 'user_surname');
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
                            echo form_label('Contact Email', 'user_email');
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
                        <div class="form-group col-md-9">
                            <?php
                            echo form_label('Entry URL', 'event_url');
                            echo form_input([
                                'name' => 'event_url',
                                'id' => 'event_url',
                                'value' => set_value('event_url'),
                                'class' => 'form-control required',
                                'placeholder' => 'URL for online entries (if available)',
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- add races -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <?php
                            echo form_label('Comment', 'user_comment');
                            echo form_textarea([
                                'name' => 'user_comment',
                                'id' => 'user_comment',
                                'value' => set_value('user_comment'),
                                'class' => 'form-control required',
                                'placeholder' => 'Add a comment (not required)',
                                'rows' => 5,
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="form-group g-recaptcha" data-sitekey="6LcxdoYUAAAAAADszn1zvLq3C9UFfwnafqzMWYoV"></div>
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-plus-circle"></i>&nbsp;Add listing',
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
<!-- end: Add listing -->