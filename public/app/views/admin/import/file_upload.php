
<?= form_open_multipart($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import event information</span>
                </div>
            </div>
            <?php
            if (@$error) {
                echo "<div class='note note-danger' role='alert'>$error</div>";
            }
            ?>

            <div class="row">
                <div class='col-md-6 col-sm-6'>
                    <div class='form-group'>
                        <?php
                        echo form_label('ASA Memeber <span class="compulsary">*</span>', 'asamember_id');
                        echo form_dropdown('asamember_id', $asamember_dropdown, [], ["id" => "asamember_id", "class" => "form-control"]);
                        ?>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-8'>
                    <div class='form-group'>
                        <?php
                        echo form_label('File to upload <span class="compulsary">*</span>', 'event_info_file');
                        echo form_upload([
                            'name' => 'event_info_file',
                            'id' => 'event_info_file',
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
