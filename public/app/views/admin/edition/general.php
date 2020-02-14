
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> Edition</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only"); ?>
        </div>
    </div>
    <div class="portlet-body">

        <!-- EDITION NAME -->
        <div class='form-group'>
            <div class='row'>
                <div class='col-sm-7'>
                    <?php
                    echo form_label('Edition Name <span class="compulsary">*</span>', 'edition_name');
                    echo form_input([
                        'name' => 'edition_name',
                        'id' => 'edition_name',
                        'value' => set_value('edition_name', $edition_detail['edition_name'], false),
                        'class' => 'form-control',
                        'required' => '',
                    ]);
                    echo form_input([
                        'name' => 'edition_name_past',
                        'id' => 'edition_name_past',
                        'value' => set_value('edition_name_past', $edition_detail['edition_name']),
                        'type' => 'hidden',
                    ]);
                    ?>
                    <p class='help-block' style='font-style: italic;'> Remember the <b>year</b> at the end of the edition name </p>

                    <?php
                    // EVENT INPUT ON ADD
                    if ($action == "add") {
                        ?>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='form-group'>
                                    <?php
                                    echo form_label('Part of Event <span class="compulsary">*</span>', 'event_id');
                                    echo form_dropdown('event_id', $event_dropdown, set_value('event_id', $edition_detail['event_id']), ["id" => "event_id", "class" => "form-control input-xlarge"]);
                                    echo form_input(['name' => 'edition_status', 'value' => set_value('edition_status', 1), 'type' => 'hidden',]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <?php
                            // EDITION STATUS    
                            echo form_label('Edition Status <span class="compulsary">*</span>', 'edition_status');
                            echo form_dropdown('edition_status', $status_dropdown, set_value('edition_status', $edition_detail['edition_status']), ["id" => "edition_status", "class" => "form-control input-small"]);
                            ?>
                        </div>
                        <div class='col-sm-6'>
                            <?php
                            echo form_label('Information Status <span class="compulsary">*</span>', 'edition_info_status');
                            echo form_dropdown('edition_info_status', $info_status_dropdown, set_value('edition_info_status', $edition_detail['edition_info_status']), ["id" => "edition_info_status", "class" => "form-control input-small"]);
                            ?>
                        </div>
                    </div>
                    <?php
                    ?>
                </div>
                <div class='col-sm-5'>
                    <?php
                    if ($action == "edit") {
                        echo form_label('', 'event_id');
                        echo "<p class='help-block'><b>Event:</b>" . $edition_detail['event_name'] . " (<a href='" . $event_edit_url . "'>Edit</a>)</p>";
                        echo form_input([
                            'name' => 'event_id',
                            'id' => 'event_id',
                            'value' => set_value('event_id', $edition_detail['event_id']),
                            'type' => 'hidden',
                        ]);
                        ?>
                        <p class='help-block'><b>Town:</b> <?= $edition_detail['town_name']; ?></p>
                        <p class='help-block'> <b>Club:</b> <?= $edition_detail['club_name']; ?></p>
                        <p class='help-block'><b>Slug:</b> <a href='<?= base_url('/event/' . $edition_detail['edition_slug']); ?>' title='Preview' target='_blank'>
                                <?= $edition_detail['edition_slug']; ?></a></p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- FLAGS -->
        <div class="form-group">
            <div class="row">
                <div class='col-md-8'>
                    <div class='mt-checkbox-inline'>
                        <?php
                        $checkbox_array = [
                            [
                                "name" => "edition_isfeatured",
                                "text" => "Is Featured",
                            ],
                            [
                                "name" => "edition_no_auto_mail",
                                "text" => "Block Auto Emails",
                            ],
                        ];
                        foreach ($checkbox_array as $checkbox) {
                            $data = array(
                                'name' => $checkbox['name'],
                                'id' => $checkbox['name'],
                                'value' => '1',
                                'checked' => set_value($checkbox['name'], $edition_detail[$checkbox['name']]),
                            );
                            echo form_hidden($checkbox['name'], 0);
                            echo '<label class="mt-checkbox">' . form_checkbox($data) . $checkbox['text'] . "<span></span></label>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- DATE & GPS -->
            <div class="row">
                <div class='col-sm-4'>
                    <?php
                    echo form_label('Edition Date <span class="compulsary">*</span>', 'edition_date');
                    echo '<div class="input-group date date-picker">';
                    echo form_input([
                        'name' => 'edition_date',
                        'id' => 'edition_date',
                        'value' => set_value('edition_date', fdateShort($edition_detail['edition_date'])),
                        'class' => 'form-control',
                    ]);
                    echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                    ?>
                </div>
                <div class='col-sm-8'>
                    <?php
                    echo form_label('GPS <span class="compulsary">*</span>', 'edition_gps');
                    echo form_input([
                        'name' => 'edition_gps',
                        'id' => 'edition_gps',
                        'value' => set_value('edition_gps', $edition_detail['edition_gps']),
                        'class' => 'form-control',
                    ]);
                    ?>
                    <!--<p class='help-block' style='font-style: italic;'> Ex: -33.844204,19.015049 </p>-->
                </div>
            </div>
        </div>

        <!-- CONTACT / ASA -->
        <div class="form-group">
            <div class="row">
                <div class='col-sm-4'>
                    <?php
                    echo form_label('Contact Person <span class="compulsary">*</span>', 'user_id');
                    echo form_dropdown('user_id', $contact_dropdown, set_value('user_id', $edition_detail['user_id']), ["id" => "user_id", "class" => "form-control"]);
                    ?>
                </div>
                <div class='col-sm-6'>
                    <?php
                    echo form_label('ASA Affiliation', 'edition_asa_member');
                    echo form_dropdown('edition_asa_member', $asamember_dropdown, set_value('edition_asa_member', $edition_detail['edition_asa_member']), ["id" => "edition_asa_member", "class" => "form-control"]);
                    ?>
                </div>
            </div>
        </div>

        <!-- SPONSORS / ENTRY TYPES / REG TYPES -->
        <div class="form-group">
            <div class="row">
                <div class='col-sm-5'>
                    <?php
                    echo form_label('Sponsor <span class="compulsary">*</span>', 'sponsor_id');
                    echo form_multiselect('sponsor_id[]', $sponsor_dropdown, set_value('sponsor_id', $sponsor_list),
                            ["id" => "sponsor_id", "class" => "form-control", "size" => 5]);
                    ?>
                </div>
                <div class='col-sm-3'>
                    <?php
                    echo form_label('Entry Types', 'entry_types');
                    echo form_multiselect('entrytype_id[]', $entrytype_dropdown, set_value('entrytype_id', $entrytype_list),
                            ["id" => "entrytype_id", "class" => "form-control", "size" => 5]);
                    ?>
                </div>
                <div class='col-sm-3'>
                    <?php
                    echo form_label('Registration Types', 'reg_types');
                    echo form_multiselect('regtype_id[]', $regtype_dropdown, set_value('regtype_id', $regtype_list),
                            ["id" => "regtype_id", "class" => "form-control", "size" => 3]);
                    ?>
                </div>
            </div>
        </div>


        <!-- ADDRESS -->
        <div class="row">
            <div class='col-sm-6'>
                <div class="form-group">
                    <?php
                    echo form_label('Street Address Start <span class="compulsary">*</span>', 'edition_address');
                    // TBR
                    echo form_input([
                        'name' => 'edition_address',
                        'id' => 'edition_address',
                        'value' => set_value('edition_address', $edition_detail['edition_address'], false),
                        'class' => 'form-control',
                    ]);
//                            echo form_textarea([
//                                'name' => 'edition_address',
//                                'id' => 'edition_address',
//                                'value' => set_value('edition_address', $edition_detail['edition_address'], false),
//                            ]);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                    echo form_label('Street Address End', 'edition_address_end');
                    // TBR
                    echo form_input([
                        'name' => 'edition_address_end',
                        'id' => 'edition_address_end',
                        'value' => set_value('edition_address_end', $edition_detail['edition_address_end'], false),
                        'class' => 'form-control',
                    ]);
//                            echo form_textarea([
//                                'name' => 'edition_address_end',
//                                'id' => 'edition_address_end',
//                                'value' => set_value('edition_address_end', $edition_detail['edition_address_end'], false),
//                            ]);
                    ?>
                </div>
            </div>
            <div class='col-sm-6'>
                <?php
                
                if ($action == "edit") { 
                    $address_nospaces=url_title($edition_detail['edition_address_end'].", ".$edition_detail['town_name'].", ZA"); 
                    ?>
                    <iframe
                        width="100%"
                        height="250"
                        frameborder="0" style="border:1"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBERO5xjCtTOmjQ_zSSUvlp5YN_l-4yKQw&q=<?= $address_nospaces; ?>" allowfullscreen>
                    </iframe>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class='form-group'>
            <div class="row">
                <div class='col-sm-12'>
                    <?php
                    //  Event Intro
                    echo form_label('Event Intro', 'edition_intro_detail');
                    echo form_textarea([
                        'name' => 'edition_intro_detail',
                        'id' => 'edition_intro_detail',
                        'value' => set_value('edition_intro_detail', @$edition_detail['edition_intro_detail'], false),
                    ]);
                    ?>
                </div>
            </div>           
        </div>

    </div> <!--close portlet body -->
</div> <!--close portlet -->