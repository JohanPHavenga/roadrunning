
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-hourglass"></i>
                    <span class="bold"> Summary of edition history data collected</span> - 
                    <small style="font-size: 0.8em;">Last Updated: <b><?=$history_summary[key($history_summary)]['created_date']?></b> | since 23 Aug 2019</small>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if ($history_summary) {
                    $this->table->set_template(ftable('history_summary_table'));
                    $this->table->set_heading(["ID", "Edition Name", "Last Visited", "Week", "Month", "Year"]);
                    foreach ($history_summary as $edition_id => $entry) {
                        $row['id'] = $edition_id;
                        $row['name'] = "<a href='/admin/edition/create/edit/$edition_id'>" . $entry['edition_name'] . "</a>";
                        $row['date'] = fdateLong($entry['history_lastvisited']);

                        $row['week'] = $entry['historysum_countweek'];
                        $row['month'] = $entry['historysum_countmonth'];
                        $row['year'] = $entry['historysum_countyear'];

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
//wts($history_summary);
?>            
