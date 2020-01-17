<?php
$img_url = "https://dev.virtualearth.net/REST/V1/Imagery/Map/Road/" . $edition_data['edition_gps'] . "/11?mapSize=1224,300&format=png&key=An56kGemZ2QHt-SqwYx3fi9E89M_lMQDqODLp55fEnUejV10d2fH9jkrlUoC6xlS";
$page_title = $edition_data['event_name'];
$page_sub_title = fdateHumanFull($edition_data['edition_date']);
?>
<!-- Page title -->
<section id="page-title" class="text-light page-title-left <?= $page_title_small; ?>" style="background-image:linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.6)),url(<?= $img_url; ?>);">

    <div class="container">
        <div class="breadcrumb">
            <ul>
                <?php
                $cl = '';
                foreach ($crumbs_arr as $name => $url) {
                    if ($name === array_key_last($crumbs_arr)) {
                        $cl = "active";
                    }
                    echo "<li class='$cl'><a href='$url'>$name</a> </li>";
                }
                ?>
            </ul>
        </div>
        <div class="page-title">
            <h1><?= $page_title; ?></h1>
        </div>
        <?php
        if (isset($page_sub_title)) {
            echo "<div class='page-sub-title'><h4>" . $page_sub_title . "</h4></div>";
        }
        ?>
    </div>
</section>
<!-- end: Page title -->
