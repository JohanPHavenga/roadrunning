<div class="c-content-box c-size-sm <?= $box_color; ?>">
    <div class="container">
        <div class="row">
            <?php
            if ((strlen($event_detail['edition_general_detail']) > 10) || ($event_detail['edition_info_medals'])) {
                ?>
                <div class="col-md-7">
                    <div class="c-content-title-1 ">
                        <h3 class="c-font-uppercase c-font-bold">
                            General Information
                        </h3>
                    </div>
                    <ul>
                        <?php
                        // MEDALS
                        if ($event_detail['edition_info_medals']) {
                            echo "<li><strong>MEDALS:</strong> ";
                            if (!empty($event_detail['edition_info_medals_text'])) {
                                echo $event_detail['edition_info_medals_text'];
                            } else {
                                echo "Medals will be awarded to all finishers within cut-off times";
                            }
                            echo "</li>";
                        }

                        // PRIZE-GIVING
                        if (!is_null($event_detail['edition_info_prizegizing'])) {
                            if ($event_detail['edition_info_prizegizing'] != "00:00:00") {
                                echo "<li><strong>PRIZE-GIVING:</strong> Scheduled to start at " . ftimeMil($event_detail['edition_info_prizegizing']) . "</li>";
                            }
                        }

                        // LUCKY DRAWS
                        if ($event_detail['edition_info_luckydraw']) {
                            echo "<li><strong>LUCKY DRAWS:</strong> Many lucky draws will be available</li>";
                        }

                        // TOG BAG
                        if ($event_detail['edition_info_togbag']) {
                            echo "<li><strong>TOG BAG:</strong> Tog bag facilities will be available, used at own risk</li>";
                        }

                        // REFRESHMENTS
                        if ($event_detail['edition_info_refreshments']) {
                            echo "<li><strong>REFRESHMENTS:</strong> Refreshments will be on sale at the event</li>";
                        }

                        // SOCIAL WALKERS
                        if ($event_detail['edition_info_socialwalkers']) {
                            echo "<li><strong>SOCIAL WALKERS:</strong> Social walkers are welcome</li>";
                        }

                        // HEADPHONES
                        if ($event_detail['edition_info_headphones']) {
                            echo "<li><strong>HEADPHONES:</strong> The use of music players with headphones is not allowed and may result in disqualification</li>";
                        }
                        ?>
                    </ul>
                    <?php
                    // GENERAL INFORMATION
                    echo $event_detail['edition_general_detail'];
                    echo "<p>&nbsp;</p>";
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="col-md-5" id="contact">
                <div class="c-contact">
                    <div class="c-content-title-1">
                        <h3 class="c-font-uppercase c-font-bold">Contact Event Organiser</h3>
                    </div>
                    <?php
                    if ($_POST) {
                        if (!@$email_send) {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo validation_errors();
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-success" role="alert">';
                            echo "Thank you for contacting us. Your message has successfully been send.<br>We will get back to you as soon as we can.";
                            echo '</div>';
                        }
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
                        <label for="dmsg">Comment *</label>
                        <?php
                        echo form_textarea(
                                [
                                    'id' => 'dmsg',
                                    'name' => 'dmsg',
                                    'value' => @$form_data['dmsg'],
                                    'placeholder' => 'Write comment here ...',
                                    'rows' => '5',
                                    'class' => 'form-control c-square c-theme input-lg',
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
                                'content' => 'Submit'
                            ]
                    );
                    echo form_hidden('devent', $event_detail['edition_name']);
                    echo form_hidden('dto', $event_detail['user_email']);
                    echo form_hidden('dreturn_url', $event_detail['summary']['edition_url']);
                    echo form_close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>