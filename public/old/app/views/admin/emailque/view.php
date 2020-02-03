<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> <?= $emailque_status; ?> email list</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($emailque_data))) {
                    // create table
                    $this->table->set_template(ftable('emailques_table'));
                    $this->table->set_heading($heading);
                    foreach ($emailque_data as $id => $data_entry) {                        
                        $row['id'] = $data_entry['emailque_id'];
                        $row['subject'] = $data_entry['emailque_subject'];
                        $row['to_address'] = $data_entry['emailque_to_address'];
                        $row['to_name'] = $data_entry['emailque_to_name'];
                        $row['updated'] = fdateLong($data_entry['updated_date']);
                        $row['actions'] = fbuttonActionGroup($action_array[$id]);

                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link) {
                    echo fbuttonLink($create_link . "/add", "Compose Email", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

