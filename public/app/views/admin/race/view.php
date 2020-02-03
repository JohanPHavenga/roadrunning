
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all races</span>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (!(empty($race_data))) {
                    // create table
                    $this->table->set_template(ftable('races_table'));
                    $this->table->set_heading($heading);
                    foreach ($race_data as $id => $data_entry) {
                        $edit_url = "/admin/race/create/edit/" . $data_entry['race_id'];
                        $action_array = [
                            [
                                "url" => $edit_url,
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/race/delete/" . $data_entry['race_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['race_id'];
                        $row['edition'] = "<a href=" . $edit_url . " title='Edit Race'>" . $data_entry['edition_name']." ".fraceDistance($data_entry['race_distance']) . "</a>";
                        $row['racetype'] = $data_entry['racetype_name'];
                        $row['time'] = ftimeSort($data_entry['race_time_start']);
                        if ($data_entry['race_fee_senior_licenced']) {
                            $row['fees'] = fdisplayCurrency($data_entry['race_fee_senior_licenced']) . "/" . fdisplayCurrency($data_entry['race_fee_senior_unlicenced']);
                        } else {
                            $row['fees'] = "Not Set";
                        }
                        $row['status'] = flableStatus($data_entry['race_status']);
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row(
                                $row['id'],
                                $row['edition'],
                                $row['racetype'],
//                                array('data' => $row['distance'], 'align' => 'right'),
                                $row['time'],
                                $row['fees'],
                                $row['status'],
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
                    echo fbuttonLink($create_link . "/add", "Add Race", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

