<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <div class="row">
                <div class="col-md-7">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?=$title;?></span>
                </div>
                <div class="col-md-5">
                    <form class="search-form search-form-inline" action="<?= base_url('admin/user/search/'); ?>" method="GET" style="margin-top: -9px;">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." name="u_query" value="<?= $this->input->get('u_query'); ?>">
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
    </div>
    <div class="portlet-body">
        <div class="row">

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
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/user/delete/" . $data_entry['user_id'],
                                "text" => "Delete",
                                "icon" => "icon-ban",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];
                        $edit_url = base_url('admin/user/create/edit/' . $data_entry['user_id']);
                        $row['id'] = $data_entry['user_id'];
                        $row['name'] = "<a href=" . $edit_url . " title='Edit Edition'>" . $data_entry['user_name'] . "</a>";
                        $row['surname'] = $data_entry['user_surname'];
                        $row['email'] = $data_entry['user_email'];
                        $row['roles'] = implode(", ", $data_entry['role_arr']);
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