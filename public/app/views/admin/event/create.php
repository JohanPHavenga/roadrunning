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

                //  NAME
                echo "<div class='form-group'>";
                echo form_label('Name', 'event_name');
                echo form_input([
                        'name'          => 'event_name',
                        'id'            => 'event_name',
                        'value'         => set_value('event_name', @$event_detail['event_name'], false),
                        'class'         => 'form-control',
                    ]);

                echo "</div>";

                //  STATUS
                echo "<div class='form-group'>";
                echo form_label('Status', 'event_status');
                echo form_dropdown('event_status', $status_dropdown, @$event_detail['event_status'], ["id"=>"event_status","class"=>"form-control"]);        
                echo "</div>";
                
                //  TOWN
                echo "<div class='form-group'>";
                echo form_label('Town <span class="compulsary">*</span>', 'user_id');
                echo form_dropdown('town_id', $town_dropdown, @$event_detail['town_id'], ["id" => "town_id", "class" => "form-control"]);
                echo "</div>";
                
                //  TOWN
//                echo "<div class='form-group'>";
//                echo form_label('Town', 'town_name');
//                echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span>';
//                echo '<span class="twitter-typeahead" style="position: relative; display: inline-block;">';
//                echo form_input([
//                        'id'            => 'town_name',
//                        'name'          => 'town_name',
//                        'value'         => set_value('club_name', @$event_detail['town_name']),
//                        'class'         => 'form-control',
//                    ]);
//                echo form_input([
//                        'name'          => 'town_id',
//                        'id'            => 'town_id',
//                        'type'          => 'hidden',
//                        'value'         => set_value('club_name', @$event_detail['town_id']),
//                    ]);
//                echo "</span></div></div>";
                
                //  CLUB
                echo "<div class='form-group'>";
                echo form_label('Organising Club', 'club_id');
                echo form_dropdown('club_id', $club_dropdown, @$event_detail['club_id'], ["id"=>"club_id","class"=>"form-control"]);        
                echo "</div>";
                
                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";

                echo form_close();

            ?>
            </div>
        </div>
    </div>
    
    <?php
    if ($action=="edit") {
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
                        'value'         => set_value('created_date', @$event_detail['created_date']),
                        'class'         => 'form-control input-medium',
                        'disabled'      => ''
                    ]);

                echo "</div>";
                echo "<div class='form-group'>";
                echo form_label('Date Updated', 'updated_date');
                echo form_input([
                        'value'         => set_value('updated_date', @$event_detail['updated_date']),
                        'class'         => 'form-control input-medium',
                        'disabled'      => ''
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
