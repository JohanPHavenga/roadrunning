

<section class="no-padding-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 m-b-10">
                <h3 class="text-uppercase">More races by Regions</h3>
            </div>
        </div>
        <div class="row">
            <?php
            $count = 0;
            foreach ($region_by_province_list as $province_id => $province) {
                if ($count == 0) {
                    ?>
                    <div class="col-lg-4">
                        <div class="accordion accordion-shadow">
                            <?php
                        }
                        ?>
                        <div class="ac-item">
                            <h5 class="ac-title"><?= $province['province_name']; ?><br><span style="font-size: 0.8em; color: #999;"><?= $province['province_count']; ?> upcoming races</span></h5>
                            <div class="ac-content">
                                <ul class="list">
                                    <?php
                                    foreach ($province['region_list'] as $region) {
                                        echo '<li><a href="' . base_url("region/" . $region['region_slug']) . '">' . $region["region_name"] . '</a> ('.$region["region_count"].')</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                        $count++;
                        if ($count == 3) {
                            $count = 0;
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>

<?php
//wts($region_by_province_list);
?>