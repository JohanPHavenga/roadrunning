<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all racetypes</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($racetype_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($racetype_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/racetype/create/edit/" . $data_entry['racetype_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/racetype/delete/" . $data_entry['racetype_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['racetype_id'];
                        $row['racetype'] = $data_entry['racetype_name'];
                        $row['abbr'] = $data_entry['racetype_abbr'];
//                        $row['color'] = $data_entry['racetype_color'];
                        $row['icon'] = '<i class="fa fa-'.$data_entry['racetype_icon'].'">';
                        $row['status'] = flableStatus($data_entry['racetype_status']);
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
                    echo fbuttonLink($create_link . "/add", "Add RaceType", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

