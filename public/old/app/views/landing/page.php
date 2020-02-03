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
        echo "<div class='alert alert-$status' role='alert'>$alert_msg</div>";
        echo "</div></div></div>";
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
                                <h3 class="c-font-34 c-font-center c-font-bold c-font-uppercase c-margin-b-30"> Road running races in <?= $area; ?> area</h3>
                                <div class="c-line-center c-theme-bg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="c-content-title-1">
<!--                                <a href="http://coachparry.com/join-coach-parry/?ref=9" title="Coach Parry" class="cp_ad" target="_blank">
                                    <img src="http://coachparry.com/wp-content/uploads/2019/02/Banner-1.jpg" alt="Coach Parry" /></a>-->
                                 <!--Landing Pages--> 
                                <ins class="adsbygoogle"
                                     style="display:block"
                                     data-ad-client="ca-pub-8912238222537097"
                                     data-ad-slot="9867943209"
                                     data-ad-format="auto"
                                     data-full-width-responsive="true" class="g_ad_hide"></ins>
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
                            <?= $race_list_html; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="c-content-box c-size-sm c-no-bottom-padding c-overflow-hide">
                <div class="c-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="c-content-title-1">
                                <!-- Landing Pages -->
                                <ins class="adsbygoogle"
                                     style="display:block"
                                     data-ad-client="ca-pub-8912238222537097"
                                     data-ad-slot="9867943209"
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
                    <div class="row c-page-faq-2">
                        <div class="col-md-12">
                            <p>
                                Not finding a race in your area that tickles your fancy? Why not try a <b>Parkrun?</b><br>
                                Parkruns are free, weekly, 5km timed runs around the world. They are open to everyone, free, and are safe and easy to take part in. 
                                Read more <u><a target="_blank" href="http://www.parkrun.co.za">here</a></u>.
                            </p>
                            <?= $parkrun_accordion; ?>
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
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END: CONTENT/FEATURES/FEATURES-1 -->
    </div>
    <!-- END: PAGE CONTAINER -->




