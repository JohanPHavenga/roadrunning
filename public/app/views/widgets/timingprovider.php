<?php
//wts($url_list);
// GET URL
if (isset($url_list[4][0])) {
    $url = $url_list[4][0]['url_name'];
} else {
    $url = $edition_data['timingprovider_url'];
}
// TIMING PROVIDER
switch ($edition_data['timingprovider_id']) {
    case 2:
?>
        <div class="content row">
            <div class="col-lg-5 col-sm-5">
                <p class="text-center">
                    <a href="<?= $url; ?>" title="View results on FinishTime site">
                        <img src="/assets/img/FinishTime_Logo.png" alt="FinishTime Logo" style="width: 80%;" />
                    </a>
                </p>
            </div>

            <div class="col-lg-7 col-sm-7">
                <h5>Official time keeping done by <a href="<?= $url; ?>" title="View results on FinishTime site">
                        <?= $edition_data['timingprovider_name']; ?></a></h5>
                <p style="font-size: 0.9em;">Click on their logo to view the official results on their site</p>
            </div>
        </div>
    <?php
        break;
    case 3:
    ?>
        <div class="content row">
            <div class="col-lg-5 col-sm-5">
                <p class="text-center">
                    <a href="<?= $url; ?>" title="View results on ChampionChip site">
                        <img src="/assets/img/ChampionChipAfrica_Logo.png" alt="ChampionChip Logo" style="width: 80%;" />
                    </a>
                </p>
            </div>

            <div class="col-lg-7 col-sm-7">
                <h5>Official time keeping done by <a href="<?= $url; ?>" title="View results on ChampionChip site">
                        <?= $edition_data['timingprovider_name']; ?></a></h5>
                <p style="font-size: 0.9em;">Click on their logo to view the official results on their site</p>
            </div>
        </div>
    <?php
    case 4:
    ?>
        <div class="content row">
            <div class="col-lg-5 col-sm-5">
                <p class="text-center">
                    <a href="<?= $url; ?>" title="View results on ProTime site">
                        <img src="/assets/img/Protime-Logo.png" alt="ProTime Logo" style="width: 80%;" />
                    </a>
                </p>
            </div>

            <div class="col-lg-7 col-sm-7">
                <h5>Official time keeping done by <a href="<?= $url; ?>" title="View results on ProTime site">
                        <?= $edition_data['timingprovider_name']; ?></a></h5>
                <p style="font-size: 0.9em;">Click on their logo to view the official results on their site</p>
            </div>
        </div>
    <?php
        break;
    case 5:
    ?>
        <div class="content col-lg-12">
            <div class="row">
                <div class="col-lg-3 col-sm-3">
                    <p class="text-center">
                        <a href="<?= $url; ?>" title="View results on PeakTime site">
                            <img src="/assets/img/Peak-Timing-Logo.png" alt="PeakTime Logo" style="height: 80px;" />
                        </a>
                    </p>
                </div>

                <div class="col-lg-7 col-sm-7">
                    <h5>Official time keeping done by <a href="<?= $url; ?>" title="View results on PeakTime site">
                            <?= $edition_data['timingprovider_name']; ?></a></h5>
                    <p style="font-size: 0.9em;">Click on their logo to view the official results on their site</p>
                </div>
            </div>
        </div>
    <?php
        break;
    case 6:
    ?>
        <div class="content col-lg-12">
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <p class="text-center">
                        <a href="<?= $url; ?>" title="View results on EventTiming site">
                            <img src="/assets/img/eventtiming_logo.jpg" alt="EventTiming Logo" style="width: 70%;"  />
                        </a>
                    </p>
                </div>

                <div class="col-lg-7 col-sm-7">
                    <h5>Official time keeping done by <a href="<?= $url; ?>" title="View results on EventTiming site">
                            <?= $edition_data['timingprovider_name']; ?></a></h5>
                    <p style="font-size: 0.9em;">Click on their logo to view the official results on their site</p>
                </div>
            </div>
        </div>
<?php
        break;
    default:
        break;
}
