<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
    <?= $title_bar; ?>
    <!-- BEGIN: PAGE CONTENT -->

    <?php
    // alert message on top of the page
    // set flashdata [alert|status]
    if ($this->session->flashdata('alert')) {
        $alert_msg = $this->session->flashdata('alert');
        if (!($this->session->flashdata('status'))) {
            $status = 'warning';
        } else {
            $status = $this->session->flashdata('status');
        }
        echo "<div class='c-content-box c-size-sm' style='padding-bottom: 0;'>";
        echo "<div class='container'><div class='row'>";
        echo "<div class='alert alert-$status' role='alert'><i class='fa fa-exclamation-circle'></i> " . $alert_msg . "</div>";
        echo "</div></div></div>";
    }

    $heading_date = date("Y");
    if (date('m') > 5) {
        $heading_date .= "-" . date("Y") - 1;
    }
    ?>

    <!-- BEGIN: CONTENT/FEATURES/FEATURES-1 -->
    <div class="c-content-box c-size-md c-bg-white">
        <div class="container">

            <div class="c-content-box c-size-md c-no-bottom-padding c-overflow-hide mobile-hide" style="padding: 0;">
                <div class="c-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="c-content-title-1">
                                <h3 class="c-font-34 c-font-center c-font-bold c-font-uppercase c-margin-b-30"> List of Road Running Races Results</h3>
                                <div class="c-line-center c-theme-bg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="c-content-title-1">
                                <!-- Past Events -->
                                <ins class="adsbygoogle"
                                     style="display:block"
                                     data-ad-client="ca-pub-8912238222537097"
                                     data-ad-slot="8519859058"
                                     data-ad-format="auto"
                                     data-full-width-responsive="true"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="c-content-box c-size-sm c-no-bottom-padding c-overflow-hide">
                <div class="c-container">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $past_race_list_html; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="c-content-box c-size-sm c-no-bottom-padding c-overflow-hide">
                <div class="c-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="c-content-title-1">
                                <!-- Past Events -->
                                <ins class="adsbygoogle"
                                     style="display:block"
                                     data-ad-client="ca-pub-8912238222537097"
                                     data-ad-slot="8519859058"
                                     data-ad-format="auto"
                                     data-full-width-responsive="true"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="c-content-box c-size-sm c-no-bottom-padding c-overflow-hide" >
                <div class="c-container">
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                <a class="btn c-theme-btn c-btn-uppercase btn-lg c-btn-bold c-btn-round" href="/">
                                    <i class="icon-home"></i> Home</a>
                                <a class="btn c-theme-btn c-btn-uppercase btn-lg c-btn-bold c-btn-round" href="/calendar/upcoming">
                                    <i class="icon-clock"></i> View upcoming races</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END: CONTENT/FEATURES/FEATURES-1 -->
    </div>
    <!-- END: PAGE CONTAINER -->
