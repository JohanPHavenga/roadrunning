<?php
//wts($url_list);
// GET URL
if (isset($url_list[4][0])) {
    $url=$url_list[4][0]['url_name'];
} else {
    $url=$edition_data['timingprovider_url'];
}
// TIMING PROVIDER
switch ($edition_data['timingprovider_id']) {
    case 2:
        ?>
        <div class="content row">
            <div class="col-lg-5">
                <p class="text-center">
                    <a href="<?=$url;?>" title="View results on FinishTime site">
                        <img src="/assets/img/FinishTime_Logo.png" alt="FinishTime" style="width: 80%;"/>
                    </a>
                </p>
            </div>

            <div class="col-lg-7">
                <h5>Official time keeping done by <a href="<?=$url;?>" title="View results on FinishTime site">
                    <?= $edition_data['timingprovider_name']; ?></a></h5>
                <p style="font-size: 0.9em;">Click on their logo to view the official results on their site</p>
            </div>
        </div>
        <?php
        break;
    default:
        break;
}
