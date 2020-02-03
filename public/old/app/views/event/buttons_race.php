<?php
if (isset($event_detail['calc_race_urls'])) {
    $button_class = "btn btn-md c-btn-border-2x c-theme-btn c-btn-uppercase c-btn-bold c-margin-t-20";
    ?>
    <div class="row" data-auto-height="true">
        <div class="col-md-12">
            <div class="btn-group">
                <?php
                foreach ($event_detail['calc_race_urls'] as $race_id => $race_list) {
                    foreach ($race_list as $link_id => $link) {
                        $button_state = '';
                        $race_detail = $event_detail['race_list'][$race_id];
                        switch ($link_id) {
                            case 4:
                                $link['buttontext'] = round($race_detail['race_distance'], 0) . "K " . $race_detail['racetype_name'] . " " . $link['buttontext'];
                                break;
                            case 7:
                                if ($link['type']=="file") {
                                $link['buttontext'] = round($race_detail['race_distance'], 0) . "K " . $race_detail['racetype_name'] . " " . $link['buttontext'];
                                }
                                break;                                
                            case 8:
                                if ($link['type']=="url") {
                                $link['buttontext'] = round($race_detail['race_distance'], 0) . "K " . $race_detail['racetype_name'] . " " . $link['buttontext'];
                                }
                                break;
                            default:
                                break;
                        }
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


//wts($event_detail['calc_race_urls']);
