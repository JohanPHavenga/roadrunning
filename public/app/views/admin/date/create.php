<?php
//adding from edition - diable fields
if (($action == "add") && (@$date_detail['linked_id'] > 1)) {
    $disable_fields = true;
} else {
    $disable_fields = false;
}
echo form_open($form_url);
?>
<div class="row">
    <div class="col-md-6">
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
                        <div class='col-md-4'>
                            <?php
                            echo form_label('Start Date <span class="compulsary">*</span>', 'date_start');
                            echo '<div class="input-group input-medium">';
                            echo form_input([
                                'name' => 'date_start',
                                'id' => 'date_start',
                                'value' => set_value('date_start', @$date_detail['date_start']),
                                'class' => 'form-control form_datetime',
                            ]);
                            echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                            ?>
                        </div>
                        <div class='col-md-4'>
                            <?php
                            echo form_label('End Date', 'date_end');
                            echo '<div class="input-group input-medium">';
                            echo form_input([
                                'name' => 'date_end',
                                'id' => 'date_end',
                                'value' => set_value('date_end', @$date_detail['date_end']),
                                'class' => 'form-control form_datetime',
                            ]);
                            echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                            ?>
                        </div>
                    </div>
                </div>
                <?php

                //  Date type
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12'>";
                echo form_label('Date Type <span class="compulsary">*</span>', 'event_id');
                echo form_dropdown('datetype_id', $datetype_dropdown, @$date_detail['datetype_id'], ["id" => "edition_id", "class" => "form-control input-xlarge"]);
                echo "</div>";
                echo "</div>";
                echo "</div>";

                //  Linked to
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12 linked_to'>";
                echo form_label('Linked to? <span class="compulsary">*</span>', 'date_linked_to');
                $dropdown_data = [
                    "id" => "file_linked_to",
                    "class" => "form-control input-xlarge"
                ];
                if ($disable_fields) {
                    $dropdown_data["readonly"] = "readonly";
                }
                echo form_dropdown('date_linked_to', $linked_to_dropdown, @$date_detail['date_linked_to'], $dropdown_data);
                echo "</div>";
                echo "</div>";
                echo "</div>";

                foreach ($linked_to_list as $linked_to_id => $linked_to_name) {
                    $h_class = "input-" . $linked_to_name;
                    $h_id = 0;
                    $linked_id_name = $linked_to_name . "_id";
                    $class_name = "hidden-input-" . $linked_to_name;
                    $dropdown_name = $linked_to_name . "_dropdown";

                    if ((($action == "add") && (@$date_detail['linked_id'] < 1)) || (@$date_detail['date_linked_to'] != $linked_to_name)) {
                        $h_class = $class_name;
                    } else {
                        $h_id = @$date_detail['linked_id'];
                    }
                    echo "<div class='form-group $h_class'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo form_label(ucfirst($linked_to_name), $linked_id_name);
                    $dropdown_data = [
                        "id" => $linked_id_name,
                        "class" => "form-control input-xlarge"
                    ];
                    if ($disable_fields) {
                        $dropdown_data["readonly"] = "readonly";
                    }
                    echo form_dropdown($linked_id_name, $$dropdown_name, @$h_id, $dropdown_data);
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }

                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12'>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

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
        <div class="col-md-6">
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
                        'value' => set_value('created_date', @$date_detail['created_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', @$date_detail['updated_date']),
                        'class' => 'form-control input-medium',
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
//wts(@$date_detail);
?>