<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> Entry Type</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                echo form_open($form_url);

                echo "<div class='form-group'>";
                echo form_label('Name', 'entrytype_name');
                echo form_input([
                    'name' => 'entrytype_name',
                    'id' => 'entrytype_name',
                    'value' => set_value('entrytype_name', $entrytype_detail['entrytype_name']),
                    'class' => 'form-control',
                ]);

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Status', 'entrytype_status');
                echo form_dropdown('entrytype_status', $status_dropdown, set_value('entrytype_name', $entrytype_detail['entrytype_status']), ["id" => "entrytype_status", "class" => "form-control"]);
                echo "</div>";

                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text = "Save", $type = "submit", $status = "primary", NULL, "save_only");
                echo fbutton($text = "Save & Close", $type = "submit", $status = "success");
                echo fbuttonLink($return_url, "Cancel", $status = "danger");
                echo "</div>";

                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>