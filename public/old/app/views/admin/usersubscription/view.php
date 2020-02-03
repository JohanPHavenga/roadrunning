
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-present"></i>
                    <span class="caption-subject bold uppercase"> List of all User Subscriptions</span>
                </div>
            </div>
            <div class="portlet-body">

<?php
                if ( ! (empty($usersubscription_data)))
                {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($usersubscription_data as $id=>$data_entry) {
                        
                        $action_array=[
                                [
                                "url"=>"/admin/usersubscription/delete/".$data_entry['user_id']."/".$data_entry['linked_to']."/".$data_entry['linked_id'],
                                "text"=>"Delete",
                                "icon"=>"icon-close",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                                     
                        $row['user']=$data_entry['user_id']; 
                        $row['user_name']=$data_entry['user_name']." ".$data_entry['user_surname'];  
                        $row['linked_to']=$data_entry['linked_to'];
                        
                        switch ($data_entry['linked_to']) {
                            case "edition":
                                $id_value=$edition_list[$data_entry['linked_id']];
                                break;
                            case "newsletter":
                                $id_value=$newsletter_list[$data_entry['linked_id']];
                                break;
                            default:
                                $id_value=$data_entry['linked_id'];
                                break;
                        }
                        $row['linked_id']=$id_value;
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
                echo fbuttonLink($create_link."/add","Add Subscription","primary");
                }
                ?>
            
            </div>
        </div>
    </div>
</div>

<?php
//wts($url_data);
?>

