<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Provinces</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($province_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($province_data as $id => $data_entry) {

                        $row['id'] = $data_entry['province_id'];
                        $row['province'] = $data_entry['province_name'];
                        $row['slug'] = $data_entry['province_slug'];

                        $this->table->add_row($row);
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

