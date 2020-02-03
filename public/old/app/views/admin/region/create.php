<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> region</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                echo form_open($form_url);

                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-8'>";
                echo form_label('Name <span class="compulsary">*</span>', 'region_name');
                echo form_input([
                    'name' => 'region_name',
                    'id' => 'region_name',
                    'value' => set_value('region_name', @$region_detail['region_name']),
                    'class' => 'form-control',
                ]);
                echo "</div>";
                echo "</div>";
                echo "</div>";

                //  STATUS
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-4'>";
                echo form_label('Status <span class="compulsary">*</span>', 'region_status');
                echo form_dropdown('region_status', $status_dropdown, @$region_detail['region_status'], ["id" => "region_status", "class" => "form-control"]);
                echo "</div>";
                echo "</div>";
                echo "</div>";

                //  PROVINCE
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-6'>";
                echo form_label('Province <span class="compulsary">*</span>', 'province_id');
                echo form_dropdown('province_id', $province_dropdown, @$region_detail['province_id'], ["id" => "province_id", "class" => "form-control"]);
                echo "</div>";
                echo "</div>";
                echo "</div>";


                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-12'>";
                echo "</div>";
                echo "</div>";
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
                    <?php
                    //  DATES Created + Updated
                    echo "<div class='form-group'>";
                    echo form_label('Date Created', 'created_date');
                    echo form_input([
                        'value' => set_value('created_date', @$region_detail['created_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', @$region_detail['updated_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    ?>
                </div>
            </div>        
        </div>
        <?php
    }
    ?>

</div>

<?php
//wts($region_detail);
?>