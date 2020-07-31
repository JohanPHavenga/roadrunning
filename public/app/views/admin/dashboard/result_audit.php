
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">                    
                    <span class="caption-subject uppercase bold">
                        <i class="icon-ghost"></i> Editions without results
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <p>Editions between <b><?= $from_date; ?></b> and <b><?= $to_date; ?></b> with no entries in the 
                    results table, but with a information status of <b><?= $info_status_name; ?></b></p>
                <?php
                if ($edition_info) {
                    $this->table->set_template(ftable('result_audit_table'));
                    $this->table->set_heading(["Date", "Edition Name", "Info Status", "ASA", "Copy"]);
                    foreach ($edition_info as $edition_id => $edition) {
                        $row['date'] = fdateShort($edition['edition_date']);
                        $row['name'] = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                        $row['info_status'] = $status_list[$edition['edition_info_status']];
                        $row['asa'] = $edition['asa_member_abbr'];
                        $row['edit'] = "<a href='" . base_url("admin/edition/edit/" . $edition['edition_id']) . "' class='btn btn-xs default'>Edit</a>";

                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No data to show</p>";
                }
                // create table
                ?>
            </div>
        </div>
    </div>
</div>

<?php
//wts($missing_editions);
?>            
