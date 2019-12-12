<div class="row m-b-10">
    <div class="col-lg-12">
        <span class="post-meta"><i class="fa fa-calendar"></i> <?= fdateHumanFull($edition_data['edition_date'], false); ?></span>
        <span class="post-meta"><i class="fa fa-clock"></i> 
            <?php
            echo ftimeSort($edition_data['race_summary']['times']['start']);
            if ($edition_data['race_summary']['times']['end']) {
                echo " - " . ftimeSort($edition_data['race_summary']['times']['end']);
            }
            ?>
        </span>
        <span class="post-meta"><a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>"><i class="fa fa-map-marker"></i> <address><?= $address; ?></address></a></span>

    </div>
</div>

