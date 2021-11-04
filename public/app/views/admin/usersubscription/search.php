<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <div class="row">
                <div class="col-md-12">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= $title; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row" style="margin-top: -5px; margin-bottom: 10px;">
            <div class="col-md-4">
                <form class="search-form search-form-inline" action="<?= base_url('admin/usersubscription/search/'); ?>" method="GET">
                    <div class="input-group">
                        <input type="text" id="u_query" class="form-control" placeholder="User Search" name="u_query" value="<?= $this->input->get('u_query'); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-primary submit">
                                <i class="icon-magnifier"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form class="search-form search-form-inline" action="<?= base_url('admin/usersubscription/search/'); ?>" method="GET" >
                    <div class="input-group">
                        <input type="text" id="e_query" class="form-control" placeholder="Edition Search" name="e_query" value="<?= $this->input->get('e_query'); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-primary submit">
                                <i class="icon-magnifier"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (@$search_results) {

                // create table
                $this->table->set_template(ftable('list_table'));
                $this->table->set_heading($heading);
                foreach ($search_results as $id => $data_entry) {
                    //                                    wts($data_entry);
                    //                                    die();
                    $action_array = [
                        [
                            "url" => "/admin/user/create/edit/" . $data_entry['user_id'],
                            "text" => "View User",
                            "icon" => "icon-eye",
                        ],
                        [
                            "url" => "/admin/usersubscription/delete/" . $data_entry['user_id'] . "/" . $data_entry['linked_to'] . "/" . $data_entry['linked_id'],
                            "text" => "Delete",
                            "icon" => "icon-close",
                            "confirmation_text" => "<b>Are you sure?</b>",
                        ],
                    ];
                    $edit_url = base_url('admin/user/create/edit/' . $data_entry['user_id']);
                    $row['id'] = $data_entry['user_id'];
                    $row['name'] = "<a href=" . $edit_url . " title='Edit Edition'>" . $data_entry['user_name'] . " " . $data_entry['user_surname'] . "</a>";
                    $row['linked_type'] = $data_entry['linked_to'];
                    $row['linked_name'] = $data_entry['linked_name'];
                    $row['actions'] = fbuttonActionGroup($action_array);
                    $this->table->add_row($row);
                }
                echo $this->table->generate();
            } else {
                echo $msg;
            }


            //                            wts($search_results);
            ?>
        </div>
    </div> <!-- row -->

</div>
<div class="portlet-footer">
    <?php
    // add button
    if (@$create_link) {
        echo fbuttonLink($create_link . "/add", "Add user", "primary");
    }
    ?>
</div>
</div>