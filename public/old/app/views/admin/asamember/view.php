
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all ASA Members</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
                if ( ! (empty($asamember_data)))
                {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($asamember_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/asamember/create/edit/".$data_entry['asa_member_id'],
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/asamember/delete/".$data_entry['asa_member_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                        
        
                        $row['id']=$data_entry['asa_member_id'];                  
                        $row['asamember']=$data_entry['asa_member_name'];     
                        $row['abbreviation']=$data_entry['asa_member_abbr'];
                        $row['url']=$data_entry['asa_member_url'];
                        $row['status']=flableStatus($data_entry['asa_member_status']);      
                        $row['actions']= fbuttonActionGroup($action_array);
                        
                        $this->table->add_row(
                                $row['id'], 
                                $row['asamember'], 
                                $row['abbreviation'], 
                                $row['url'],
                                $row['status'],
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
                echo fbuttonLink($create_link."/add","Add ASA Member","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

