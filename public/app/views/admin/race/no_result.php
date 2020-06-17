
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of races without results</span>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (!(empty($race_data))) {
                    // create table
                    $this->table->set_template(ftable('races_table'));
                    $heading = ["Date", "Edition", "Distance", "ASA", "Race Type", "Actions"];
                    $this->table->set_heading($heading);
                    foreach ($race_data as $id => $data_entry) {
                        $edit_url = "/admin/edition/create/edit/" . $data_entry['edition_id'];
                        $action_array = [
                            [
                                "url" => $edit_url,
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                        ];

                        $this->table->add_row(
                                fdateShort($data_entry['edition_date']),
                                "<a href=" . $edit_url . " title='Edit Race'>" . $data_entry['edition_name'],
                                "<span class='badge badge-".$data_entry['color']."'>" . fraceDistance(round($data_entry['race_distance'])) . "</span>",
                                $data_entry['asa_member_abbr'],
                                $data_entry['racetype_name'],
                                fbuttonActionGroup($action_array)
                        );
//                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }
                ?>

            </div>
        </div>
    </div>
</div>

