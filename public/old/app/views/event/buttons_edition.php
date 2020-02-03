<?php
if ($calc_edition_urls) {
    $button_class = "btn btn-md c-btn-border-2x c-theme-btn c-btn-uppercase c-btn-bold c-margin-t-20";
    ?>
    <div class="row" data-auto-height="true">
        <div class="col-md-12">
            <div class="btn-group">
                <?php
                foreach ($calc_edition_urls as $link_id => $link) {
                    $button_state = '';
                    switch ($link_id) {
                        case 0:
                            if (isset($date_list[3])) {
                                if ((strtotime($date_list[3][0]["date_end"]) < strtotime("now")) && (isset($calc_edition_urls[5]))) {
                                    $link['url'] = '';
                                    $link['buttontext'] = "Entries Closed";
                                    $link['helptext'] = "Entries for this event has closed";
                                    $button_state = "disabled";
                                }
                            }
                            $skip=$link['type_id'];
                            break;
                        case 6:
                            $link['buttontext'] = '<i class="fa fa-facebook"></i> ' . $link['buttontext'];
                            break;
                        default:
                            break;
                    }
                    
                    if ($skip!=$link_id) {
                    ?>
                    <a href="<?= $link['url']; ?>" class="<?= $button_class; ?>" title="<?= $link['helptext']; ?>" <?= $button_state; ?>><?= $link['buttontext']; ?></a>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
//echo $date_list[4][$edition_id]["date_date"];
//wts($date_list);
//wts($calc_edition_urls);
//wts($event_detail);

