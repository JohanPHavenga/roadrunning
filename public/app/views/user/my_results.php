<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">

            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?>
                    <span class="badge badge-danger" style="font-size: 0.6em; position: relative; top: -2px">Beta</span>
                </h3>
                <p>We are busy working on this module where you can search and <b>claim a result</b> as your own,
                    building up a nice history of your results. </p>
                <p>The result set is limited at the moment but will be expanded on over time.</p>
                <?php
                if ($logged_in_user) {
                ?>
                    <p>Use the search form below to search for your results to add them to your profile. Alternatively
                        you can add results manually for those that do not have results loaded on the site
                    </p>

                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="text-uppercase">Link results</h4>
                            <?php
                            $search_url = base_url("result/search");
                            $attributes = array('class' => '', 'role' => 'form');
                            echo form_open($search_url, $attributes);
                            ?>
                            <div class="row">
                                <div class="form-group col-lg-10">
                                    <label>Search races for results</label>
                                    <div class="input-group mb-3">
                                        <?php
                                        echo form_input([
                                            'name' => 'result_search',
                                            'id' => 'result_search',
                                            'value' => set_value('result_search'),
                                            'class' => 'form-control required',
                                            'placeholder' => 'Search for a race',
                                            'autocomplete' => 'off',
                                            'style' => 'height: 40px;',
                                            'required' => '',
                                        ]);
                                        ?>
                                        <div class="input-group-append">
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
                                </div>
                            </div>
                            <?php
                            echo form_close(); 
                            ?>
                            

                        </div>
                        <div class="col-lg-4 m-b-20">
                            <h4 class="text-uppercase">Auto Search</h4>
                            <p>Use your name & surname to auto find suggested results</p>
                            <a href="<?= base_url("result/auto"); ?>" class="btn btn-primary">Auto Search</a>
                        </div>
                    </div>



                    <h4 class="text-uppercase">Your claimed results</h4>
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