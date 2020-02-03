<?= form_open($form_url); ?>
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
                <div class="row">
                    <div class='col-md-5 col-sm-5 form-group'>
                        <?php
                        echo form_label('Name', 'racetype_name');
                        echo form_input([
                            'name' => 'racetype_name',
                            'id' => 'racetype_name',
                            'value' => set_value('racetype_name', @$racetype_detail['racetype_name']),
                            'class' => 'form-control',
                        ]);
                        ?>
                    </div>
                    <div class='col-md-3 col-sm-3 form-group'>                            
                        <?php
                        echo form_label('Abbreviation', 'racetype_abbr');
                        echo form_input([
                            'name' => 'racetype_abbr',
                            'id' => 'racetype_abbr',
                            'value' => set_value('racetype_abbr', @$racetype_detail['racetype_abbr']),
                            'class' => 'form-control',
                        ]);
                        ?>
                    </div>
                    <div class='col-md-4 col-sm-4 form-group'>                            
                        <?php
                        echo form_label('Status', 'racetype_status');
                        echo form_dropdown('racetype_status', $status_dropdown, @$racetype_detail['racetype_status'], ["id" => "racetype_status", "class" => "form-control"]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class='col-md-5 col-sm-5 form-group'>
                        <?php
                        echo form_label('Icon', 'racetype_icon');
                        echo form_input([
                            'name' => 'racetype_icon',
                            'id' => 'racetype_icon',
                            'value' => set_value('racetype_icon', @$racetype_detail['racetype_icon']),
                            'class' => 'form-control',
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="portlet-footer">
                <hr>
                <div class='row'>
                    <div class='col-md-12 col-sm-12'>
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
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>