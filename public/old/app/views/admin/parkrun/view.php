
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Parkruns</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
                if ( ! (empty($parkrun_data)))
                {
                    // create table
                    $this->table->set_template(ftable('parkruns_table'));
                    $this->table->set_heading($heading);
                    foreach ($parkrun_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/parkrun/create/edit/".$data_entry['parkrun_id'],
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/parkrun/delete/".$data_entry['parkrun_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                        
        
                        $row['id']=$data_entry['parkrun_id'];                  
                        $row['parkrun']=$data_entry['parkrun_name'];
                        $row['status']=flableStatus($data_entry['parkrun_status']);
                        $row['town']=$data_entry['town_name'];
                        $row['area']=$data_entry['area_name'];  
                        $row['contact']=$data_entry['user_email'];                            
                        $row['actions']= fbuttonActionGroup($action_array);
                        
                        $this->table->add_row(
                                $row['id'], 
                                $row['parkrun'], 
                                $row['status'],
                                $row['town'],
                                $row['area'],
                                $row['contact'],
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
                echo fbuttonLink($create_link."/add","Add Parkrun","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

