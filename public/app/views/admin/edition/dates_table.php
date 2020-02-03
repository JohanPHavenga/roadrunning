<!-- DATES -->
<div class="portlet light" id="dates">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold">DATES</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only", "dates"); ?>
            <?= fbuttonLink("/admin/date/create/add/" . $edition_detail['edition_id'] . "/edition", "Add Date", "info"); ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php
        if (!(empty($date_list))) {
            // create table
            $this->table->set_template(ftable('edition_dates_table'));
            $this->table->set_heading(["ID", "Date", "Date Type", "Actions"]);
            foreach ($date_list as $id => $data_entry) {

                $action_array = [
                    [
                        "url" => "/admin/date/create/edit/" . $data_entry['date_id'],
                        "text" => "Edit",
                        "icon" => "icon-pencil",
                    ],
                    [
                        "url" => "/admin/date/delete/" . $data_entry['date_id'],
                        "text" => "Delete",
                        "icon" => "icon-dislike",
                        "confirmation_text" => "<b>Are you sure?</b>",
                    ],
                ];


                $row['id'] = $data_entry['date_id'];
                $row['date_start'] = $data_entry['date_start'];
                $row['date_end'] = $data_entry['date_end'];
                $row['datetype'] = $data_entry['datetype_name'];
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