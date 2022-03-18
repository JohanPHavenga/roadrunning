<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Race day information</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">

                <!-- ad box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <?php
                        // LANDSCAPE ADS WIDGET
                        $this->load->view('widgets/horizontal_ad');
                        ?>
                    </div>
                </div>

                <?php
                // RACE DAY INFO CONTENT
                $this->load->view('event/content/race-day-information');
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        if (isset($flyer['edition'])) {
                        ?>
                            <a href="<?= $flyer['edition']['url']; ?>" class="btn btn-default">
                                <i class="fa fa-<?= $flyer['edition']['icon']; ?>"></i> <?= $flyer['edition']['text']; ?></a>

                        <?php
                        }
                        if (isset($entry_form['edition'])) {
                        ?>
                            <a href="<?= $entry_form['edition']['url']; ?>" class="btn btn-light">
                                <i class="fa fa-<?= $entry_form['edition']['icon']; ?>"></i> <?= $entry_form['edition']['text']; ?></a>

                        <?php
                        }
                        // website link
                        if (isset($url_list[1])) {
                        ?>
                            <a href="<?= $url_list['1'][0]['url_name']; ?>" class="btn btn-light">
                                <i class="fa fa-link"></i> Race Website</a>

                        <?php
                        }
                        // press release
                        if (isset($file_list[10])) {
                        ?>
                            <a href="<?= base_url("file/edition/" . $slug . "/press release/" . $this->data_to_views['file_list'][10][0]['file_name']); ?>" class="btn btn-light">
                                <i class="fa fa-file-pdf"></i> Official Press Release</a>

                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Want to get notified when info is made available?";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');

                // MAP WIDGET
                // if not virtual
                if ($edition_data['edition_status'] != 17) {
                    $this->load->view('widgets/map');
                }
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->