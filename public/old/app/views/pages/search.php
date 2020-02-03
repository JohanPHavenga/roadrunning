<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
    <?= $title_bar; ?>
    <!-- BEGIN: PAGE CONTENT -->
    <?php
    $attributes = array('class' => 'c-quick-search', 'method' => 'get');
    echo form_open('search', $attributes);
    $ss = $this->input->get('query');
    ?>
    <div class="c-content-box c-size-sm"  style="padding-bottom:0px">
        <div class="container">
            <div class="col-md-6 c-margin-t-10">
                <div class="input-group c-square">
                    <?php
                    echo form_input(array(
                        'id' => 'query',
                        'name' => 'query',
                        'value' => @$ss,
                        'placeholder' =>
                        'Search for...',
                        'class' => 'form-control c-square c-theme',
                        'autofocus' => '',
                        'onfocus' => 'this.select();',
                    ));
                    ?>
                    <span class="input-group-btn">
                        <button class="btn c-theme-btn" type="submit">Search</button>
                    </span>
                </div>
            </div>
            <div class="col-md-6 c-margin-t-10">
                <div class="form-group form-c-checkboxes">
                    <div class="c-checkbox c-margin-t-5">
                        <?php
                        $inc = $this->input->get('inc');
                        $data = array(
                            'name' => 'inc',
                            'id' => 'inc_all',
                            'value' => TRUE,
                            'checked' => $inc,
                            'style' => 'margin:10px'
                        );

                        echo form_checkbox($data);
                        ?>
                        <label for="inc_all">
                            <span style="margin-top:2px"></span>
                            <span class="check" style="margin-top:2px;"></span>
                            <span class="box" style="margin-top:2px;"></span>
                            Include old events</label>
                    </div>
                </div>
            </div>

        </div>
    </div>    
    <?php
    echo form_close();
    ?>

    <div class="c-content-box c-size-sm">
        <div class="container">
            <?php
            $n = 0;
//                wts($search_results);
            if (!empty($search_results)) {
                foreach ($search_results as $year => $year_list) {
                    foreach ($year_list as $month => $month_list) {
                        foreach ($month_list as $day => $edition_list) {
                            foreach ($edition_list as $id => $event) {

                                if (@$edition_logo_list[$event['edition_id']]) {
                                    $img_url=base_url("uploads/edition/".$event['edition_id']."/".$edition_logo_list[$event['edition_id']]);

                                // =================================
                                // #toberemoved                      
                                // =================================
//                                } elseif (strlen($event['edition_logo']) > 3) {
//                                    $img_url = base_url("uploads/admin/edition/" . $event['edition_id'] . "/" . $event['edition_logo']);
                                } else {
                                    $rand = rand(1, 8);
                                    $img_url = "img/events/generic/" . $rand . ".jpg";
                                }
                                $n++;
                                if ($n == 1) {
                                    echo '<div class="row">';
                                }
                                ?>
                                <div class="col-md-6">
                                    <div class="row c-margin-b-40">
                                        <div class="c-content-product-2 c-bg-white">
                                            <div class="col-md-4">
                                                <div class="c-content-overlay">
                                                    <!--<div class="c-label c-label-right c-theme-bg c-font-uppercase c-font-white c-font-13 c-font-bold">New</div>-->
                                                    <div class="c-overlay-wrapper">
                                                        <div class="c-overlay-content">
                                                            <a href="<?= base_url($event['edition_url']); ?>" class="btn btn-md c-btn-grey-1 c-btn-uppercase c-btn-bold c-btn-border-1x c-btn-square">Detail</a>
                                                        </div>
                                                    </div>
                                                    <div class="c-bg-img-center c-overlay-object" data-height="height" style="height: 230px; background-image: url('<?= $img_url; ?>');"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="c-info-list">
                                                    <h3 class="c-title c-font-bold c-font-22 c-font-dark">
                                                        <a class="c-theme-link" href="<?= base_url($event['edition_url']); ?>"><?= substr($event['edition_name'], 0, -5); ?></a>
                                                    </h3>
                                                    <p class="c-desc c-font-16 c-font-thin">
                                                        <?= $event['town_name']; ?><br>
                                                        <?= $event['race_distance']; ?><br>
                                                        <?= $event['race_time_start']; ?> Race
                                                    </p>
                                                    <p class="c-price c-font-26 c-font-thin"><?= $event['edition_date']; ?></p>
                                                </div>
                                                <div>
                                                    <a href="<?= base_url($event['edition_url']); ?>" class="btn c-theme-btn c-btn-border-2x c-btn-square"> Detail </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($n == 2) {
                                    echo '</div>';
                                    $n = 0;
                                }
                            }
                        }
                    }
                }
            } else {
                echo '<div class="col-md-6"><div class="row c-margin-b-40"><div class="col-md-12">';
                echo "<p>$msg</p>";
                echo '</div></div></div>';
            }
            ?>
        </div>
    </div>
    <!-- END: PAGE CONTENT -->
</div>
<!-- END: PAGE CONTAINER -->
