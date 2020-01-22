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
                        <h4 class="text-uppercase">Pages</h4>
                        <ul>
                            <?php
                            foreach ($this->session->static_pages as $page_detail) {
                                echo "<li><a href='" . $page_detail['loc'] . "'>" . ucwords($page_detail['display']) . "</a></li>";
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
                        <ul>
                            <?php
                            foreach ($this->session->calendar_date_list as $year => $month_list) {
                                foreach ($month_list as $month_number => $month_name) {
                                    echo "<li><a href='" . base_url() . "calendar/" . $year . "/" . $month_number . "'>" . $month_name . "</a></li>";
                                }
                            }
                            ?>
                        </ul>    
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        foreach ($edition_arr as $year => $year_list) {
                            echo "<h3>$year</h3>";
                            foreach ($year_list as $month => $month_list) {
                                echo "<h3>$month</h3>";
                                foreach ($month_list as $day => $edition_list) {
                                    foreach ($edition_list as $edition_id => $edition) {
                                        echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
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
    </div>
</section>
<!-- end: Sitemap -->
