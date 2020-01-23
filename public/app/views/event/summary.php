<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2><?= $edition_data['edition_name']; ?>
                    <span class="fa fa-<?= $status_notice['icon']; ?> text-<?= $status_notice['state']; ?>" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?= $status_notice['short_msg']; ?>"></span>
                </h2>
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
        <?php
        $this->load->view('widgets/race_meta');
        ?>        
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
                                    <h3>Summary</h3>
                                </div>
                                <?php
                                // FEE SUMMARY
                                if ($edition_data['race_summary']['fees']['from'] == $edition_data['race_summary']['fees']['to']) {
                                    ?>
                                    <div class="product-price"><ins>R<?= intval($edition_data['race_summary']['fees']['from']); ?></ins></div>
                                    <?php
                                } elseif (($edition_data['race_summary']['fees']['from'] > 0) && ($edition_data['race_summary']['fees']['from'] < 10000)) {
                                    ?>
                                    <div class="product-price"><ins>R<?= intval($edition_data['race_summary']['fees']['from']); ?> - R<?= intval($edition_data['race_summary']['fees']['to']); ?></ins></div>
                                    <?php
                                }
                                ?>
                                <!--                                
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half"></i>
                                    </div>
                                    <div class="product-reviews"><a href="#">3 customer reviews</a>
                                    </div>
                                -->
                                <div class="seperator m-b-10"></div>
                                <div id="race_badges">
                                    <?php
                                    foreach ($edition_data['race_summary']['list'] as $race) {
                                        echo '<h3><a href="' . base_url('event/' . $slug . '/distances/' . url_title($race['name'])) . '"><span class="badge badge-' . $race['color'] . '">' . floatval($race['distance']) . '<small>km</small><br>'
                                        . '<small>' . $race['type'] . '</small></span></a></h3>';
                                    }
                                    ?>
                                </div>
                                <div class="seperator m-t-10 m-b-10"></div>
                                <div class="col-lg-12"> 
                                    <?php
                                    // ASA MEMBERSHIP
                                    if ($edition_data['asa_member_id'] > 0) {
                                        echo "<p style='font-size: 0.9em;'>This event is held under the rules and regulations of "
                                        . "<u><a href='https://www.athletics.org.za/' target='_blank' title='Athletics South Africa'>ASA</a></u> "
                                        . "and <u><a href='" . $edition_data['asa_member_url'] . "' target='_blank' title='" . $edition_data['asa_member_abbr'] . "'>"
                                        . "" . $edition_data['asa_member_name'] . "</a></u></p>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    if (!in_array(5, $edition_data['entrytype_list'])) {
                                        ?>
                                        <a class="btn btn-light btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/entries"); ?>">Entry Details
                                            <i class="fa fa-arrow-right"></i></a>

                                        <?php
                                    }
                                    ?>
                                    <a class="btn btn-light btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/race-day-information"); ?>">Race day info
                                        <i class="fa fa-arrow-right"></i></a>
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

                    <div class="row m-b-20">
                        <div class="col-lg-12">
                            <div class="accordion accordion-shadow">
                                <?php
                                foreach ($race_list as $race_id => $race) {
                                    $active = '';
                                    if ($race_id === array_key_first($race_list)) {
                                        $active = "ac-active";
                                    }
                                    ?>
                                    <div class="ac-item <?= $active; ?>">
                                        <h5 class="ac-title"><i class="fa fa-<?= $race['racetype_icon']; ?>"></i><?= $race['race_name']; ?></h5>
                                        <div class="ac-content">
                                            <table class="table table-striped table-bordered race_table">
                                                <tbody>
                                                    <tr>
                                                        <td style='width: 50%;'>Date</td>
                                                        <td colspan="2">
                                                            <?php
                                                            if (strtotime($race['race_date']) != strtotime($edition_data['edition_date'])) {
                                                                echo "<b class='text-danger'>" . fdateHuman($race['race_date']) . "</b>";
                                                            } else {
                                                                echo fdateHuman($race['race_date']);
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Time</td>
                                                        <td colspan="2">Start: <b><?= ftimeMil($race['race_time_start']); ?></b>
                                                            <?php
                                                            if ($race['race_time_end'] > 0) {
                                                                ?>
                                                            <br>Cut-off: <b><?= ftimeMil($race['race_time_end']); ?></b>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Distance</td>
                                                        <td colspan="2"><span class="badge badge-<?= $race['race_color']; ?>"><?= fraceDistance($race['race_distance']); ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Race Type</td>
                                                        <td colspan="2"><?= $race['racetype_name']; ?></td>
                                                    </tr>
                                                    <?php
                                                    if ($race['race_minimum_age'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Minimum age:</td>
                                                            <td colspan="2"><?= $race['race_minimum_age']; ?> years</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    if ($race['race_entry_limit'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Entry limit for the race:</td>
                                                            <td colspan="2"><?= $race['race_entry_limit']; ?></td>
                                                        </tr>
                                                        <?php
                                                    }

                                                    // FEES
                                                    if (($race['race_fee_flat'] > 0) || ($race['race_fee_senior_licenced'] > 0)) {
                                                        $info_text="Races that are ran under the rules and regulations of the ASA (10km+) requires you to have a running license. You can either buy a temporarily license for the race you want to enter, or join a running club and purchase a permanent license number for the year. If you run more that 3x 10km+ races a year, it starts making financial sense to get a permanent number.";
                                                        ?>
                                                        <tr>
                                                            <th></th>
                                                            <th><a href="<?= base_url("faq/license#license");?>" title="<?=$info_text;?>">Licensed <i class="fa fa-info-circle"></i></a></th>
                                                            <th><a href="<?= base_url("faq/license#license");?>" title="<?=$info_text;?>">Unlicensed <i class="fa fa-info-circle"></i></a></th>
                                                        </tr>
                                                        <?php
                                                    }
                                                    if ($race['race_fee_flat'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Race fee:</td>
                                                            <td>R<?= floatval($race['race_fee_flat']); ?></td>
                                                            <td>R<?= floatval($race['race_fee_flat']); ?></td>
                                                        </tr>
                                                        <?php
                                                    } else {
                                                        if ($race['race_fee_senior_licenced'] > 0) {
                                                            ?>
                                                            <tr>
                                                                <td>Entry fees:</td>
                                                                <td>R<?= floatval($race['race_fee_senior_licenced']); ?></td>
                                                                <td>R<?= floatval($race['race_fee_senior_unlicenced']); ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        if ($race['race_fee_junior_licenced'] > 0) {
                                                            ?>
                                                            <tr>
                                                                <td>Junior (<20) Entry Fees:</td>
                                                                <td>R<?= floatval($race['race_fee_junior_licenced']); ?></td>
                                                                <td>R<?= floatval($race['race_fee_junior_unlicenced']); ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    if ($race['race_isover70free']) {
                                                        $fee70 = $race['race_fee_senior_unlicenced'] - $race['race_fee_senior_licenced'];
                                                        ?>
                                                        <tr>
                                                            <td>Great Grandmasters (70+):</td>
                                                            <td>Free</td>
                                                            <td>R<?= $fee70; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <ul>
                                                <?php
                                                // LIMIT
                                                if ($race['race_entry_limit'] > 0) {
                                                    echo "<li>The entry limit for this race <strong>" . $race['race_entry_limit'] . "</strong> entrants</li>";
                                                }
                                                // LIMIT
                                                if (!empty($race['race_address'])) {
                                                    echo "<li><span style='color:red'><strong>NOTE</strong></span> that the <strong>starting address</strong> for this race differs from the end address:<br><strong>" . $race['race_address'] . "</strong></li>";
                                                }
                                                ?>
                                            </ul>
                                            <?= $race['race_notes']; ?>
                                            <div>
                                                <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/entries"); ?>" class="btn btn-light btn-sm">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Entry Details</a>

                                                <a href="<?= base_url("training-programs/" . url_title($race['race_name'])); ?>" class="btn btn-light btn-sm">
                                                    <i class="fa fa fa-running"></i> Training Programs</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="heading-text heading-line text-center m-t-30">
                        <h4>Race Organisers info</h4>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <p>
                                <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/contact"); ?>" class="btn btn-light">
                                    <i class="fa fa-envelope-open" aria-hidden="true"></i>&nbsp;Contact Race Organisers</a>
                                <?php
                                if (!$in_past) {
                                    ?>
                                    <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/accommodation"); ?>" class="btn btn-light">
                                        <i class="fa fa-bed"></i> Get Accommodation</a>
                                    <?php
                                }
                                ?>
                            </p>
                            <?php
                            if ($edition_data['club_id'] != 8) {
                                ?>
                                <p>This event is organised by the 
                                    <?php
                                    if (isset($edition_data['club_url_list'][0])) {
                                        echo "<a href='" . $edition_data['club_url_list'][0]['url_name'] . "' target='_blank' title='Visit club website' class='link'>" . $edition_data['club_name'] . "</a>";
                                    } else {
                                        echo $edition_data['club_name'];
                                    }
                                    ?>
                                </p>
                                <?php
                            }
                            ?>
                            <p class="contact_info">
                                <i class="fa fa-envelope"></i> <a href="mailto:<?= $edition_data['user_email']; ?>"><?= $edition_data['user_email']; ?></a>
                                <?php
                                if ($edition_data['user_contact']) {
                                    ?>
                                    <br><i class="fa fa-phone"></i> <?= fphone($edition_data['user_contact']); ?>
                                    <?php
                                }
                                ?>
                            </p>
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
                <div role="alert" class="m-b-30 alert alert-<?= $status_notice['state']; ?>">
                    <strong><i class="fa fa-<?= $status_notice['icon']; ?>"></i> <?= $status_notice['msg']; ?> </div>
                    <?php
                    // SUBSCRIBE WIDGET
                    $data_to_widget['title'] = "Receive race notification";
                    $this->load->view('widgets/subscribe', $data_to_widget);

                    // ADD TO CALENDAR WIDGET
                    $this->load->view('widgets/add_calendar');

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