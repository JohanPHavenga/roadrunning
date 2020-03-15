<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of Timing Providers</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($timingprovider_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($timingprovider_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/timingprovider/create/edit/" . $data_entry['timingprovider_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/timingprovider/delete/" . $data_entry['timingprovider_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['timingprovider_id'];
                        $row['timingprovider_name'] = $data_entry['timingprovider_name'];
                        $row['timingprovider_abbr'] = $data_entry['timingprovider_abbr'];
                        $row['status'] = flableStatus($data_entry['timingprovider_status']);
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
                    echo fbuttonLink($create_link . "/add", "Add Timing Provider", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

