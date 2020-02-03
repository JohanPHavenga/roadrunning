
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all ASA Regulations</span>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (!(empty($asareg_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($asareg_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/asareg/create/edit/" . $data_entry['asa_reg_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/asareg/delete/" . $data_entry['asa_reg_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];


                        $row['id'] = $data_entry['asa_reg_id'];
                        $row['iaaf'] = $data_entry['asa_reg_iaaf'];
                        $row['name'] = $data_entry['asa_reg_distance_name'];
                        $row['from'] = $data_entry['asa_reg_distance_from'];
                        $row['to'] = $data_entry['asa_reg_distance_to'];
                        $row['min_age'] = $data_entry['asa_reg_minimum_age'];
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row(
                                $row['id'],
                                fnumber($row['iaaf'],2),
                                $row['name'],
                                fnumber($row['from'],2),
                                fnumber($row['to'],2),
                                $row['min_age'],
                                $row['actions']
                        );
//                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link) {
                    echo fbuttonLink($create_link . "/add", "Add ASA Regulation", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

