<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Find a link to ALL the pages on the site below</p>

                <!-- add box -->
                <div class="row m-b-30 m-t-10">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <?php
//                        wts($this->session->static_pages);
                        ?>
                        <h4 class="text-uppercase">Pages</h4>
                        <ul>
                            <?php
                            foreach ($this->session->static_pages as $page_detail) {
                                echo "<li><a href='" . $page_detail['loc'] . "'>" . ucwords($page_detail['display']) . "</a>";
                                if (isset($page_detail['sub-menu'])) {
                                    echo "<ul>";
                                    foreach ($page_detail['sub-menu'] as $sub_page) {
                                        echo "<li><a href='" . $sub_page['loc'] . "'>" . ucwords($sub_page['display']) . "</a>";
                                    }
                                    echo "</ul>";
                                }
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-lg-5">
                        <h4 class="text-uppercase">Provinces</h4>
                        <ul>
                            <?php
                            foreach ($this->session->province_pages as $province_id => $province) {
                                echo "<li><a href='" . $province['loc'] . "'>" . ucwords($province['display']) . "</a></li>";
                            }
                            ?>
                        </ul>
                        <h4 class="text-uppercase">Regions</h4>
                        <ul>
                            <?php
                            foreach ($this->session->region_pages as $region_id => $region) {
                                echo "<li><a href='" . $region['loc'] . "'>" . ucwords($region['display']) . "</a></li>";
                            }
                            ?>
                        </ul>   
                    </div>

                    <div class="col-lg-3">
                        <h4 class="text-uppercase">Calendar</h4>
                        <?php
                        
                        ?>
                    </div>
                </div>

                <div class="row">

                </div>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
        <div class="row">
            <div class="content col-lg-12">
                <h4 class="text-uppercase">RACES</h4>
                <?php
                foreach ($edition_arr as $year => $year_list) {
                    foreach ($year_list as $month => $month_list) {
                        echo "<h5>$month $year</h5>";
                        echo "<ul class='list inline'>";
                        foreach ($month_list as $day => $edition_list) {
                            foreach ($edition_list as $edition_id => $edition) {
                                echo "<li>"
                                . date("D j M", strtotime($edition['edition_date'])) . " - "
                                . "<a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
                                echo "</li>";
                            }
                        }
                        echo "</ul>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
<!-- end: Sitemap -->
