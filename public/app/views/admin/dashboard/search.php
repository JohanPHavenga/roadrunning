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
                    $this->table->set_heading(["ID", "Edition Name", "Status", "Edition Date", "Races", "Event Name", "Actions"]);
                    foreach ($search_results as $edition_id => $data_entry) {
                        $edit_url = "/admin/edition/create/edit/" . $edition_id;
                        $action_array = [
                            [
                                "url" => $edit_url,
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/edition/copy/" . $edition_id,
                                "text" => "Copy",
                                "icon" => "icon-share-alt",
                            ],
                        ];

                        if ($data_entry['status_id'] == 2) {
                            $action_array[] = [
                                "url" => "/admin/edition/delete/" . $edition_id,
                                "text" => "Delete",
                                "icon" => "icon-ban",
                                "confirmation_text" => "<b>Are you sure?</b> <br>Note, this will also delete all races linked to this edition",
                            ];
                        }

                        $row['id'] = $edition_id;
                        $row['name'] = "<a href=" . $edit_url . " title='Edit Edition'>" . $data_entry['edition_name'] . "</a>";
                        $row['status'] = flableStatus($data_entry['status_id']);
                        $row['date'] = fdateShort($data_entry['edition_date']);
                        foreach ($data_entry['races'] as $race_id => $race) {
                            if (!isset($row['race_list'])) {
                                $row['race_list'] = "";
                            }
                            if ($race['has_results']) {
                                $status=$race['color'];
                            } else {
                                $status="default";
                            }
                            $row['race_list'] .= flable(fraceDistance($race['distance']), $status, "sm") . "&nbsp;";
                        }
                        $row['event'] = $data_entry['event_name'];
                        $row['actions'] = fbuttonActionGroup($action_array);
                        $this->table->add_row($row);
                        unset($row);
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
