<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action);?> entry</span>
                </div>
            </div>
            <div class="portlet-body">
            <?php  
                echo form_open($form_url); 

                echo "<div class='form-group'>";
                echo form_label('Name', 'asa_member_name');
                echo form_input([
                        'name'          => 'asa_member_name',
                        'id'            => 'asa_member_name',
                        'value'         => set_value('asa_member_name', @$asamember_detail['asa_member_name']),
                        'class'         => 'form-control',
                    ]);

                echo "</div>";
                
                echo "<div class='form-group'>";
                echo form_label('Abbreviation', 'asa_member_abbr');
                echo form_input([
                        'name'          => 'asa_member_abbr',
                        'id'            => 'asa_member_abbr',
                        'value'         => set_value('asa_member_abbr', @$asamember_detail['asa_member_abbr']),
                        'class'         => 'form-control',
                    ]);

                echo "</div>";
                
                echo "<div class='form-group'>";
                echo form_label('URL', 'asa_member_url');
                echo form_input([
                        'name'          => 'asa_member_url',
                        'id'            => 'asa_member_url',
                        'value'         => set_value('asa_member_name', @$asamember_detail['asa_member_url']),
                        'class'         => 'form-control',
                    ]);

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Status', 'asa_member_status');
                echo form_dropdown('asa_member_status', $status_dropdown, @$asamember_detail['asa_member_status'], ["id"=>"asa_member_status","class"=>"form-control"]);        
                echo "</div>";    

                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";
                
//                echo "<div class='btn-group'>";
//                echo fbutton();
//                echo fbuttonLink($return_url,"Cancel");
//                echo "</div>";

                echo form_close();

            //    wts($town_dropdown);

                //<input type="submit" name="submit" value="Edit Event">
            ?>
            </div>
        </div>
    </div>
</div>