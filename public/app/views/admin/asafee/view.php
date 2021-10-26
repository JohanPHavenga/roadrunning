
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all ASA Licence Fees</span>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (!(empty($asafee_data))) {
                    // create table
                    $this->table->set_template(ftable('asa_fee_table'));
                    $this->table->set_heading($heading);
                    foreach ($asafee_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/asafee/create/edit/" . $data_entry['asa_fee_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/asafee/copy/" . $data_entry['asa_fee_id'],
                                "text" => "Copy",
                                "icon" => "icon-share-alt",
                            ],
                            [
                                "url" => "/admin/asafee/delete/" . $data_entry['asa_fee_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];


                        $row['id'] = $data_entry['asa_fee_id'];
                        $row['asa_member'] = $data_entry['asa_member_abbr'];
                        $row['year'] = $data_entry['asa_fee_year'];
                        $row['from'] = $data_entry['asa_fee_distance_from'];
                        $row['to'] = $data_entry['asa_fee_distance_to'];
                        $row['snr'] = $data_entry['asa_fee_snr'];
                        $row['jnr'] = $data_entry['asa_fee_jnr'];
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row(
                                $row['id'],
                                $row['asa_member'],
                                $row['year'],
                                fraceDistance($row['from'],2),
                                fraceDistance($row['to'],2),
                                fdisplayCurrency($row['snr'],2),
                                fdisplayCurrency($row['jnr'],2),
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
                    echo fbuttonLink($create_link . "/add", "Add ASA Licence Fee", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

