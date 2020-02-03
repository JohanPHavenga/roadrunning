<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Date Types</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($datetype_data))) {
                    // create table
                    $this->table->set_template(ftable('datetype_table'));
                    $this->table->set_heading($heading);
                    foreach ($datetype_data as $id => $data_date) {

                        $action_array = [
                            [
                                "url" => "/admin/datetype/create/edit/" . $data_date['datetype_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/datetype/delete/" . $data_date['datetype_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_date['datetype_id'];
                        $row['datetype'] = $data_date['datetype_name'];
                        $row['display'] = $data_date['datetype_display'];
                        $row['status'] = flableStatus($data_date['datetype_status']);
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
                    echo fbuttonLink($create_link . "/add", "Add DateType", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

