<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">

            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?> 
                    <span class="badge badge-danger" style="font-size: 0.6em; position: relative; top: -2px">Beta</span></h3>
                <p>I am busy working on a module where you can search and <b>claim a result</b> as your own, building up a nice history of your results. </p>
                <p>Currently we have loaded results for <u>races in the Western Cape</u> starting from October 2019. This will be expanded on over time.</p>
                <?php
                if ($logged_in_user) {
                    ?>
                    <p>Use the search form below to search for your results to add them to your profile.</p>


                    <h4 class="text-uppercase">Search for results</h4>
                    <?php
                    $search_url = base_url("result/search");
                    $attributes = array('class' => '', 'role' => 'form');
                    echo form_open($search_url, $attributes);
                    ?>
                    <div class="row">

                        <div class="form-group col-lg-6">
                            <?php
                            echo form_input([
                                'name' => 'result_search',
                                'id' => 'result_search',
                                'value' => set_value('result_search'),
                                'class' => 'form-control required',
                                'placeholder' => 'Search for a race',
                                'required' => '',
                            ]);
                            ?> 
                        </div>

                        <div class="form-group col-lg-4">
                            <?php
                            $data = array(
                                'type' => 'submit',
                                'content' => 'Search',
                                'class' => 'btn',
                            );
                            echo form_button($data);
                            ?>
                        </div>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                    <h4 class="text-uppercase">Claimed results</h4>
                    <?php
                    if ($user_result_list) {
                        $template = array(
                            'table_open' => '<table id="datatable_date" class="table table-striped table-bordered table-hover table-sm result_table">',
                        );

                        $this->table->set_template($template);
                        $this->table->set_heading('Date', 'Event', 'Distance', 'Time');
                        foreach ($user_result_list as $result) {
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
                    } else {
                        echo "<p>No results linked to your profile</p>";
                    }
//                wts($user_result_list);
                } else {
                    ?>
                    <p>To give it a try, please <a href="/login">log in</a> or <a href="/register">create a new profile</a>.</p>
                    <div class="form-group">
                        <a class="btn" href="/login">Login</a>
                        <a class="btn btn-light" href="/register">Register</a>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- end: Content-->
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                if (!empty($logged_in_user)) {
                    // PROFILE WIDGET
                    $this->load->view('widgets/profile');
                }
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->

        </div>
    </div>
</section>
<!-- end: My Results -->
