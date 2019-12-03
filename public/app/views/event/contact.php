<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Contact Race Organisers</h2>
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
                        <h3 class="text-uppercase">Get In Touch</h3>
                        <p>Got a question regarding this race? Use the form below to contact the race organisers directly. You will receive a copy of the email in your inbox as well.</p>
                        
                        <div class="m-t-30">
                            <form class="contact_form" action="<?= $contact_url; ?>" role="form" method="post">
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
                                    ?>
                                </div>
                                <button class="btn" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send message</button>
                            </form>

                        </div>
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