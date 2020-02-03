<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> regulation</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Regulation Name', 'asa_reg_distance_name');
                            echo form_input([
                                'name' => 'asa_reg_distance_name',
                                'id' => 'asa_reg_distance_name',
                                'value' => set_value('asa_reg_distance_name', $asareg_detail['asa_reg_distance_name']),
                                'class' => 'form-control input-med',
//                                'required' => '',
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Minimum age', 'asa_reg_minimum_age');
                            echo form_input([
                                'name' => 'asa_reg_minimum_age',
                                'id' => 'asa_reg_minimum_age',
                                'value' => set_value('asa_reg_minimum_age', $asareg_detail['asa_reg_minimum_age']),
                                'class' => 'form-control input-xsmall',
                                'required' => '',
                                'type' => 'number',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('IAAF distance (km)', 'asa_reg_iaaf');
                            echo form_input([
                                'name' => 'asa_reg_iaaf',
                                'id' => 'asa_reg_iaaf',
                                'value' => set_value('asa_reg_iaaf', $asareg_detail['asa_reg_iaaf']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                                'step' => ".1",
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Distance from (km)', 'asa_reg_distance_from');
                            echo form_input([
                                'name' => 'asa_reg_distance_from',
                                'id' => 'asa_reg_distance_from',
                                'value' => set_value('asa_reg_distance_from', $asareg_detail['asa_reg_distance_from']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                                'step' => ".1",
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Distance to (km)', 'asa_reg_distance_to');
                            echo form_input([
                                'name' => 'asa_reg_distance_to',
                                'id' => 'asa_reg_distance_to',
                                'value' => set_value('asa_reg_distance_to', $asareg_detail['asa_reg_distance_to']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                                'step' => ".1",
                            ]);
                            ?>
                        </div>
                    </div>
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
                    <div class='form-group'>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <?php
                                echo form_label('Date Created', 'created_date');
                                echo form_input([
                                    'value' => set_value('created_date', $asareg_detail['created_date']),
                                    'class' => 'form-control input-medium',
                                    'disabled' => ''
                                ]);
                                ?>
                            </div>
                            <div class='col-sm-6'>
                                <?php
                                echo form_label('Date Updated', 'updated_date');
                                echo form_input([
                                    'value' => set_value('updated_date', $asareg_detail['updated_date']),
                                    'class' => 'form-control input-medium',
                                    'disabled' => ''
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
        <?php
    }
    ?>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-body">
                <?php
                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text = "Apply", $type = "submit", $status = "primary", NULL, "save_only");
                echo fbutton($text = "Save", $type = "submit", $status = "success");
                echo fbuttonLink($return_url, "Cancel", $status = "danger");
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
</div>

<?php
echo form_close();
//wts($asareg_detail);
//wts($edition_detail);