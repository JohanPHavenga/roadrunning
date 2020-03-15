<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> Tag</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Name', 'tag_name');
                            echo form_input([
                                'name' => 'tag_name',
                                'id' => 'tag_name',
                                'value' => set_value('tag_name', $tag_detail['tag_name']),
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
                            echo form_label('Tag Type <span class="compulsary">*</span>', 'tagtype_id');
                            echo form_dropdown('tagtype_id', $tagtype_dropdown, set_value('tagtype_id', $date_detail['tagtype_id']), ["id" => "tagtype_id", "class" => "form-control"]);
                            ?>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Status', 'tag_status');
                            echo form_dropdown('tag_status', $status_dropdown, set_value('tag_status', $tag_detail['tag_status']), ["id" => "tag_status", "class" => "form-control input-small"]);
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