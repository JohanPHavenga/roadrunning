<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> URL Type</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <?php
                            echo form_label('Name', 'urltype_name');
                            echo form_input([
                                'name' => 'urltype_name',
                                'id' => 'urltype_name',
                                'value' => set_value('urltype_name', @$urltype_detail['urltype_name']),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Button Text', 'urltype_buttontext');
                            echo form_input([
                                'name' => 'urltype_buttontext',
                                'id' => 'urltype_buttontext',
                                'value' => set_value('urltype_buttontext', @$urltype_detail['urltype_buttontext']),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-9'>
                            <?php
                            echo form_label('Help Text', 'urltype_helptext');
                            echo form_input([
                                'name' => 'urltype_helptext',
                                'id' => 'urltype_helptext',
                                'value' => set_value('urltype_helptext', @$urltype_detail['urltype_helptext']),
                                'class' => 'form-control',
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
                                    'value' => set_value('created_date', @$urltype_detail['created_date']),
                                    'class' => 'form-control input-medium',
                                    'disabled' => ''
                                ]);
                                ?>
                            </div>
                            <div class='col-sm-6'>
                                <?php
                                echo form_label('Date Updated', 'updated_date');
                                echo form_input([
                                    'value' => set_value('updated_date', @$urltype_detail['updated_date']),
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
