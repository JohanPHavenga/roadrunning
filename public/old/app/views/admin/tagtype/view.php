<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase">List of all TagTypes</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($tagtype_data))) {
                    // create table
                    $this->table->set_template(ftable('tagtype_table'));
                    $this->table->set_heading($heading);
                    foreach ($tagtype_data as $id => $data_date) {

                        $action_array = [
                            [
                                "url" => "/admin/tagtype/create/edit/" . $data_date['tagtype_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/tagtype/delete/" . $data_date['tagtype_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_date['tagtype_id'];
                        $row['tagtype'] = $data_date['tagtype_name'];
                        $row['status'] = flableStatus($data_date['tagtype_status']);
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
                    echo fbuttonLink($create_link . "/add", "Add Tagtype", "primary");
                }
                ?>
            </div>
        </div>
    </div>
</div>

