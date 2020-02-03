
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bulb"></i>
                    <span class="bold"> Events in <?= $year-1;?> with no event is <?=$year;?></span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if ($missing_editions) {
                    $this->table->set_template(ftable('editions_missing_table'));
                    $this->table->set_heading(["Date","Edition Name","Event Name"]);
                    foreach ($missing_editions as $event_id => $event) {
                        foreach ($event as $year=>$edition) {
                            $row['date'] = fdateShort($edition['edition_date']);
                            $row['name'] = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                            $row['event'] = $edition['event_name'];

                            $this->table->add_row($row);
                            unset($row);
                        }
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
