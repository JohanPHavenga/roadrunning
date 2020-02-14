<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import Event Info for <span style="color: #cc0000"><?= $import_data[0]['asamember_name']; ?></span></span>
                </div>
            </div>

            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <?php
                    if (!(empty($import_data))) {
                        // create table
                        $this->table->set_template(ftable('event_import_table'));
                        $this->table->set_heading($columns);
                        foreach ($import_data as $row_num => $data_entry) {
                            $this->table->add_row($data_entry);
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
</div>
