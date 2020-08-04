
<?= form_open("/admin/result/import_confirm"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import Result Set For <span style="color: #cc0000"><?= $race_name; ?></span></span>
                </div>
            </div>

            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <?php
                    if (!(empty($import_data))) {
                        if (!isset($skip)) {
                            $skip = 0;
                        }
                        // create table
                        $this->table->set_template(ftable('result_import_table'));
                        array_unshift($columns, "#");
                        $this->table->set_heading($columns);
                        foreach ($import_data as $row_num => $data_entry) {
                            if ($row_num<$skip-2) { $data_entry=[]; } // clear stuff above the column headings
                            array_unshift($data_entry, $row_num + 1);
                            $this->table->add_row($data_entry);
                        }
//                        $this->table->add_row($columns);



                        $form_fields = [
                            form_dropdown('skip', $skip_arr, set_value('skip', $skip), ["id" => 'skip', "class" => "form-control"]),
                        ];
                        foreach ($columns as $key => $column) {

                            if ($key == 0) {
                                continue;
                            }
                            if (isset($pre_load[$column])) {
                                $val = $pre_load[$column];
                            } else {
                                $val = "";
                            }
                            $form_fields[] = form_dropdown("columns[$column]", $result_fields, set_value("columns[$column]", $val), ["id" => "columns[$column]", "class" => "form-control"]);
                        }

                        $this->table->add_row($form_fields);
                        echo $this->table->generate();
                    } else {
                        echo "<p>No data to show</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <h4 class="caption-subject font-dark bold uppercase">PREVIEW ENTRY</h4>
                    <?php
                    if (!(empty($input_data))) {
                        // remove fields not used
                        foreach ($result_fields as $field => $short_name) {
                            if (!isset($input_data[$field])) {
                                unset($result_fields[$field]);
                            }
                        }

                        $this->table->set_template(ftable('preview_import_table'));
                        $this->table->set_heading(array_keys($result_fields));
                        foreach ($result_fields as $result_field => $short_name) {
                            $data_row[$result_field] = $input_data[$result_field];
                        }
                        $this->table->add_row($data_row);
                        echo $this->table->generate();
                    } else {
                        echo "<p>No data to show</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <div class='btn-group' style="float: right">
                        <?= fbutton($text = "Reload Data", $type = "submit", $status = "info", $size = NULL, $name = "reload", $value = "preview"); ?>
                        <?= fbutton($text = "Upload Results", $type = "submit", $status = "danger", $size = NULL, $name = "upload"); ?>
                        <?= fbuttonLink($cancel_url, "Cancel", $status = "warning"); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= form_close(); ?>
<?php
//wts($import_data);
//wts($columns);
//wts($input_data);
//wts($pre_load);
//wts($this->input->post());
//wts($_SESSION['import']['race_id']);
//wts($_SESSION['import']['race']);
//wts($result_fields);
