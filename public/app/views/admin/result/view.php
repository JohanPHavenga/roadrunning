
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Results</span>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (!(empty($result_data))) {
                    // create table
                    $this->table->set_template(ftable('result_table'));
                    $this->table->set_heading($heading);
                    foreach ($result_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/result/create/edit/" . $data_entry['result_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/result/delete/" . $data_entry['result_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['pos'] = $data_entry['result_pos'];
                        $row['race'] = $data_entry['race_name'];
                        $row['surname'] = $data_entry['result_surname'];
                        $row['name'] = $data_entry['result_name'];
                        $row['club'] = $data_entry['result_club'];
                        $row['time'] = ftimeSort($data_entry['result_time'], true);
                        $row['actions'] = fbuttonActionGroup($action_array);
                        $this->table->add_row($row);

                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
//                echo fbuttonLink($create_link . "/add", "Manually Add Result", "primary");
                ?>

            </div>
        </div>
    </div>
</div>
<?php
//wts($result_data);

