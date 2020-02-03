
<div class="portlet light" id="dates_reg_flat">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">REGISTRATION DETAILS</span>
        </div>
        <div class='btn-group pull-right'>
            <?php
            echo fbutton("Apply", "submit", "primary", null, "save_only", "dates_reg_flat");
//            echo fbuttonLink("/admin/date/create/add/" . $edition_detail['edition_id'] . "/edition", "Add Date", "info");
            ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php
        // =====================================================================
        // ON THE DAY REGISTRATION
        // =====================================================================
        if (in_array(1, $regtype_list)) {
            $dateype_id = 9;
            if (isset($date_list_by_type[$dateype_id])) {
                $date_detail = $date_list_by_type[$dateype_id][0];
                $date_id = $date_detail['date_id'];
                ?><div class="form-group">
                    <div class="row">
                        <div class='col-sm-3'>
                            <?php
                            $field = "date_start";
                            $display = "Open";
                            $error_value = $edition_detail['edition_date'];
                            $field_id = $field . "_" . $date_id;
                            $field_name = 'dates[' . $date_id . '][' . $field . ']';
                            echo form_label($date_detail['datetype_display'] . " " . $display, $field_id);
                            $form_input_array = [
                                'name' => 'dates[' . $date_id . '][' . $field . ']',
                                'id' => $field_id,
                                'class' => 'form-control timepicker timepicker-24 input-xsmall',
                                'value' => set_value($field_name, ftimeSort($date_detail[$field])),
                            ];
                            if ($date_detail[$field] == $error_value) {
                                $form_input_array['class'] = $form_input_array['class'] . " danger";
                            }
                            echo '<div class="input-group input-xsmall">';
                            echo form_input($form_input_array);
                            echo '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button></div>';
                            ?>
                        </div>
                        <div class='col-sm-3'>
                            <?php
                            $field = "date_end";
                            $display = "Close";
                            $error_value = strtotime($date_detail['date_start']);
                            $field_id = $field . "_" . $date_id;
                            $field_name = 'dates[' . $date_id . '][' . $field . ']';
                            echo form_label($date_detail['datetype_display'] . " " . $display, $field_id);
                            $form_input_array = [
                                'name' => 'dates[' . $date_id . '][' . $field . ']',
                                'id' => $field_id,
                                'class' => 'form-control timepicker timepicker-24 input-xsmall',
                                'value' => set_value($field_name, ftimeSort($date_detail[$field])),
                            ];
                            if (strtotime($date_detail[$field]) <= $error_value) {
                                $form_input_array['class'] = $form_input_array['class'] . " danger";
                            }
                            echo '<div class="input-group input-xsmall">';
                            echo form_input($form_input_array);
                            echo '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button></div>';
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo "<div class='note note-danger' role='alert'><b>OTD REGISTRATION DATES</b> NOT LOADED</div>";
            }
        }
        // =====================================================================
        // PRE ENTRIES
        // =====================================================================
        if (in_array(2, $regtype_list)) {
            $dateype_id = 10;
            if (isset($date_list_by_type[$dateype_id])) {
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class='col-sm-4'>
                            <?= form_label("Pre-Registration"); ?>
                        </div>
                    </div>
                    <?php
                    foreach ($date_list_by_type[$dateype_id] as $date_detail) {
                        $date_id = $date_detail['date_id'];
                        ?>

                        <div class="row">
                            <div class='col-sm-4'>
                                <?php
                                $field = "date_start";
                                $display = "Open";
                                $error_value = $edition_detail['edition_date'];
                                $field_id = $field . "_" . $date_id;
                                $field_name = 'dates[' . $date_id . '][' . $field . ']';
                                echo form_label($display, $field_id);
                                $form_input_array = [
                                    'name' => 'dates[' . $date_id . '][' . $field . ']',
                                    'id' => $field_id,
                                    'class' => 'form-control form_datetime',
                                    'value' => set_value($field_name, fdateLong($date_detail[$field], false)),
                                ];
                                if ($date_detail[$field] == $error_value) {
                                    $form_input_array['class'] = $form_input_array['class'] . " danger";
                                }
                                echo '<div class="input-group">';
                                echo form_input($form_input_array);
                                echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                                ?>
                            </div>
                            <div class='col-sm-2'>
                                <?php
                                $field = "date_end";
                                $display = "Close";
                                $error_value = strtotime($date_detail["date_start"]);
                                $field_id = $field . "_" . $date_id;
                                $field_name = 'dates[' . $date_id . '][' . $field . ']';
                                echo form_label($display, $field_id);
                                $form_input_array = [
                                    'name' => 'dates[' . $date_id . '][' . $field . ']',
                                    'id' => $field_id,
                                    'class' => 'form-control timepicker timepicker-24 input-xsmall',
                                    'value' => set_value($field_name, ftimeSort($date_detail[$field])),
                                ];
                                if (strtotime($date_detail[$field]) <= $error_value) {
                                    $form_input_array['class'] = $form_input_array['class'] . " danger";
                                }
                                echo '<div class="input-group input-xsmall">';
                                echo form_input($form_input_array);
                                echo '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button></div>';
                                ?>
                            </div>
                            <div class='col-sm-5'>
                                <?php
                                // venue_id on open date
                                $field = "venue_id";
                                $field_id = $field . "_" . $date_id;
                                $field_name = 'dates[' . $date_id . '][' . $field . ']';
                                echo form_label("Venue", $field_id);
                                echo form_dropdown(
                                        'dates[' . $date_id . '][' . $field . ']',
                                        $venue_dropdown,
                                        set_value($field_name, $date_detail['venue_id']),
                                        ["id" => $field_id, "class" => "form-control"]
                                );
                                ?>
                            </div>
                            <div class='btn-group pull-right' style="position: relative; top:28px;">
                                <?php
                                echo fbutton("<i class='fa fa-copy white'></i>", "submit", "primary", "sm", "copy_date", "/admin/date/copy/" . $date_id . "/dates_reg_flat");
//                                echo fbuttonLink("/admin/date/copy/" . $date_id."/dates_reg_flat", "<i class='fa fa-copy white'></i>", "info", "sm");
                                $confirm = "data-toggle='confirmation' data-original-title='Are you sure ?' data-placement='top'";
                                echo fbuttonLink("/admin/date/delete/" . $date_id."/dates_reg_flat", "<i class='fa fa-times-circle white'></i>", "danger", "sm", $confirm);
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                echo "<div class='note note-danger' role='alert'><b>PRE REGISTRATION DATES</b> NOT LOADED</div>";
            }
        }
        ?>
        <div class="form-group">
            <div class="row">
                <div class='col-sm-12'>
                    <?php
                    echo form_label('Registration Details', 'edition_reg_detail');
                    echo form_textarea([
                        'name' => 'edition_reg_detail',
                        'id' => 'edition_reg_detail',
                        'value' => set_value('edition_reg_detail', $edition_detail['edition_reg_detail'], false),
                    ]);
                    ?>
                </div>
            </div>
        </div>
        
    </div>
</div>

