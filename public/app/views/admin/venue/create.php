<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> Venue</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Name', 'venue_name');
                            echo form_input([
                                'name' => 'venue_name',
                                'id' => 'venue_name',
                                'value' => set_value('venue_name', $venue_detail['venue_name']),
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
                            echo form_label('Province <span class="compulsary">*</span>', 'province_id');
                            echo form_dropdown('province_id', $province_dropdown, $venue_detail['province_id'], ["id" => "province_id", "class" => "form-control"]);
                            ?>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Status', 'venue_status');
                            echo form_dropdown('venue_status', $status_dropdown, set_value('venue_name', $venue_detail['venue_status']), ["id" => "venue_status", "class" => "form-control input-small"]);
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
<?= form_close(); ?>