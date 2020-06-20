<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <?php
                // CRUMBS WIDGET
                $this->load->view('widgets/crumbs');
                ?>
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Welcome <b><?= $logged_in_user['user_name'] . " " . $logged_in_user['user_surname']; ?>.</b></p>

                <?php
                if (empty($result_count)) {
                    echo "<p>This is your dashboard showing your results. <br>Please <a href='/user/my-results'>search and claim results</a> "
                    . "to start showing some graphs.</p>";
                } else {
                    foreach ($result_count as $key => $rc) {
                        ?>
                        <h4><?=$rc['name'];?></h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="<?=$rc['chart'];?>" class="graph_holder"></div>
                            </div>
                        </div>
                        <?php
                        $template = array(
                            'table_open' => '<table id="datatable_date" class="m-t-10 table table-striped table-bordered table-hover table-sm result_table">',
                        );

                        $this->table->set_template($template);
                        $this->table->set_heading('Date', 'Event', 'Distance', 'Time');
                        foreach ($result_list[$key] as $result) {
                            $url = base_url("result/view/" . $result['result_id']);
                            $row = [
                                "<a href='$url'>" . fdateShort($result['edition_date']) . "</a>",
                                "<a href='$url'>" . $result['edition_name'] . "</a>",
                                "<a href='$url'><span class='badge badge-" . $result['race_color'] . "'>" . round($result['race_distance'], 0) . "km</span></a>",
                                "<a href='$url'>" . $result['result_time'] . "</a>",
                            ];
                            $this->table->add_row($row);
                        }
                        echo $this->table->generate();
                        ?>
                        <div class="line m-b-30"></div> 
                        <?php
                    }
                }
                ?>
                <div class="btn-group">
                    <a href='/user/my-results' class='btn'><i class='fa fa-clock'></i> My Results</a>
                </div>
            </div>
            <!-- end: Content-->
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // PROFILE WIDGET
                $this->load->view('widgets/profile');
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->

        </div>
    </div>
</section>
<!-- end: PROFILE -->




