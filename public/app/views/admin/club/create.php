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
    <?php
    if ($action == "edit") {
        // wts($club_detail);
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
                    <div class="form-group">
                        <?php
                            if ($club_detail['event_list']) {
                                echo form_label('Event Links', 'event_link');
                                echo "<ul>";
                                foreach ($club_detail['event_list'] as $event_id=>$event) {
                                    echo "<li><b>".fdateShort($event['latest_edition']['edition_date'])."</b>: <a target='_blank' href='".base_url("admin/edition/create/edit/".$event['latest_edition']['edition_id'])."'>".$event['latest_edition']['edition_name']."</a></li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p>No edition links</p>";
                            }
                        ?>
                    </div>
                    <?php
                    //  DATES Created + Updated
                    echo "<div class='form-group'>";
                    echo form_label('Date Created', 'created_date');
                    echo form_input([
                        'value' => set_value('created_date', $club_detail['created_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', $club_detail['updated_date']),
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