<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase">Mail merge list</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($emailmerge_data))) {
                    // create tables
                    $this->table->set_template(ftable('emailmerges_table'));
                    $this->table->set_heading($heading);
                    foreach ($emailmerge_data as $data_entry) {
                        switch ($data_entry['emailmerge_status']) {
                            case 4: // draft
                                $text="Edit";
                                $icon="pencil";
                                break;
                            case 8: // completed
                                $text="View";
                                $icon="eye";
                                break;
                        }
                        $action_array = [
                            [
                                "url" => "/admin/emailmerge/create/edit/" . $data_entry['emailmerge_id'],
                                "text" => $text,
                                "icon" => "icon-".$icon,
                            ],
                            [
                                "url" => "/admin/emailmerge/delete/" . $data_entry['emailmerge_id'],
                                "text" => "Delete",
                                "icon" => "icon-close",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['emailmerge_id'];
                        $row['subject'] = $data_entry['emailmerge_subject'];
                        $row['status'] = flableStatus($data_entry['emailmerge_status']);
                        $row['recipient_count'] = $data_entry['count'];
                        $row['updated'] = $data_entry['updated_date'];
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
                    echo fbuttonLink("/admin/emailmerge/wizard", "Create Merge", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

<?php
//wts($emailmerge_data);
?>
