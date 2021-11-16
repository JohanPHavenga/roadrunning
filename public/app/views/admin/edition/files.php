<div class="portlet light" id="file_list">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">Files</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only", "file_list"); ?>
            <?= fbuttonLink("/admin/file/create/add/" . $edition_detail['edition_id'] . "/edition", "Add File", "info"); ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php
        if (!(empty($file_list))) {
            // create table
            $this->table->set_template(ftable('edition_file_table'));
            $this->table->set_heading(["ID", "File Name", "File Type", "Actions"]);
            foreach ($file_list as $id => $data_entry) {

                $action_array = [
                    [
                        "url" => "/admin/file/delete/" . $data_entry['file_id'],
                        "text" => "Delete",
                        "icon" => "icon-dislike",
                        "confirmation_text" => "<b>Are you sure?</b>",
                    ],
                ];

                $file_id = my_encrypt($data_entry['file_id']);
                $file_url = base_url("file/edition/" . $edition_detail['edition_slug'] . "/".strtolower($data_entry['filetype_name'])."/" . $data_entry['file_name']);

                $row['id'] = $data_entry['file_id'];
                $row['file_name'] = "<a href='$file_url' target='_blank'>" . $data_entry['file_name'] . "</a>";
                $row['filetype'] = $data_entry['filetype_name'];
                $row['actions'] = fbuttonActionGroup($action_array);

                $this->table->add_row($row);
                unset($row);
            }
            echo $this->table->generate();
        } else {
            echo "<p>No Files loaded for the edition</p>";
        }
        ?>
    </div>
</div>

