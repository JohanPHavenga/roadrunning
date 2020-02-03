<?php
$gps_parts = explode(",", $event_detail['edition_gps']);
$lat = $gps_parts[0];
$long = $gps_parts[1];
?>
<div class="c-content-box c-size-sm <?= $box_color; ?>">
    <div class="container">
        <div class="c-content-bar-2 c-opt-1">
            <div class="row" data-auto-height="true">
                <div class="col-md-12">
                    <div class="c-content-title-1 ">
                        <h3 class="c-font-uppercase c-font-bold">
                            Accommodation Options
                        </h3>
                    </div>
                    <iframe src="https://www.stay22.com/embed/gm?aid=roadrunning&lat=<?=$lat;?>&lng=<?=$long;?>&checkin=<?=fdateShort($event_detail['edition_date']);?>&maincolor=32c5d2&venue=<?=$event_detail['edition_address'];?>" id="stay22-widget" width="100%" height="560" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//echo fdateShort($event_detail['edition_date']);
?>

