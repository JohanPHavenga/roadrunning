<div class="portlet light" id="comment_list">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">Comments</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only", "comment_list"); ?>
            <?= fbuttonLink("/admin/comment/create/add/" . $edition_detail['edition_id'], "Add Comment", "info"); ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php
        if (!(empty($comment_list))) {
            // create table
            $this->table->set_template(ftable('edition_comment_table'));
            $this->table->set_heading(["ID", "Comment", "Actions"]);
            foreach ($comment_list as $id => $data_entry) {

                $action_array = [
                    [
                        "url" => "/admin/comment/create/edit/" . $data_entry['edition_id'] . "/" . $data_entry['comment_id'],
                        "text" => "Edit",
                        "icon" => "icon-pencil",
                    ],
                    [
                        "url" => "/admin/comment/delete/" . $data_entry['edition_id'] . "/" . $data_entry['comment_id'],
                        "text" => "Delete",
                        "icon" => "icon-dislike",
                        "confirmation_text" => "<b>Are you sure?</b>",
                    ],
                ];

                if ($data_entry['updated_date']) {
                    $row['date'] = $data_entry['updated_date'];
                } else {
                    $row['date'] = $data_entry['created_date'];
                }
                $row['comment_data'] = $data_entry['comment_data'];
                $row['actions'] = fbuttonActionGroup($action_array);

                $this->table->add_row($row);
                unset($row);
            }
            echo $this->table->generate();
        } else {
            echo "<p>No Comments loaded for the edition</p>";
        }
        ?>
    </div>
</div>