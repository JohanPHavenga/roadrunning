<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2><?= $edition_data['edition_name']; ?></h2>
            </div>
            <div class="col-lg-2 post-meta-share">
<!--                <a class="btn btn-xs btn-slide btn-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url(); ?>">
                    <i class="fab fa-facebook-f"></i>
                    <span>Facebook</span>
                </a>
                <a class="btn btn-xs btn-slide btn-twitter" href="https://twitter.com/share?ref_src=twsrc%5Etfw" data-width="100" style="width: 28px;">
                    <i class="fab fa-twitter"></i>
                    <span>Twitter</span>
                </a>
                <a class="btn btn-xs btn-slide btn-googleplus" href="mailto:" data-width="80">
                    <i class="far fa-envelope"></i>
                    <span>Mail</span>
                </a>-->
            </div>
        </div>
        <div class="row m-b-20">
            <div class="col-lg-12">
                <span class="post-meta"><i class="fa fa-calendar"></i> <?= fdateHumanFull($edition_data['edition_date'], false); ?></span>
                <span class="post-meta"><i class="fa fa-clock"></i> 
                    <?php
                    echo ftimeSort($edition_data['race_summary']['times']['start']);
                    if ($edition_data['race_summary']['times']['end']) {
                        echo " - " . ftimeSort($edition_data['race_summary']['times']['end']);
                    }
                    ?>
                </span>
                <span class="post-meta"><a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>"><i class="fa fa-map-marker"></i> <address><?= $address; ?></address></a></span>

            </div>
        </div>
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <div class="product">
                    <div class="row m-b-40">
                        <div class="col-lg-5">
                            <div class="product-image">
                                <!-- Carousel slider -->
                                <div class="carousel dots-inside dots-dark arrows-visible arrows-only arrows-dark" data-items="1" data-loop="true" data-autoplay="true" data-animate-in="fadeIn" data-animate-out="fadeOut" data-autoplay-timeout="2500" data-lightbox="gallery">
                                    <?php
                                    if (isset($file_list[1])) {
                                        echo "<img src='" . base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'] . "' title='" . $edition_data['edition_name'] . " Logo' />";
                                    }
                                    // potential to add photos here
                                    ?>
<!--                                    <a href="images/shop/products/product-large.jpg" data-lightbox="image" title="Shop product image!"><img alt="Shop product image!" src="images/shop/products/1.jpg">
                                    </a>
                                    <a href="images/shop/products/product-large.jpg" data-lightbox="image" title="Shop product image!"><img alt="Shop product image!" src="images/shop/products/2.jpg">
                                    </a>-->
                                </div>
                                <!-- Carousel slider -->
                            </div>
                        </div>


                        <div class="col-lg-7">
                            <?php
                            echo $edition_data['edition_intro_detail'];
                            ?>
                            <div class="product-description">
                                <div class="product-category"><?= $edition_data['annual_name']; ?></div>
                                <div class="product-title">
                                    <h3><a href="#">Summary</a></h3>
                                </div>
                                <?php
                                if (($edition_data['race_summary']['fees']['from'] > 0) && ($edition_data['race_summary']['fees']['from'] < 10000)) {
                                    ?>
                                    <div class="product-price"><ins>R<?= intval($edition_data['race_summary']['fees']['from']); ?> - R<?= intval($edition_data['race_summary']['fees']['to']); ?></ins></div>
                                    <?php
                                }
                                ?>
                                <!--                                <div class="product-rate">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-half"></i>
                                                                </div>
                                                                <div class="product-reviews"><a href="#">3 customer reviews</a>
                                                                </div>-->

                                <div class="seperator m-b-10"></div>
                                <h3>
                                    <?php
                                    foreach ($edition_data['race_summary']['list'] as $race) {
                                        echo '<span class="badge badge-' . $race['color'] . '">' . floatval($race['distance']) . '<small>km</small><br><small>' . $race['type'] . '</small></span> ';
                                    }
                                    ?>
                                </h3>
                                <div class="seperator m-t-20 m-b-10"></div>

                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-light btn-creative btn-icon-holder btn-shadow btn-light-hover">Entry Details
                                        <i class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- add box -->
                    <div class="row m-b-30">
                        <div class="col-lg-12">
                            <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                        </div>
                    </div>

                    <!-- Product additional tabs -->
                    <div class="tabs tabs-folder">
                        <ul class="nav nav-tabs" id="race_tabs" role="tablist">
                            <?php
                            foreach ($race_list as $race_id => $race) {
                                $active = '';
                                if ($race_id === array_key_first($race_list)) {
                                    $active = "active show";
                                }
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $active; ?>" id="race-<?= $race_id; ?>-tab" data-toggle="tab" href="#race<?= $race_id; ?>" role="tab" aria-controls="<?= $race_id; ?>" aria-selected="false">
                                        <i class="fa fa-<?= $race['racetype_icon']; ?>"></i><?= $race['race_name']; ?></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <div class="tab-content" id="race-tab-content">
                            <?php
                            foreach ($race_list as $race_id => $race) {
                                $active = '';
                                if ($race_id === array_key_first($race_list)) {
                                    $active = "active show";
                                }
                                ?>
                                <div class="tab-pane fade <?= $active; ?>" id="race<?= $race_id; ?>" role="tabpanel" aria-labelledby="race-<?= $race_id; ?>-tab">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <td style='width: 50%;'>Date</td>
                                                <td><?= fdateHuman($race['race_date']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Start Time</td>
                                                <td><?= ftimeSort($race['race_time_start']); ?></td>
                                            </tr>
                                            <?php
                                            if ($race['race_time_end'] > 0) {
                                                ?>
                                                <tr>
                                                    <td>Cut-off Time</td>
                                                    <td><?= ftimeSort($race['race_time_end']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                <td>Distance</td>
                                                <td><span class="badge badge-<?= $race['race_color']; ?>"><?= fraceDistance($race['race_distance']); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Race Type</td>
                                                <td><?= $race['racetype_name']; ?></td>
                                            </tr>
                                            <?php
                                            if ($race['race_fee_flat'] > 0) {
                                                ?>
                                                <tr>
                                                    <td>Race fee:</td>
                                                    <td>R<?= floatval($race['race_fee_flat']); ?></td>
                                                </tr>
                                                <?php
                                            } else {
                                                if ($race['race_fee_senior_licenced'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Senior Licensed Runner:</td>
                                                        <td>R<?= floatval($race['race_fee_senior_licenced']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                if ($race['race_fee_senior_unlicenced'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Senior Unlicensed Runner:</td>
                                                        <td>R<?= floatval($race['race_fee_senior_unlicenced']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                if ($race['race_fee_junior_licenced'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Junior Licensed Runner:</td>
                                                        <td>R<?= floatval($race['race_fee_junior_licenced']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                if ($race['race_fee_junior_unlicenced'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Junior Unlicensed Runner:</td>
                                                        <td>R<?= floatval($race['race_fee_junior_unlicenced']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            if ($race['race_isover70free']) {
                                                ?>
                                                <tr>
                                                    <td>Licensed Athlete 70+:</td>
                                                    <td>Free</td>
                                                </tr>
                                                <?php
                                            }
                                            if ($race['race_minimum_age'] > 0) {
                                                ?>
                                                <tr>
                                                    <td>Minimum age:</td>
                                                    <td><?= $race['race_minimum_age']; ?> years</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>


                    <!--                    <div class="heading-text heading-line text-center m-t-30">
                                            <h4>Event Reviews</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="comments" id="comments">
                                                    <div class="comment-list">
                                                         Comment 
                                                        <div class="comment" id="comment-1">
                                                            <div class="image"><img alt="" src="images/blog/author.jpg" class="avatar"></div>
                                                            <div class="text">
                                                                <div class="product-rate">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-half"></i>
                                                                </div>
                                                                <h5 class="name">John Doe</h5>
                                                                <span class="comment_date">Posted at 15:32, 06 December</span>
                                                                <a class="comment-reply-link" href="#">2019 edition</a>
                                                                <div class="text_holder">
                                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                         end: Comment 
                                                         Comment 
                                                        <div class="comment" id="comment-1-1">
                                                            <div class="image"><img alt="" src="images/blog/author2.jpg" class="avatar"></div>
                                                            <div class="text">
                                                                <div class="product-rate">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                </div>
                                                                <h5 class="name">John Doe</h5>
                                                                <span class="comment_date">Posted at 15:32h, 06 December</span>
                                                                <a class="comment-reply-link" href="#">Reply</a>
                                                                <div class="text_holder">
                                                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                         end: Comment 
                                                         Comment 
                                                        <div class="comment" id="comment-1-2">
                                                            <div class="image"><img alt="" src="images/blog/author3.jpg" class="avatar"></div>
                                                            <div class="text">
                                                                <div class="product-rate">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-half"></i>
                                                                </div>
                                                                <h5 class="name">John Doe</h5>
                                                                <span class="comment_date">Posted at 15:32h, 06 December</span>
                                                                <a class="comment-reply-link" href="#">Reply</a>
                                                                <div class="text_holder">
                                                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                         end: Comment 
                    
                                                    </div>
                                                </div>
                                            </div>
                    
                    
                                        </div>-->
                    <!-- end: Product additional tabs -->
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Receive race notification";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // TAGS WIDGET
                $this->load->view('widgets/tags');

                // ADS WIDGET
                $this->load->view('widgets/side_ad');

                // MAP WIDGET
                $this->load->view('widgets/map');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->