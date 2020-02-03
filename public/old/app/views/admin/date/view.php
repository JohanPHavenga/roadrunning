
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Dates</span>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (!(empty($date_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($date_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/date/create/edit/" . $data_entry['date_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/date/delete/" . $data_entry['date_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['date_id'];
                        $row['start'] = $data_entry['date_start'];
                        $row['end'] = $data_entry['date_end'];
                        $row['datetype'] = $data_entry['datetype_name'];
                        $row['date_linked_to'] = $data_entry['date_linked_to'];
                        $row['linked_id'] = $data_entry['linked_id'];
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
                    echo fbuttonLink($create_link . "/add", "Add Date", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

<?php
//wts($date_data);
?>

