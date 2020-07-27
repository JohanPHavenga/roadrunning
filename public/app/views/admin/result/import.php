
<?= form_open_multipart($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import Result Set</span>
                </div>
            </div>
            <?php
            if (@$error) {
                echo "<div class='note note-danger' role='alert'>$error</div>";
            }
            ?>

            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <div class='form-group'>
                        <?php
                        echo form_label('Race <span class="compulsary">*</span>', 'race_id');
                        echo form_dropdown('race_id', $race_dropdown, set_value('race_id',$race_id), ["id" => "race_id", "class" => "form-control"]);
                        ?>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='form-group'>
                        <?php
                        echo form_label('File to upload <span class="compulsary">*</span>', 'result_file');
                        echo form_upload([
                            'name' => 'result_file',
                            'id' => 'result_file',
                            'class' => 'filestyle',
                            "accept" => '.xls,.xlsx,.csv',
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class='btn-group'>
                <?= fbutton($text = "Upload", $type = "submit", $status = "primary"); ?>
            </div>

        </div>
    </div>
</div>
<?= form_close(); ?>
<?php
//    wts($_POST);
