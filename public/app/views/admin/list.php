<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-yellow-casablanca"></i>
                    <span class="caption-subject bold font-yellow-casablanca uppercase">List Data</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if ( ! (empty($list)))
                {
                    // pagination links
//                    echo fpagination(@$pagination);
//
//                    echo '<div class="table-scrollable">';
                    // create table
                    $this->table->set_template(ftable("list_table"));
                    $this->table->set_heading($heading);
                    foreach ($list as $id=>$data) {
                        if (@$create_link)
                        {
                            $crypt=base64_encode($this->encryption->encrypt($delete_arr['controller']."|".$delete_arr['id_field']."|".$id));
//                            $actions_array=["Edit"=>$create_link."/edit/".$id,"Delete"=>"/admin/delete/".$crypt];
                            $action_array=[
                                [
                                "url"=>$create_link."/edit/".$id,
                                "text"=>"Edit",
                                "icon"=>"icon-pencil",
                                ],
                                [
                                "url"=>"/admin/delete/".$crypt,
                                "text"=>"Delete",
                                "icon"=>"icon-dislike",
                                "confirmation_text"=>"<b>Are you sure?</b>",
                                ],
                            ];
                            $data[]=fbuttonActionGroup($action_array);
                        }
                        $this->table->add_row($data);
                    }
                    echo $this->table->generate();
//                    echo "</div>";

                    // pagination links
//                    echo fpagination(@$pagination);

                }
                else
                {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link)
                {
                echo fbuttonLink($create_link."/add","Add Entry","primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>
