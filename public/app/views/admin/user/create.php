<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action);?> User</span>
                </div>
            </div>
            <div class="portlet-body">
            <?php 
                echo form_open($form_url); 

                echo "<div class='form-group'>";
                echo form_label('Name', 'user_name');
                echo form_input([
                        'name'          => 'user_name',
                        'id'            => 'user_name',
                        'value'         => set_value('user_name', @$user_detail['user_name']),
                        'class'         => 'form-control',
                        'required'      => '',
                    ]);

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Surname', 'user_surname');
                echo form_input([
                        'name'          => 'user_surname',
                        'id'            => 'user_surname',
                        'value'         => set_value('user_surname', @$user_detail['user_surname']),
                        'class'         => 'form-control',
                        'required'      => '',
                    ]);
                echo "</div>";
                
                echo "<div class='form-group'>";
                echo form_label('Email', 'user_email');
                echo '<div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>';
                echo form_input([
                        'name'          => 'user_email',
                        'id'            => 'user_email',
                        'value'         => set_value('user_email', @$user_detail['user_email']),
                        'class'         => 'form-control',
                    ]);
                echo "</span></div></div>";
                
                echo "<div class='form-group'>";
                echo form_label('Phone', 'user_contact');
                echo form_input([
                        'name'          => 'user_contact',
                        'id'            => 'user_contact',
                        'value'         => set_value('user_contact', @$user_detail['user_contact']),
                        'class'         => 'form-control input-small',
                    ]);
                echo "</div>";

//                echo "<div class='form-group'>";
//                echo form_label('Username', 'user_username');
//                echo form_input([
//                        'name'          => 'user_username',
//                        'id'            => 'user_username',
//                        'value'         => set_value('user_username', @$user_detail['user_username']),
//                        'class'         => 'form-control',
//                    ]);
//                echo "</div>";
//
//                echo "<div class='form-group'>";
//                echo form_label('Password', 'user_password');
//                echo form_input([
//                        'name'          => 'user_password',
//                        'id'            => 'user_password',
//                        'value'         => set_value('user_password', @$user_detail['user_password']),
//                        'class'         => 'form-control',
//                        'type'          => 'password',
//                    ]);
//
//                echo "</div>";


                echo "<div class='form-group'>";
                echo form_label('Role', 'role_id');
//                echo form_multiselect('role_id[]', $role_dropdown, @$user_detail['role_id'], ["id"=>"role_id","class"=>"form-control", "size"=>"2"]);      
                echo form_multiselect('role_id[]', $role_dropdown, @$user_detail['role_id'], ["id"=>"role_id","class"=>"form-control", "size"=>"3"]);        
                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Club', 'club_id');
                echo form_dropdown('club_id', $club_dropdown, @$user_detail['club_id'], ["id"=>"club_id","class"=>"form-control"]);        
                echo "</div>";

                                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";
                echo "</div>";

                echo form_close();

            //    wts($user_detail);
            ?>
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
                    <div class="form-group">
                        <?php
                            if ($edition_links) {
                                echo form_label('Edition Links', 'edition_links');
                                echo "<ul>";
                                foreach ($edition_links as $edition) {
                                    echo "<li><b>".fdateShort($edition['edition_date'])."</b>: <a target='_blank' href='".base_url("admin/edition/create/edit/".$edition['edition_id'])."'>".$edition['edition_name']."</a></li>";
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
                        'value' => set_value('created_date', $user_detail['created_date']),
                        'class' => 'form-control input-medium',
                        'disabled' => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', $user_detail['updated_date']),
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