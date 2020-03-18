<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase">Email template email list</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($emailtemplate_data))) {
                    // create table
                    $this->table->set_template(ftable('emailtemplates_table'));
                    $this->table->set_heading($heading);
                    foreach ($emailtemplate_data as $data_entry) {
                        $action_array = [
                            [
                                "url" => "/admin/emailtemplate/create/edit/" . $data_entry['emailtemplate_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/emailtemplate/copy/" . $data_entry['emailtemplate_id'],
                                "text" => "Copy",
                                "icon" => "icon-share-alt",
                            ],
                            [
                                "url" => "/admin/emailtemplate/delete/" . $data_entry['emailtemplate_id'],
                                "text" => "Delete",
                                "icon" => "icon-close",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];
                        $row['id'] = $data_entry['emailtemplate_id'];
                        $row['name'] = "<a href='/admin/emailtemplate/create/edit/" . $data_entry['emailtemplate_id'] . "'>" . $data_entry['emailtemplate_name'] . "</a>";
                        $row['linked_to'] = $data_entry['emailtemplate_linked_to'];
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
                    echo fbuttonLink($create_link . "/add", "Create Template", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

