
<?php
echo form_open($form_url);
?>
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
                //  NAME
                echo "<div class='form-group'>";
                echo form_label('Parkrun Name', 'parkrun_name');
                echo form_input([
                    'name' => 'parkrun_name',
                    'id' => 'parkrun_name',
                    'value' => set_value('parkrun_name', @$parkrun_detail['parkrun_name']),
                    'class' => 'form-control',
                ]);

                echo "</div>";

                //  ADDRESS
                echo "<div class='form-group'>";
                echo form_label('Street Address', 'parkrun_address');
                echo form_input([
                    'name' => 'parkrun_address',
                    'id' => 'parkrun_address',
                    'value' => utf8_encode(@$parkrun_detail['parkrun_address']),
                    'class' => 'form-control',
                ]);

                echo "</div>";

                
                //  TOWN
                echo "<div class='form-group'>";
                echo form_label('Town <span class="compulsary">*</span>', 'user_id');
                echo form_dropdown('town_id', $town_dropdown, @$parkrun_detail['town_id'], ["id" => "town_id", "class" => "form-control"]);
                echo "</div>";

                //  TOWN
//                echo "<div class='form-group'>";
//                echo form_label('Town', 'town_name');
//                echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span>';
//                echo form_input([
//                    'id' => 'town_name',
//                    'name' => 'town_name',
//                    'value' => set_value('parkrun_name', @$parkrun_detail['town_name']),
//                    'class' => 'form-control',
//                ]);
//                echo "</div></div>";
//                echo form_input([
//                    'name' => 'town_id',
//                    'id' => 'town_id',
//                    'type' => 'hidden',
//                    'value' => set_value('parkrun_id', @$parkrun_detail['town_id']),
//                ]);


                //  GPS
                echo "<div class='form-group'>";
                echo form_label('Latitude and Longitude', 'latitude_num');
                echo "<div class='row'>";
                echo "<div class='col-md-6 col-sm-6'>";
                echo form_input([
                    'name' => 'latitude_num',
                    'id' => 'latitude_num',
                    'value' => utf8_encode(@$parkrun_detail['latitude_num']),
                    'class' => 'form-control',
                ]);
                echo "<p class='help-block' style='font-style: italic;'> Ex: -33.844204 </p>";
                echo "</div>";

                echo "<div class='col-md-6 col-sm-6'>";
                echo form_input([
                    'name' => 'longitude_num',
                    'id' => 'longitude_num',
                    'value' => utf8_encode(@$parkrun_detail['longitude_num']),
                    'class' => 'form-control',
                ]);
                echo "<p class='help-block' style='font-style: italic;'> Ex: 19.015049 </p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                //  URL
                echo "<div class='form-group'>";
                echo form_label('Link parkrun site');
                echo form_input([
                    'name' => 'parkrun_url',
                    'id' => 'parkrun_url',
                    'value' => set_value('parkrun_url', @$parkrun_detail['parkrun_url']),
                    'class' => 'form-control',
                ]);

                echo "</div>";

                //  CONTACT
                echo "<div class='row'>";
                echo "<div class='col-md-6'>";
                echo form_label('Contact Person <span class="compulsary">*</span>', 'user_id');
                echo form_dropdown('user_id', $user_dropdown, @$parkrun_detail['user_id'], ["id" => "user_id", "class" => "form-control"]);
                echo "</div>";

                //  STATUS
                echo "<div class='col-md-6'>";
                echo form_label('Status', 'parkrun_status');
                echo form_dropdown('parkrun_status', $status_dropdown, @$parkrun_detail['parkrun_status'], ["id" => "parkrun_status", "class" => "form-control"]);
                echo "</div>";
                echo "</div>";
                ?>

            </div>
        </div>
    </div>

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
                //  COMMENT
                echo "<div class='form-group'>";
                echo form_label('Comments', 'parkrun_comment');
                echo form_textarea([
                    'name' => 'parkrun_comment',
                    'id' => 'parkrun_comment',
                    'value' => utf8_encode(@$parkrun_detail['parkrun_comment']),
                ]);

                echo "</div>";

               
                if ($action=="edit") {   

                    echo "<div class='form-group'>";
                        echo "<div class='row'>";
                            echo "<div class='col-md-6'>";
                            echo form_label('Date Created', 'created_date');
                            echo form_input([
                                    'value'         => set_value('created_date', @$parkrun_detail['created_date']),
                                    'class'         => 'form-control input-medium',
                                    'disabled'      => ''
                                ]);
                            echo "</div>";
                            echo "<div class='col-md-6'>";
                            echo form_label('Date Updated', 'updated_date');
                            echo form_input([
                                    'value'         => set_value('updated_date', @$parkrun_detail['updated_date']),
                                    'class'         => 'form-control input-medium',
                                    'disabled'      => ''
                                ]);

                            echo "</div>";
                        echo "</div>";
                     echo "</div>";
                    
                }
                ?>
            </div> <!-- portlet-bdy -->
        </div> <!-- portlet-light -->
    </div> <!-- col-6 -->
    
</div> <!-- row -->

<div class="row">
    <div class="col-md-6">    
        <div class="portlet light"> 
            
            <div class="portlet-body">            
                <?php
                 //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
    
</div>

<?php
echo form_close();

//wts(@$parkrun_detail);
?>