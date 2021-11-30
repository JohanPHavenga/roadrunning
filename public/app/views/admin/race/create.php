<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-9">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> entry</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Race Distance (km)', 'race_distance');
                            echo form_input([
                                'name' => 'race_distance',
                                'id' => 'race_distance',
                                'value' => set_value('race_distance', $race_detail['race_distance']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                                'step' => ".1",
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Start Time', 'race_time_start');
                            echo '<div class="input-group input-small">';
                            echo form_input([
                                'name' => 'race_time_start',
                                'id' => 'race_time_start',
                                'value' => set_value('race_time_start', ftimeSort($race_detail['race_time_start'], false)),
                                'class' => 'form-control timepicker timepicker-24',
                                'required' => '',
                            ]);
                            echo '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button></div>';
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Cut-off Time', 'race_time_end');
                            echo '<div class="input-group input-small">';
                            echo form_input([
                                'name' => 'race_time_end',
                                'id' => 'race_time_end',
                                'value' => set_value('race_time_end', ftimeSort($race_detail['race_time_end'])),
                                'class' => 'form-control timepicker timepicker-24',
                            ]);
                            echo '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button></div>';
                            ?>
                        </div>
                    </div>
                </div>


                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Status', 'race_status');
                            echo form_dropdown('race_status', $status_dropdown, set_value('race_status', $race_detail['race_status']), ["id" => "race_status", "class" => "form-control input-small"]);
                            ?>
                        </div>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Race Type', 'racetype_id');
                            echo form_dropdown('racetype_id', $racetype_dropdown, set_value('racetype_id', $race_detail['racetype_id']), ["id" => "racetype_id", "class" => "form-control input-small"]);
                            ?>
                        </div>
                        <div class='col-sm-5'>
                            <?php
                            echo form_label('Race Date', 'race_date');
                            echo '<div class="input-group input-medium date date-picker">';
                            echo form_input([
                                'name' => 'race_date',
                                'id' => 'race_date',
                                'value' => set_value('race_date', fdateShort($race_detail['race_date'])),
                                'class' => 'form-control',
                            ]);
                            echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                            echo "<p class='help-block' style='font-style: italic;'>If different than edition date [" . fdateShort($edition_detail['edition_date']) . "]</p>";
                            ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Race Name', 'race_name');
                            echo form_input([
                                'name' => 'race_name',
                                'id' => 'race_name',
                                'value' => set_value('race_name', $race_detail['race_name'], true),
                                'class' => 'form-control input-medium',
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-8'>
                            <?php
                            echo form_label('Edition', 'edition_id');
                            echo form_dropdown('edition_id', $edition_dropdown, set_value('edition_id', $race_detail['edition_id']), ["id" => "edition_id", "class" => "form-control"]);
                            ?>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Flat Race Fee', 'race_fee_flat');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'race_fee_flat',
                                'id' => 'race_fee_flat',
                                'value' => set_value('race_fee_flat', $race_detail['race_fee_flat']),
                                'class' => 'form-control input-small',
                                'type' => 'number',
                                'step' => ".01",
                                'min' => '0',
                            ]);
                            echo "</div>";
                            echo "<p class='help-block' style='font-style: italic;'>If set it will be the only value shown</p>";
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Senior Race Fee Licenced', 'race_fee_senior_licenced');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'race_fee_senior_licenced',
                                'id' => 'race_fee_senior_licenced',
                                'value' => set_value('race_fee_senior_licenced', $race_detail['race_fee_senior_licenced']),
                                'class' => 'form-control input-small',
                                'type' => 'number',
                                'step' => ".01",
                                'min' => '0',
                            ]);
                            echo "</div>";
                            ?>

                        </div>

                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Senior Race Fee Unlicenced', 'race_fee_senior_unlicenced');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'race_fee_senior_unlicenced',
                                'id' => 'race_fee_senior_unlicenced',
                                'value' => set_value('race_fee_senior_unlicenced', $race_detail['race_fee_senior_unlicenced']),
                                'class' => 'form-control input-small',
                                'type' => 'number',
                                'step' => ".01",
                                'min' => '0',
                            ]);
                            echo "</div>";
                            echo "<p class='help-block' style='font-style: italic;'>Will get auto populated if left blank</p>";
                            ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <div class='mt-checkbox-inline' style='margin-top:20px'>
                                <?php
                                $over_70_data = array(
                                    'name' => 'race_isover70free',
                                    'id' => 'race_isover70free',
                                    'value' => '1',
                                    'checked' => set_value('race_isover70free', $race_detail['race_isover70free']),
                                );
                                echo '<label class="mt-checkbox">' . form_checkbox($over_70_data) . "Over 70 run free?<span></span></label>";
                                ?>
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Junior Race Fee Licenced', 'race_fee_junior_licenced');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'race_fee_junior_licenced',
                                'id' => 'race_fee_junior_licenced',
                                'value' => set_value('race_fee_junior_licenced', $race_detail['race_fee_junior_licenced']),
                                'class' => 'form-control input-small',
                                'type' => 'number',
                                'step' => ".01",
                                'min' => '0',
                            ]);
                            echo "</div>";
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Junior Race Fee Unlicenced', 'race_fee_junior_unlicenced');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'race_fee_junior_unlicenced',
                                'id' => 'race_fee_junior_unlicenced',
                                'value' => set_value('race_fee_junior_unlicenced', $race_detail['race_fee_junior_unlicenced']),
                                'class' => 'form-control input-small',
                                'type' => 'number',
                                'step' => ".01",
                                'min' => '0',
                            ]);
                            echo "</div>";
                            echo "<p class='help-block' style='font-style: italic;'>Will get auto populated if left blank</p>";
                            ?>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class="row">                                
                                <div class='col-sm-5'>
                                    <?php
                                    echo form_label('Entry Limit', 'race_entry_limit');
                                    echo form_input([
                                        'name' => 'race_entry_limit',
                                        'id' => 'race_entry_limit',
                                        'value' => set_value('race_entry_limit', $race_detail['race_entry_limit']),
                                        'class' => 'form-control input-small',
                                    ]);
                                    ?>
                                </div>
                                <div class='col-sm-7'>
                                    <?php
                                    echo form_label('Minimum age', 'race_minimum_age');
                                    echo form_input([
                                        'name' => 'race_minimum_age',
                                        'id' => 'race_minimum_age',
                                        'value' => set_value('race_minimum_age', $race_detail['race_minimum_age']),
                                        'class' => 'form-control input-xsmall',
                                        'type' => 'number',
                                        'min' => '0',
                                    ]);
                                    echo "<p class='help-block' style='font-style: italic;'>Will auto populate if left blank</p>";
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-sm-12'>
                                    <?php
                                    echo form_label('Start address for race', 'race_address');
                                    echo form_input([
                                        'name' => 'race_address',
                                        'id' => 'race_address',
                                        'value' => set_value('race_address', $race_detail['race_address'], true),
                                        'class' => 'form-control',
                                    ]);
                                    echo "<p class='help-block' style='font-style: italic;'>Only applicable if different than edition address<br> [" . $edition_detail['edition_address'] . "]</p>";
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <?php
                            echo form_label('Race Notes', 'race_notes');
                            echo form_textarea([
                                'name' => 'race_notes',
                                'id' => 'race_notes',
                                'value' => utf8_encode($race_detail['race_notes']),
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

                <?php
                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text = "Save", $type = "submit", $status = "primary", NULL, "save_only");
                echo fbutton($text = "Save & Close", $type = "submit", $status = "success");
                echo fbuttonLink($return_url, "Cancel", $status = "danger");
                echo "</div>";
                ?>
            </div>
        </div>
    </div>

    <?php
    if ($action == "edit") {
        ?>
        <div class="col-md-3">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-edit font-dark"></i>
                        <span class="caption-subject font-dark bold uppercase">More information</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php
                    //  DATES Created + Updated
                    echo "<div class='form-group'>";
                    echo form_label('Date Created', 'created_date');
                    echo form_input([
                        'value' => set_value('created_date', $race_detail['created_date']),
                        'class' => 'form-control',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', $race_detail['updated_date']),
                        'class' => 'form-control',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    ?>
                </div>
            </div>        
        </div>
        <?php
    }
    ?>
</div>

<?php
echo form_close();
//wts($race_detail);
//wts($edition_detail);
