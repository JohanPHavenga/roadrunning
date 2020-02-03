<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Areas</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($area_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($area_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/area/create/edit/" . $data_entry['area_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/area/delete/" . $data_entry['area_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];


                        $row['id'] = $data_entry['area_id'];
                        $row['area'] = $data_entry['area_name'];
                        $row['status'] = flableStatus($data_entry['area_status']);
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row(
                                $row['id'], $row['area'], $row['status'], $row['actions']
                        );
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link) {
                    echo fbuttonLink($create_link . "/add", "Add Area", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

