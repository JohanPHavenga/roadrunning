
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all clubs</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
                if ( ! (empty($club_data)))
                {
                    // create table
                    $this->table->set_template(ftable('clubs_table'));
                    $this->table->set_heading($heading);
                    foreach ($club_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/club/create/edit/".$data_entry['club_id'],
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/club/delete/".$data_entry['club_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                        
        
                        $row['id']=$data_entry['club_id'];                  
                        $row['club']=$data_entry['club_name'];
                        $row['status']=flableStatus($data_entry['club_status']);
                        $row['town']=$data_entry['town_name'];
                        $row['province']=$data_entry['province_name'];
                        $row['sponsor']=$data_entry['sponsor_name'];                        
                        $row['actions']= fbuttonActionGroup($action_array);
                        
                        $this->table->add_row(
                                $row['id'], 
                                $row['club'], 
                                $row['status'],
                                $row['town'],
                                $row['province'],
                                $row['sponsor'],
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
                echo fbuttonLink($create_link."/add","Add Club","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

