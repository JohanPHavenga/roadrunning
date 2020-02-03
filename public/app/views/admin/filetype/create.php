<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> File Type</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class='form-group'>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <?php
                            echo form_label('Name', 'filetype_name');
                            echo form_input([
                                'name' => 'filetype_name',
                                'id' => 'filetype_name',
                                'value' => set_value('filetype_name', @$filetype_detail['filetype_name']),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                        <div class='col-sm-4'>
                            <?php
                            echo form_label('Button Text', 'filetype_buttontext');
                            echo form_input([
                                'name' => 'filetype_buttontext',
                                'id' => 'filetype_buttontext',
                                'value' => set_value('filetype_buttontext', @$filetype_detail['filetype_buttontext']),
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
                            echo form_label('Help Text', 'filetype_helptext');
                            echo form_input([
                                'name' => 'filetype_helptext',
                                'id' => 'filetype_helptext',
                                'value' => set_value('filetype_helptext', @$filetype_detail['filetype_helptext']),
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
                                    'value' => set_value('created_date', @$filetype_detail['created_date']),
                                    'class' => 'form-control input-medium',
                                    'disabled' => ''
                                ]);
                                ?>
                            </div>
                            <div class='col-sm-6'>
                                <?php
                                echo form_label('Date Updated', 'updated_date');
                                echo form_input([
                                    'value' => set_value('updated_date', @$filetype_detail['updated_date']),
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
