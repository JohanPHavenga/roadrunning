<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> Timing Provider</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Name', 'timingprovider_name');
                            echo form_input([
                                'name' => 'timingprovider_name',
                                'id' => 'timingprovider_name',
                                'value' => set_value('timingprovider_name', $timingprovider_detail['timingprovider_name']),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Abbriviation', 'timingprovider_abbr');
                            echo form_input([
                                'name' => 'timingprovider_abbr',
                                'id' => 'timingprovider_abbr',
                                'value' => set_value('timingprovider_abbr', $timingprovider_detail['timingprovider_abbr']),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Status', 'timingprovider_status');
                            echo form_dropdown('timingprovider_status', $status_dropdown, set_value('timingprovider_name', $timingprovider_detail['timingprovider_status']), ["id" => "timingprovider_status", "class" => "form-control"]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $this->load->view('templates/admin/create_footer');
            ?>
        </div>
    </div>
</div>
<?php
echo form_close();
?>