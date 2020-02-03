<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-magnifier"></i> 
                    <span class="caption-subject font-dark bold uppercase">Search Results</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (@$search_results) {
                    $this->table->set_template(ftable('search_table'));
                    $this->table->set_heading($heading);
                    foreach ($search_results as $year => $year_list) {
                        foreach ($year_list as $month => $month_list) {
                            foreach ($month_list as $day => $edition_list) {
                                foreach ($edition_list as $id => $data_entry) {
                                    $edit_url = "/admin/edition/create/edit/" . $data_entry['edition_id'];
                                    $action_array = [
                                        [
                                            "url" => $edit_url,
                                            "text" => "Edit",
                                            "icon" => "icon-pencil",
                                        ],
                                        [
                                            "url" => "/admin/edition/copy/" . $data_entry['edition_id'],
                                            "text" => "Copy",
                                            "icon" => "icon-share-alt",
                                        ],
                                    ];

                                    if ($data_entry['edition_status'] == 2) {
                                        $action_array[] = [
                                            "url" => "/admin/edition/delete/" . $data_entry['edition_id'],
                                            "text" => "Delete",
                                            "icon" => "icon-ban",
                                            "confirmation_text" => "<b>Are you sure?</b> <br>Note, this will also delete all races linked to this edition",
                                        ];
                                    }

                                    $row['id'] = $data_entry['edition_id'];
                                    $row['name'] = "<a href=" . $edit_url . " title='Edit Edition'>" . $data_entry['edition_name'] . "</a>";
                                    $row['status'] = flableStatus($data_entry['edition_status']);
                                    $row['date'] = $data_entry['edition_date'];
                                    $row['event'] = $data_entry['event_name'];
                                    $row['actions'] = fbuttonActionGroup($action_array);
                                    $this->table->add_row($row);
                                    unset($row);
                                }
                            }
                        }
                    }
                    echo $this->table->generate();
                } else {
                    echo $msg;
                }
                ?>

            </div>
        </div>
    </div>
</div>
