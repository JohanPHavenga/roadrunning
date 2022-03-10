<?php
if ((empty($route_maps)) && (empty($route_profile))) {
    $mailing_list_notice = "<p>If you would like to be notified once route maps are loaded, "
        . "please add yourself to the "
        . "<a href='" . base_url('event/' . $slug . '/subscribe') . "' title='Add yourself to the mailing list'>mailing list</a> for this race.</p>";
    if (!$in_past) {
        $msg = "<b>No route maps</b> has been made available for this race yet.";
    } else {
        $msg = "No route maps were made available for this race.";
    }
?>
    <div class="row m-b-40">
        <div class="col-lg-12">
            <!-- <div role="alert" class="m-b-30 alert alert-warning">
                <i class="fa fa-info-circle"></i> <b><?= $msg; ?></b>
            </div> -->
            <p class='text-warning'><?=$msg;?></p>
            <?php
            if (!$in_past) {
            ?>
                <p>
                    <?= $mailing_list_notice; ?>
                </p>
            <?php
            }
            ?>
        </div>
    </div>
<?php

    // if there is route maps
} else {
?>
    <div class="row m-b-40">
        <div class="col-lg-12">
            <!-- <div role="alert" class="m-b-30 alert alert-success">
                <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b>Route Maps has been loaded!</b>
            </div> -->
            <p class='text-success'><b>Route maps has been loaded!</b></p>
            <?php
            
            if (isset($route_maps['edition'])) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (is_image($route_maps['edition']['url'])) {
                        ?>
                            <h4>Event route map</h4>
                            <p>
                                <img style="max-width: 100%;" src="<?= $route_maps['edition']['url']; ?>" title="<?= $edition_data['edition_name']; ?> Route Map" />
                            </p>
                        <?php
                        } else {
                        ?>
                            <a href="<?= $route_maps['edition']['url']; ?>" class="btn btn-light">
                                <i class="fa fa-<?= $route_maps['edition']['icon']; ?>"></i> <?= $route_maps['edition']['text']; ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            if (isset($route_maps['race'])) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        foreach ($route_maps['race'] as $race_map) {
                            if (is_image($race_map['url'])) {
                        ?>
                                <h4><?= $race_map['text']; ?></h4>
                                <p style="margin-bottom: 10px;">
                                    <img style="max-width: 100%;" src="<?= $race_map['url']; ?>" title="<?= $race_map['text']; ?>" style="border: 1px solid #333;" />
                                </p>
                            <?php
                            } else {
                            ?>
                                <p>
                                    <a href="<?= $race_map['url']; ?>" class="btn btn-light"><i class="fa fa-<?= $race_map['icon']; ?>"></i>
                                        <?= $race_map['text']; ?></a>
                                </p>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            if (isset($route_profile['edition'])) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (is_image($route_profile['edition']['url'])) {
                        ?>
                            <h4>Route Profile</h4>
                            <p>
                                <img style="max-width: 100%;" src="<?= $route_profile['edition']['url']; ?>" title="<?= $edition_data['edition_name']; ?> Route Profile" />
                            </p>
                        <?php
                        } else {
                        ?>
                            <a href="<?= $route_profile['edition']['url']; ?>" class="btn btn-light">
                                <i class="fa fa-<?= $route_profile['edition']['icon']; ?>"></i> <?= $route_profile['edition']['text']; ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            if (isset($route_profile['race'])) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        foreach ($route_profile['race'] as $race_map) {
                            if (is_image($race_map['url'])) {
                        ?>
                                <h4><?= $race_map['text']; ?></h4>
                                <p style="margin-bottom: 10px;">
                                    <img style="max-width: 100%;" src="<?= $race_map['url']; ?>" title="<?= $race_map['text']; ?>" style="border: 1px solid #333;" />
                                </p>
                            <?php
                            } else {
                            ?>
                                <p>
                                    <a href="<?= $race_map['url']; ?>" class="btn btn-light"><i class="fa fa-<?= $race_map['icon']; ?>"></i>
                                        <?= $race_map['text']; ?></a>
                                </p>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
<?php
}
?>