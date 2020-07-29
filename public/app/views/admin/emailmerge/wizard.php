<?php
echo form_open($form_url);
//            wts($_POST);
?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= $title; ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class='form-group'>
                    <div class="row">
                        <div class='col-md-12 linked_to'>
                            <?php
                            //  EMAIL TEMPLATE
                            echo form_label('Template to use <span class="compulsary">*</span>', 'emailtemplate_id');
                            echo form_dropdown('emailtemplate_id', $emailtemplate_dropdown, "", ["id" => "emailtemplate_id", "class" => "form-control input-large", "required" => ""]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <div class="row">
                        <div class='col-md-12 linked_to'>
                            <?php
                            //  LINKED TO TYPE
                            echo form_label('Subscribed to? <span class="compulsary">*</span>', 'linked_to');
                            $dropdown_data = [
                                "id" => "linked_to",
                                "class" => "form-control input-xlarge",
                                "required" => "required"
                            ];
                            echo form_dropdown('linked_to', $linked_to_dropdown, @$usersubscription_detail['linked_to'], $dropdown_data);
                            ?>
                        </div>
                    </div>
                </div>

                <?php
                foreach ($linked_to_list as $linked_to_id => $linked_to_name) {
                    $h_class = "input-" . $linked_to_name;
                    $h_id = 0;
                    $linked_id_name = $linked_to_name . "_id";
                    $class_name = "hidden-input-" . $linked_to_name;
                    $dropdown_name = $linked_to_name . "_dropdown";

                    if ((($action == "add") && (@$usersubscription_detail['linked_id'] < 1)) || (@$usersubscription_detail['linked_to'] != $linked_to_name)) {
                        $h_class = $class_name;
                    } else {
                        $h_id = @$usersubscription_detail['linked_id'];
                    }
                    ?>
                    <div class='form-group <?= $h_class; ?>'>
                        <div class="row">
                            <div class='col-md-12 linked_to_type'>
                                <?php
                                //  LINKED TO 
                                echo form_label(ucfirst($linked_to_name), $linked_id_name);
                                $dropdown_data = [
                                    "id" => $linked_id_name,
                                    "class" => "form-control input-xlarge",
                                    "required" => "required"
                                ];
                                echo form_dropdown($linked_id_name, $$dropdown_name, @$h_id, $dropdown_data);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="form-group hidden-date-range">
                    <div class="row">
                        <div class='col-sm-3 col-md-6'>
                            <?php
                            echo form_label("Date From", "date_from");
                            $form_input_array = [
                                'name' => 'date_from',
                                'id' => "date_from",
                                'class' => 'form-control',
                                'value' => set_value("date_from", date("Y-m-d", strtotime("+1 month"))),
                            ];
                            echo '<div class="input-group date date-picker">';
                            echo form_input($form_input_array);
                            echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                            ?>
                        </div>
                        <div class='col-sm-3 col-md-6'>
                            <?php
                            echo form_label("Date To", "date_to");
                            $form_input_array = [
                                'name' => 'date_to',
                                'id' => "date_to",
                                'class' => 'form-control',
                                'value' => set_value("date_to", date("Y-m-d", strtotime("+2 months"))),
                            ];
                            echo '<div class="input-group date date-picker">';
                            echo form_input($form_input_array);
                            echo '<span class="input-group-btn"><button class="btn default" type="button"><i class="fa fa-calendar"></i></button></span></div>';
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-4'>
                            <?php
                            echo form_label("Status","status");
                            $dropdown_data = [
                                "id" => "status",
                                "class" => "form-control input-small",
                                "required" => "required"
                            ];
                            echo form_dropdown("status", $status_dropdown, "status", $dropdown_data);
                            ?>
                        </div>
                    </div>
                </div>

                <div class='btn-group'>
                    <?php
                    //  BUTTONS
                    echo fbutton($text = "Continue", $type = "submit", $status = "primary");
                    echo fbuttonLink($return_url, "Cancel", $status = "default");
                    ?>
                </div>
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
                        'value' => set_value('created_date', @$usersubscription_detail['created_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', @$usersubscription_detail['updated_date']),
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
//wts(@$usersubscription_detail);
?>