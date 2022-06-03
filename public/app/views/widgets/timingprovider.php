<?php
//wts($url_list);
// GET URL
if (isset($url_list[4][0])) {
    $url = $url_list[4][0]['url_name'];
} else {
    $url = $edition_data['timingprovider_url'];
}

if ($edition_data['timingprovider_id']>1) {
?>
<div class="content row">
    <div class="col-lg-5 col-sm-5">
        <p class="text-center">
            <a href="<?= $url; ?>" title="View results on <?= $edition_data['timingprovider_name']; ?> site">
                <img src="/assets/img/timingproviders/<?= $edition_data['timingprovider_img']; ?>" alt="<?= $edition_data['timingprovider_name']; ?> Logo" style="width: 80%;" />
            </a>
        </p>
    </div>

    <div class="col-lg-7 col-sm-7">
        <h5>Time keeping for this event done by <a href="<?= $url; ?>" title="View results on <?= $edition_data['timingprovider_name']; ?> site">
                <?= $edition_data['timingprovider_name']; ?></a></h5>
        <p style="font-size: 0.9em;">Click on the logo to view results directly on their site</p>
    </div>
</div>
<?php
}