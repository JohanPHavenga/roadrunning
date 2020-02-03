<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Roles</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($role_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($role_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/role/create/edit/" . $data_entry['role_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/role/delete/" . $data_entry['role_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['role_id'];
                        $row['role'] = $data_entry['role_name'];
                        $row['status'] = flableStatus($data_entry['role_status']);
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row(
                                $row['id'], $row['role'], $row['status'], $row['actions']
                        );
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link) {
                    echo fbuttonLink($create_link . "/add", "Add Role", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

