<div class="portlet light" id="url_list">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold">URLs</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only", "url_list"); ?>
            <?= fbuttonLink("/admin/url/create/add/" . $edition_detail['edition_id'] . "/edition", "Add URL", "info"); ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php
        if (!(empty($url_list))) {
            // create table
            $this->table->set_template(ftable('edition_url_table'));
            $this->table->set_heading(["ID", "URL", "URL Type", "Actions"]);
            foreach ($url_list as $id => $data_entry) {

                $action_array = [
                    [
                        "url" => "/admin/url/create/edit/" . $data_entry['url_id'],
                        "text" => "Edit",
                        "icon" => "icon-pencil",
                    ],
                    [
                        "url" => "/admin/url/delete/" . $data_entry['url_id'],
                        "text" => "Delete",
                        "icon" => "icon-dislike",
                        "confirmation_text" => "<b>Are you sure?</b>",
                    ],
                ];


                $row['id'] = $data_entry['url_id'];
                $row['url_name'] = $data_entry['url_name'];
                $row['urltype'] = $data_entry['urltype_name'];
                $row['actions'] = fbuttonActionGroup($action_array);

                $this->table->add_row($row);
                unset($row);
            }
            echo $this->table->generate();
        } else {
            echo "<p>No URLs loaded for the edition</p>";
        }
        ?>
    </div>
</div>

