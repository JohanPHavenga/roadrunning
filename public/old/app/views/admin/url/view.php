
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all URLs</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
                if ( ! (empty($url_data)))
                {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($url_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/url/create/edit/".$data_entry['url_id'],
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/url/delete/".$data_entry['url_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                        
                        $row['id']=$data_entry['url_id'];                  
                        $row['url']=$data_entry['url_name'];  
                        $row['urltype']=$data_entry['urltype_name'];     
                        $row['url_linked_to']=$data_entry['url_linked_to'];
                        $row['linked_id']=$data_entry['linked_id'];
                        $row['actions']= fbuttonActionGroup($action_array);
                      
                        $this->table->add_row($row);
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
                echo fbuttonLink($create_link."/add","Add URL","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

<?php
//wts($url_data);
?>

