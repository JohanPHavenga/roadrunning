<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all File Types</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($filetype_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($filetype_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/filetype/create/edit/" . $data_entry['filetype_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/filetype/delete/" . $data_entry['filetype_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['filetype_id'];
                        $row['filetype'] = $data_entry['filetype_name'];
                        $row['help'] = $data_entry['filetype_helptext'];
                        $row['button'] = $data_entry['filetype_buttontext'];
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
                    echo fbuttonLink($create_link . "/add", "Add FileType", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

