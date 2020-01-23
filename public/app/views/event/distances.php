<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Race Distances</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">

                <!-- add box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <div class="accordion accordion-shadow">
                            <?php
                            foreach ($race_list as $race_id => $race) {
                                $ac_data['race'] = $race;
                                $ac_data['active'] = '';
                                if (isset($url_params[1])) {
                                    if (url_title($race['race_name']) == $url_params[1]) {
                                        $ac_data['active'] = "ac-active";
                                    }
                                } elseif ($race_id === array_key_first($race_list)) {
                                    $ac_data['active'] = "ac-active";
                                }

                                $this->load->view('widgets/race_accordion_item', $ac_data);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Get Notified via Email";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<?php
//wts($race_list);

