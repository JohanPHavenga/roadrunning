
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all sponsors</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
                if ( ! (empty($sponsor_data)))
                {
                    // create table
                    $this->table->set_template(ftable('sponsors_table'));
                    $this->table->set_heading($heading);
                    foreach ($sponsor_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/sponsor/create/edit/".$data_entry['sponsor_id'],
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/sponsor/delete/".$data_entry['sponsor_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                        
        
                        $row['id']=$data_entry['sponsor_id'];                  
                        $row['sponsor']=$data_entry['sponsor_name'];
                        $row['status']=flableStatus($data_entry['sponsor_status']);      
                        $row['actions']= fbuttonActionGroup($action_array);
                        
                        $this->table->add_row(
                                $row['id'], 
                                $row['sponsor'], 
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
                echo fbuttonLink($create_link."/add","Add Sponsor","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

