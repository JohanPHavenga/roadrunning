
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Users</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
//    wts($user_data);
                if ( ! (empty($user_data)))
                {
                    // create table
                    $this->table->set_template(ftable('users_table'));
                    $this->table->set_heading($heading);
                    foreach ($user_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/user/create/edit/".$data_entry['user_id'],
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/user/delete/".$data_entry['user_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                        
        
                        // $row['id']=$data_entry['user_id'];                  
                        $row['user_name']=$data_entry['user_name'];
                        $row['user_surname']=$data_entry['user_surname'];
                        $row['user_email']=$data_entry['user_email'];
                        $row['last_login_date']=$data_entry['lastlogin_date'];
                        $row['last_login_from']=$data_entry['lastlogin_from'];
                        $row['actions']= fbuttonActionGroup($action_array);
                        
                        $this->table->add_row(
                                // $row['id'], 
                                $row['last_login_date'],
                                $row['user_name']." ". $row['user_surname'], 
                                $row['user_email'],
                                $row['last_login_from'],
                                $row['actions']
                                );
//                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();

                }
                else
                {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link)
                {
                echo fbuttonLink($create_link."/add","Add User","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

