<div class="row m-b-10">
    <div class="col-lg-12">
        <span class="post-meta">
            <i class="fa fa-calendar"></i>
            <time datetime="<?= fdateShort($date_list[1][0]['date_start']); ?>">
                <?= fdateHuman($date_list[1][0]['date_start']); ?>
            </time>
            <?php
            if ($date_list[1][0]['date_start'] != $date_list[1][0]['date_end']) {
                echo " - ";
            ?>
                <time datetime="<?= fdateShort($date_list[1][0]['date_end']); ?>">
                    <?= fdateHuman($date_list[1][0]['date_end']); ?>
                </time>
            <?php
            }
            ?>
        </span>
        <?php
        if ($edition_data['edition_status'] == 17) {
        ?>
            <span class="post-meta"><i class="fa fa-map-marker"></i> Virtual Race</span>
        <?php
        } else {
            $btn_class="btn-light";
            if (!isset($this->session->user['logged_in'])) {
                $fav_link = "href='".base_url("login")."'";
                $fav_id = "";                
            } else {
                $fav_link = "";
                $fav_id = "fav_but_add";
                if ($edition_data['is_favourite']) {
                    $btn_class="btn-primary";
                    $fav_id = "fav_but_remove";
                } 
            }
        ?>
            <span class="post-meta"><i class="fa fa-clock"></i>
                <time datetime="<?= ftimeSort($edition_data['race_summary']['times']['start']); ?>">
                    <?= ftimeSort($edition_data['race_summary']['times']['start']); ?>
                </time>

                <?php
                if ($edition_data['race_summary']['times']['end']) {
                    echo " - " . ftimeSort($edition_data['race_summary']['times']['end']);
                }
                ?>
            </span>
            <span class="post-meta">
                <a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>">
                    <i class="fa fa-map-marker"></i>
                    <address><?= $address; ?></address>
                </a>
            </span>
            <span class="post-meta">
                <a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>">
                    <i class="fa fa-map-marked"></i> <?= $edition_data['edition_gps']; ?>
                </a>
            </span>
            <span class="post-meta">
                <a <?= $fav_link; ?> class="btn <?=$btn_class;?> btn-xs" id="<?= $fav_id; ?>">
                    <i class="far fa-heart" aria-hidden="true"></i> Favourite</a>
            </span>
        <?php
        }

        ?>

    </div>
</div>


