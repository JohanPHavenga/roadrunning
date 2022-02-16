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
                <?php
                echo form_open($form_url);

                echo "<div class='form-group'>";
                echo form_label('Name', 'town_name');
                echo form_input([
                    'name' => 'town_name',
                    'id' => 'town_name',
                    'value' => set_value('town_name', @$town_detail['town_name'], false),
                    'class' => 'form-control',
                ]);

                echo "</div>";
                
                echo "<div class='form-group'>";
                echo form_label('Alternate Name', 'town_name_alt');
                echo form_input([
                    'name' => 'town_name_alt',
                    'id' => 'town_name_alt',
                    'value' => set_value('town_name_alt', @$town_detail['town_name_alt'], false),
                    'class' => 'form-control',
                ]);

                echo "</div>";

                //  GPS
                echo "<div class='form-group'>";
                echo form_label('Latitude and Longitude', 'latitude_num');
                echo "<div class='row'>";
                echo "<div class='col-md-6 col-sm-6'>";
                echo form_input([
                    'name' => 'latitude_num',
                    'id' => 'latitude_num',
                    'value' => utf8_encode(@$town_detail['latitude_num']),
                    'class' => 'form-control',
                ]);
                echo "<p class='help-block' style='font-style: italic;'> Ex: -33.844204 </p>";
                echo "</div>";

                echo "<div class='col-md-6 col-sm-6'>";
                echo form_input([
                    'name' => 'longitude_num',
                    'id' => 'longitude_num',
                    'value' => utf8_encode(@$town_detail['longitude_num']),
                    'class' => 'form-control',
                ]);
                echo "<p class='help-block' style='font-style: italic;'> Ex: 19.015049 </p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";


                //  REGION
                echo "<div class='form-group'>";
                echo "<div class='row'>";
                echo "<div class='col-md-6'>";
                echo form_label('Region <span class="compulsary">*</span>', 'region_id');
                echo form_dropdown('region_id', $region_dropdown, @$town_detail['region_id'], ["id" => "region_id", "class" => "form-control"]);
                echo "</div>";

                // BELOW NEED TO BE REMOVED IN TIME
                //  PROVINCE
                echo "<div class='col-md-6'>";
                echo form_label('Province <span class="compulsary">*</span>', 'province_id');
                echo form_dropdown('province_id', $province_dropdown, @$town_detail['province_id'], ["id" => "province_id", "class" => "form-control"]);
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

//                wts($town_detail);
                //<input type="submit" name="submit" value="Edit Event">
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
                        'value' => set_value('created_date', @$town_detail['created_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', @$town_detail['updated_date']),
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