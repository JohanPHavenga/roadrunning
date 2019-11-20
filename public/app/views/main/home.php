<section id="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
            <h2>HOME</h2>
            <p>Welcome, to the real world</p>

            <h3>Featured Events incl Races</h3>
            <?php
            foreach ($featured_events_new as $edition_id => $edition) {
                echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
            }
            ?>
            <h3>Last Edited Events</h3>
            <?php
            foreach ($last_edited_events as $edition_id => $edition) {
                echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
            }
            ?>
            <h3>Most popular events (month)</h3>
            <?php
            foreach ($history_sum_month as $edition_id => $edition) {
                echo date("D j M", strtotime($edition['edition_date'])) . " - "
                . "<a href='" . $edition['edition_url'] . "'>" . $edition['edition_name'] . "</a>"
                . " - Visits: <b>" . $edition['historysum_countmonth'] . "</b>  ";
            }
            ?>
            </div>
        </div>
    </div>
</section>
<?php

//wts($featured_events);
//wts($featured_events_new);
//wts($last_edited_events);
//wts($most_visited_events);