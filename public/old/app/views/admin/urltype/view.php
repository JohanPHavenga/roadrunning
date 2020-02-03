<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all URL Types</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($urltype_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($urltype_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/urltype/create/edit/" . $data_entry['urltype_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/urltype/delete/" . $data_entry['urltype_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['urltype_id'];
                        $row['urltype'] = $data_entry['urltype_name'];
                        $row['help'] = $data_entry['urltype_helptext'];
                        $row['button'] = $data_entry['urltype_buttontext'];
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link) {
                    echo fbuttonLink($create_link . "/add", "Add URL Type", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

