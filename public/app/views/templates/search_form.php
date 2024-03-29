<section class="search-form-over no-padding background-grey">
    <div class="container">
        <?php
        $attributes = array('class' => 'search-form', 'method' => 'post', 'id' => 'main-search-form');
        echo form_open(base_url("search"), $attributes);
        ?>
        <div class="row">
            <div class="col-lg-2 col-6">
                <?php
                echo form_label('Query', 'query');
                echo form_input([
                    'name' => 'query',
                    'id' => 'query',
                    'value' => set_value('query', "", false),
                    'class' => 'form-control',
                    'placeholder' => 'Not required',
                    'autofocus' => '',
                    "onclick" => "this.select()",
                ]);
                ?>
            </div>
            <div class="col-lg-2 col-3">
                <?php
                echo form_label('Distance', 'distance');
                $dist_options = array(
                    'all' => 'All',
                    'fun' => 'Fun Run',
                    '10' => '10km',
                    '15' => '15km',
                    '21' => 'Half-Marathon',
                    '30' => '30km',
                    '42' => 'Marathon',
                    'ultra' => 'Ultra Marathon',
                );
                echo form_dropdown('distance', $dist_options, set_value('distance'), ["id" => "distance"]);
                ?>
            </div>
            <div class="col-lg-2 col-3">
                <?php
                echo form_label('Status', 'status');
                $status_options = array(
                    'all' => 'All',
                    'active' => 'Active',
                    // 'confirmed' => 'Confirmed',
                    'verified' => 'Info Verified',                    
                );
                if(get_cookie("search_status_pref")) {
                    $init_status_value = get_cookie("search_status_pref");
                } else {
                    $init_status_value = 'active';
                }
                echo form_dropdown('status', $status_options, set_value('status',$init_status_value), ["id" => "status"]);
                ?>
            </div>
            <div class="col-lg-2 col-6">
                <?php
                echo form_label("When", 'when');
                $time_options = array(
                    'any' => 'Anytime',
                    'weekend' => 'This weekend',
                    'plus_30d' => 'Next 30 days',
                    'plus_3m' => 'Next 3 months',
                    'plus_6m' => 'Next 6 months',
                    'plus_1y' => '+ 1 year',
                    'minus_6m' => 'Past 6 months',
                );
                
                if(get_cookie("search_when_pref")) {
                    $init_when_value = get_cookie("search_when_pref");
                } else {
                    $init_when_value = 'plus_6m';
                }
                echo form_dropdown('when', $time_options, set_value('when', $init_when_value), ["id" => "when"]);
                ?>
            </div>

            <div class="col-lg-2 col-6">
                <?php
                echo form_label('Where', 'where');
                $loc_options = array(
                    'my' => 'My selected regions',
                    'all' => 'Everywhere',
                );
                foreach ($this->session->province_pages as $province_id => $province) {
                    $loc_options["Provinces"]["pro_" . $province_id] = $province['display'];
                }
                foreach ($this->session->region_pages as $region_id => $region) {
                    $loc_options["Regions"]["reg_" . $region_id] = $region['display'];
                }
                echo form_dropdown('where', $loc_options, set_value('where',$where), ["id" => "where"]);
                ?>
            </div>
            <!-- <div class="col-lg-1 col-6">
                <?php
                // echo form_label('Show as', 'show');
                // $show_options = array(
                //     'list' => 'List',
                //     'grid' => 'Grid',
                // );
                // if (get_cookie("listing_pref") == "grid") {
                //     $init_value = "grid";
                // } else {
                //     $init_value = "list";
                // }
                // echo form_dropdown('show', $show_options, set_value('show', $init_value), ["id" => "show"]);
                ?>
            </div> -->
            <div class="col-1">
                <?php
                echo form_label('', 'form-submit');
                $data = array(
                    'id' => 'form-submit',
                    'type' => 'submit',
                    'content' => '<i class="fa fa-search"></i>&nbsp;Search',
                    'class' => 'btn',
                );
                echo form_button($data);
                ?>
            </div>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</section>