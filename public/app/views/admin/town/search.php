
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <div class="row">
                <div class="col-md-6">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Search Results</span>
                </div>
                <div class="col-md-6">
                    <form class="search-form search-form-expanded" action="<?= base_url('admin/town/search/'); ?>" method="GET">
                        <div class="input-group ">
                            <input type="text" class="form-control" placeholder="Search..." name="t_query" autofocus onfocus="this.select();" value="<?= $this->input->get('t_query'); ?>">
                            <span class="input-group-btn">
                                <a href="javascript:;" class="btn submit">
                                    <i class="icon-magnifier"></i>
                                </a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="portlet-body">
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
                                "url" => "/admin/town/create/edit/" . $data_entry['town_id'],
                                "text" => "Edit",
                                "icon" => "icon-pencil",
                            ],
                            [
                                "url" => "/admin/town/delete/" . $data_entry['town_id'],
                                "text" => "Delete",
                                "icon" => "icon-ban",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];

                        $row['id'] = $data_entry['town_id'];
                        $row['name'] = $data_entry['town_name'];
                        $row['province'] = $data_entry['province_name'];
                        $row['region'] = $data_entry['region_name'];
                        $row['area'] = $data_entry['area_name'];
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
            echo fbuttonLink($create_link . "/add", "Add Town", "primary");
        }
        ?>
    </div>
</div>
