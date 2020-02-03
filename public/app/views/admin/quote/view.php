<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> List of all Quotes</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($quote_data))) {
                    // create table
                    $this->table->set_template(ftable('list_table'));
                    $this->table->set_heading($heading);
                    foreach ($quote_data as $id => $data_entry) {

                        $action_array = [
                            [
                                "url" => "/admin/quote/create/edit/" . $data_entry['quote_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/quote/delete/" . $data_entry['quote_id'],
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['quote_id'];
                        $row['quote'] = $data_entry['quote_quote'];
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row(
                                $row['id'], $row['quote'], $row['actions']
                        );
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }

                // add button
                if (@$create_link) {
                    echo fbuttonLink($create_link . "/add", "Add Quote", "primary");
                }
                ?>

            </div>
        </div>
    </div>
</div>

