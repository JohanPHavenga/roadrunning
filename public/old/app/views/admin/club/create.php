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
                echo form_label('Name', 'club_name');
                echo form_input([
                        'name'          => 'club_name',
                        'id'            => 'club_name',
                        'value'         => set_value('club_name', @$club_detail['club_name']),
                        'class'         => 'form-control',
                    ]);

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Status', 'club_status');
                echo form_dropdown('club_status', $status_dropdown, @$club_detail['club_status'], ["id"=>"club_status","class"=>"form-control"]);        
                echo "</div>";

                //  TOWN
                echo "<div class='form-group'>";
                echo form_label('Town <span class="compulsary">*</span>', 'user_id');
                echo form_dropdown('town_id', $town_dropdown, @$club_detail['town_id'], ["id" => "town_id", "class" => "form-control"]);
                echo "</div>";
                
//                echo "<div class='form-group'>";
//                echo form_label('Town', 'town_name');
//                echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span>';
//                echo form_input([
//                        'id'            => 'town_name',
//                        'name'          => 'town_name',
//                        'value'         => set_value('club_name', @$club_detail['town_name']),
//                        'class'         => 'form-control',
//                    ]);
//                echo "</div></div>";
//                echo form_input([
//                        'name'          => 'town_id',
//                        'id'            => 'town_id',
//                        'type'          => 'hidden',
//                        'value'         => set_value('club_id', @$club_detail['town_id']),
//                    ]);
//                

                echo "<div class='form-group'>";
                echo form_label('Sponsor', 'sponsor_id');
                echo form_dropdown('sponsor_id', $sponsor_dropdown, @$club_detail['sponsor_id'], ["id"=>"sponsor_id","class"=>"form-control"]);        
                echo "</div>";
                
                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";

                echo form_close();

//                wts($club_detail);

                //<input type="submit" name="submit" value="Edit Event">
            ?>
            </div>
        </div>
    </div>
</div>