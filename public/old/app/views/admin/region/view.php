<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Regions</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($region_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($region_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/region/create/edit/" . $data_entry['region_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/region/delete/" . $data_entry['region_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];


                        $row['id'] = $data_entry['region_id'];
                        $row['region'] = $data_entry['region_name'];
                        $row['slug'] = $data_entry['region_slug'];
                        $row['province'] = $data_entry['province_name'];
                        $row['status'] = flableStatus($data_entry['region_status']);
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
                    echo fbuttonLink($create_link . "/add", "Add Region", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

