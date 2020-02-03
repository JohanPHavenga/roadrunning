<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> Date Type</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                echo form_open($form_url);

                echo "<div class='form-group'>";
                echo form_label('Name', 'datetype_name');
                echo form_input([
                    'name' => 'datetype_name',
                    'id' => 'datetype_name',
                    'value' => set_value('datetype_name', $datetype_detail['datetype_name']),
                    'class' => 'form-control',
                ]);
                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Display', 'datetype_display');
                echo form_input([
                    'name' => 'datetype_display',
                    'id' => 'datetype_display',
                    'value' => set_value('datetype_display', $datetype_detail['datetype_display']),
                    'class' => 'form-control',
                ]);
                echo "</div>";

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Status', 'datetype_status');
                echo form_dropdown('datetype_status', $status_dropdown, set_value('datetype_name', $datetype_detail['datetype_status']), ["id" => "datetype_status", "class" => "form-control"]);
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