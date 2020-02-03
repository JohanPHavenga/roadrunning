<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> licence fees</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <?php
                            // EDITION STATUS    
                            echo form_label('ASA Member <span class="compulsary">*</span>', 'asa_member_id');
                            echo form_dropdown('asa_member_id', $asamember_dropdown, set_value('asa_member_id', $asafee_detail['asa_member_id']), [
                                "id" => "asa_member_id",
                                "class" => "form-control input-small"
                            ]);
                            ?>

                        </div>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Year <span class="compulsary">*</span>', 'asa_fee_year');
                            echo form_input([
                                'name' => 'asa_fee_year',
                                'id' => 'asa_fee_year',
                                'value' => set_value('asa_fee_year', $asafee_detail['asa_fee_year']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Distance from (km) <span class="compulsary">*</span>', 'asa_fee_distance_from');
                            echo form_input([
                                'name' => 'asa_fee_distance_from',
                                'id' => 'asa_fee_distance_from',
                                'value' => set_value('asa_fee_distance_from', $asafee_detail['asa_fee_distance_from']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                                'step' => ".1",
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Distance to (km) <span class="compulsary">*</span>', 'asa_fee_distance_to');
                            echo form_input([
                                'name' => 'asa_fee_distance_to',
                                'id' => 'asa_fee_distance_to',
                                'value' => set_value('asa_fee_distance_to', $asafee_detail['asa_fee_distance_to']),
                                'class' => 'form-control input-small',
                                'required' => '',
                                'type' => 'number',
                                'step' => ".1",
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Senior Licence Fee <span class="compulsary">*</span>', 'asa_fee_snr');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'asa_fee_snr',
                                'id' => 'asa_fee_snr',
                                'value' => set_value('asa_fee_snr', $asafee_detail['asa_fee_snr']),
                                'class' => 'form-control input-xsmall',
                            ]);
                            echo "</div>";
                            ?>
                        </div>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Junior Licence Fee', 'asa_fee_jnr');
                            echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>';
                            echo form_input([
                                'name' => 'asa_fee_jnr',
                                'id' => 'asa_fee_jnr',
                                'value' => set_value('asa_fee_jnr', $asafee_detail['asa_fee_jnr']),
                                'class' => 'form-control input-xsmall',
                            ]);
                            echo "</div>";
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
                                    'value' => set_value('created_date', $asafee_detail['created_date']),
                                    'class' => 'form-control input-medium',
                                    'disabled' => ''
                                ]);
                                ?>
                            </div>
                            <div class='col-sm-6'>
                                <?php
                                echo form_label('Date Updated', 'updated_date');
                                echo form_input([
                                    'value' => set_value('updated_date', $asafee_detail['updated_date']),
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
        <div class='btn-group'>
            <?php
            echo fbutton($text = "Apply", $type = "submit", $status = "primary", NULL, "save_only");
            echo fbutton($text = "Save", $type = "submit", $status = "success");
            ?>
        </div>
        <div class='btn-group pull-right'>
            <?php
            echo fbuttonLink($return_url, "Cancel", $status = "warning");
            ?>
        </div>
    </div> 
</div> 

<?php
echo form_close();
//wts($asafee_detail);
//wts($edition_detail);