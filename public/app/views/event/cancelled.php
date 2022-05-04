<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2><?= $edition_data['edition_name']; ?>
                    <span class="fa fa-<?= $status_notice['icon']; ?> text-<?= $status_notice['state']; ?>" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?= $status_notice['short_msg']; ?>"></span>
                </h2>
            </div>

        </div>
        <?php //$this->load->view('widgets/race_meta'); 
        ?>
        <div class="row m-t-40">
            <!-- Content-->
            <div class="content col-lg-9" style="margin-bottom: 0;">
                <div class="product" style="margin-bottom: 0;">
                    <div class="row m-b-10">
                        <div class="col-lg-5">
                            <div class="product-image m-b-20">
                                <!-- Carousel slider -->
                                <div class="carousel dots-inside dots-dark arrows-visible arrows-only arrows-dark" data-items="1" data-loop="true" data-autoplay="true" data-animate-in="fadeIn" data-animate-out="fadeOut" data-autoplay-timeout="2500" data-lightbox="gallery">
                                    <?php
                                    if (isset($file_list[1])) {
                                        echo "<img src='" . base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'] . "' title='" . $edition_data['edition_name'] . " Logo' />";
                                    }
                                    // potential to add photos here
                                    ?>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-7">
                            <p style="color: red; font-weight: bold;">This event has been cancelled</p>
                            <?php
                            // if (($edition_data['edition_status'] == 1) || ($edition_data['edition_status'] == 17)) {
                            if (strlen($edition_data['edition_intro_detail']) > 13) {
                                echo $edition_data['edition_intro_detail'];
                            }
                            // }
                            ?>

                            <div class="product-description">
                                <div class="product-category"><?= $edition_data['annual_name']; ?></div>
                                <div class="product-title">
                                    <h3>Summary</h3>
                                </div>
                                <div class="m-b-20"></div>
                                <div id="race_badges">
                                    <?php
                                    foreach ($edition_data['race_summary']['list'] as $race) {
                                        echo '<h3><a href="' . base_url('event/' . $slug . '/distances/' . url_title($race['name'])) . '"><span class="badge badge-' . $race['color'] . '">' . fraceDistance($race['distance'], true) . '<br>'
                                            . '<small>' . $race['type'] . '</small></span></a></h3>';
                                    }
                                    ?>
                                </div>
                                <div class="seperator m-t-10 m-b-10"></div>
                                <!-- <div class="col-lg-12">  -->
                                <p style='font-size: 0.9em;'>
                                    <?php
                                    // CLUB
                                    if ($edition_data['club_id'] != 8) {
                                    ?>
                                        <b>Organisers</b>:
                                        <?php
                                        if (isset($edition_data['club_url_list'][0])) {
                                            echo "<a href='" . $edition_data['club_url_list'][0]['url_name'] . "' target='_blank' title='Visit club website' class='link'>" . $edition_data['club_name'] . "</a>";
                                        } else {
                                            echo $edition_data['club_name'];
                                        }
                                        ?>
                                        <br>
                                    <?php
                                    }
                                    // ASA MEMBERSHIP
                                    if ($edition_data['asa_member_id'] > 0) {
                                        echo "Athletics Association: <u><a href='" . $edition_data['asa_member_url'] . "' target='_blank' title='" . $edition_data['asa_member_abbr'] . "'>" . "" . $edition_data['asa_member_name'] . "</a></u>";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ad box -->
                    <div class="row m-b-30">
                        <div class="col-lg-12">
                            <?php
                            // LANDSCAPE ADS WIDGET
                            $this->load->view('widgets/horizontal_ad');
                            ?>
                        </div>
                    </div>

                    <!-- race organisers info -->
                    <div class="heading-text heading-line m-t-50">
                        <h4 class="text-uppercase">Race Organisers info</h4>
                    </div>
                    <div class="row m-b-60">
                        <div class="col-lg-12">
                            <p class="contact_info">
                                <?php
                                if ($edition_data['club_id'] != 8) {
                                ?>
                                    <b>Oragnisers: </b>
                                    <?php
                                    if (isset($edition_data['club_url_list'][0])) {
                                        echo "<a href='" . $edition_data['club_url_list'][0]['url_name'] . "' target='_blank' title='Visit club website' class='link'>" . $edition_data['club_name'] . "</a>";
                                    } else {
                                        echo $edition_data['club_name'];
                                    }
                                    ?>
                                    <br>
                                <?php
                                }
                                ?>
                                <i class="fa fa-envelope"></i> <a href="mailto:<?= $edition_data['user_email']; ?>?subject=<?= $edition_data['event_name']; ?> query from roadrunning.co.za"><?= $edition_data['user_email']; ?></a>
                                <?php
                                if ($edition_data['user_contact']) {
                                ?>
                                    <br><i class="fa fa-phone"></i> <?= fphone($edition_data['user_contact']); ?>
                                <?php
                                }
                                ?>
                            </p>
                            <p>Use the form below to contact the race organisers directly.</p>
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
                    </div>

                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">
                <?php
                // TAGS WIDGET
                $this->load->view('widgets/tags');
                // RACE STATUS
                echo "<div class='widget clearfix widget-status'>";
                $this->load->view('widgets/race_status', $status_notice);
                echo "</div>";
                echo "<div class='m-b-30'></div>";
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->