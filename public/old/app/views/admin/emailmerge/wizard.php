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
                    <span class="caption-subject font-dark bold uppercase"><?=$title;?></span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                //  EMAIL TEMPLATE
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12 linked_to'>";
                echo form_label('Template to use <span class="compulsary">*</span>', 'emailtemplate_id');
                echo form_dropdown('emailtemplate_id', $emailtemplate_dropdown, "", ["id" => "emailtemplate_id", "class" => "form-control input-large", "required" => ""]);
                echo "</div>";
                echo "</div>";
                echo "</div>";
                ?>
            </div>
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Pull user subscription information from:</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                //  Linked to
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12 linked_to'>";
                echo form_label('Subscribed to? <span class="compulsary">*</span>', 'linked_to');
                $dropdown_data = [
                    "id" => "linked_to",
                    "class" => "form-control input-xlarge",
                    "required" => "required"
                ];
                echo form_dropdown('linked_to', $linked_to_dropdown, @$usersubscription_detail['linked_to'], $dropdown_data);
                echo "</div>";
                echo "</div>";
                echo "</div>";

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
                    echo "<div class='form-group $h_class'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo form_label(ucfirst($linked_to_name), $linked_id_name);
                    $dropdown_data = [
                        "id" => $linked_id_name,
                        "class" => "form-control input-xlarge",
                        "required" => "required"
                    ];
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
                echo fbutton($text = "Continue", $type = "submit", $status = "primary");
                echo fbuttonLink($return_url, "Cancel", $status = "default");
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