<div class="portlet light" id="races">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">Races</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbuttonLink("/admin/race/create/add/" . $edition_detail['edition_id'], "Add Race", "info"); ?>
        </div>
    </div>
    <!-- RACES FLAT EDIT -->
    <?php
    if ($race_list) {
        foreach ($race_list as $race_id => $race) {
            // set race icon on wheather there is info loaded or not                        
            if ((($race['race_fee_flat'] > 0) || ($race['race_fee_senior_licenced'] > 0)) && ($race['race_time_end'] > 0)) {
                $icon_class = "green";
            } elseif (($race['race_fee_flat'] > 0) || ($race['race_fee_senior_licenced'] > 0)) {
                $icon_class = "amber";
            } else {
                $icon_class = "red";
            }
            // set badge color
            switch ($race['race_status']) {
                case "1":
                    $badge_type = "success";
                    break;
                case "2":
                    $badge_type = "danger";
                    break;
                default;
                    $badge_type = "warning";
                    break;
            }
            
            // chcek results
            if ($race['has_results']) {
                $result_badge_type="success";
                $result_badge_text="<a href='".base_url()."admin/result/delete_result_set/$race_id' data-toggle='confirmation' data-original-title='Delete results?' data-placement='top'>Results</a>";
            } else {
                $result_badge_type="warning";
                $result_badge_text="<a href='".base_url()."admin/result/import/$race_id'>No Results</a>";
            }
            
            if (isset($race['file_list'][4])) {
                $result_file_badge_type="success";
                $result_file_badge_text="Results File";
            } else {
                $result_file_badge_type="danger";
                $result_file_badge_text="No Results File";
            }
            
            ?>
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject uppercase">
                        <i class="fa fa-check-square <?= $icon_class; ?>"></i>
                        <strong><?= fraceDistance($race['race_distance']) . "</strong> " . $race['racetype_name']; ?>
                    </span>
                    <span style='margin-top: -2px;' class="badge badge-<?= $badge_type; ?>"> <?= $status_list[$race['race_status']]; ?> </span>
                    <span style='margin-top: -2px;' class="badge badge-<?=$result_badge_type;?>"> <?=$result_badge_text;?> </span>
                    <span style='margin-top: -2px;' class="badge badge-<?=$result_file_badge_type;?>"> <?=$result_file_badge_text;?> </span>
                </div>
                <div class='btn-group pull-right' style="margin: 5px 0 0 10px;">
                    <?php
                    echo fbutton("Apply", "submit", "primary", "sm", "save_only", "races");
                    echo fbuttonLink("/admin/race/create/edit/" . $race_id, "Edit", "info", "sm");
                    $confirm = "data-toggle='confirmation' data-original-title='Are you sure ?' data-placement='left'";
                    echo fbuttonLink("/admin/race/delete/" . $race_id, "Delete", "danger", "sm", $confirm);
                    ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-group">

                    <div class="row">
                        <div class='col-sm-2'>
                            <?php
                            echo form_label("Start", 'race_time_start_' . $race_id);
                            ?>
                            <div class="input-group input-xsmall">
                                <?php
                                echo form_input([
                                    'name' => 'races[' . $race_id . '][race_time_start]',
                                    'id' => 'race_time_start_' . $race_id,
                                    'value' => set_value('races[' . $race_id . '][race_time_start]', $race['race_time_start'], false),
                                    'class' => 'form-control timepicker timepicker-24 input-xsmall',
                                    'required' => '',
                                ]);
                                ?>
                                <span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button>
                            </div>
                        </div>
                        <div class='col-sm-2'>
                            <?php
                            echo form_label("Cut-off", 'race_time_end_' . $race_id);
                            ?>
                            <div class="input-group input-xsmall">
                                <?php
                                echo form_input([
                                    'name' => 'races[' . $race_id . '][race_time_end]',
                                    'id' => 'race_time_end_' . $race_id,
                                    'value' => set_value('races[' . $race_id . '][race_time_end]', $race['race_time_end'], false),
                                    'class' => 'form-control timepicker timepicker-24 input-xsmall',
                                    'required' => '',
                                ]);
                                ?>
                                <span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button>
                            </div>
                        </div>
                        <?php
                        if ($race['race_distance'] >= 10) {
                            ?>
                            <div class='col-sm-2'>
                                <?php
                                echo form_label('Senior Lic', 'race_fee_senior_licenced_' . $race_id);
                                echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                                echo form_input([
                                    'name' => 'races[' . $race_id . '][race_fee_senior_licenced]',
                                    'id' => 'race_fee_senior_licenced_' . $race_id,
                                    'value' => set_value('races[' . $race_id . '][race_fee_senior_licenced]', $race['race_fee_senior_licenced']),
                                    'class' => 'form-control input-xsmall',
                                    'type' => 'number',
                                    'step' => ".01",
                                    'min' => '0',
                                ]);
                                echo "</div>";
                                ?>
                            </div>
                            <?php
                            if ($race['race_distance'] < 20) {
                                ?>
                                <div class='col-sm-2'>
                                    <?php
                                    echo form_label('Junior Lic', 'race_fee_junior_licenced_' . $race_id);
                                    echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                                    echo form_input([
                                        'name' => 'races[' . $race_id . '][race_fee_junior_licenced]',
                                        'id' => 'race_fee_junior_licenced_' . $race_id,
                                        'value' => set_value('races[' . $race_id . '][race_fee_junior_licenced]', $race['race_fee_junior_licenced']),
                                        'class' => 'form-control input-xsmall',
                                        'type' => 'number',
                                        'step' => ".01",
                                        'min' => '0',
                                    ]);
                                    echo "</div>";
                                    ?>

                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class='col-sm-2'>
                                <?php
                                echo form_label('Flat Fee', 'race_fee_flat_' . $race_id);
                                echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                                echo form_input([
                                    'name' => 'races[' . $race_id . '][race_fee_flat]',
                                    'id' => 'race_fee_flat_' . $race_id,
                                    'value' => set_value('races[' . $race_id . '][race_fee_flat]', $race['race_fee_flat']),
                                    'class' => 'form-control input-xsmall',
                                    'type' => 'number',
                                    'step' => ".01",
                                    'min' => '0',
                                ]);
                                echo "</div>";
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label("Name", 'race_name_' . $race_id);
                            echo form_input([
                                'name' => 'races[' . $race_id . '][race_name]',
                                'id' => 'race_name_' . $race_id,
                                'value' => set_value('races[' . $race_id . '][race_name]', $race['race_name'], true),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='note note-danger' role='alert'><b>No races</b> linked to this edition</div>";
    }
    ?>
</div>