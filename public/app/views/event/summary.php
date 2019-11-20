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
                <span class="post-meta"><a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>"><i class="fa fa-map-marker"></i> <address><?= $edition_data['edition_address'] . ", " . $edition_data['town_name']; ?></address></a></span>

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
                                        echo "<img src='" . base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'] . "' />";
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
                                <div class="product-category"><?= $edition_data['annual_name'];?></div>
                                <div class="product-title">
                                    <h3><a href="#">Summary</a></h3>
                                </div>
                                <?php
                                if ($edition_data['annual_name']) {
                                ?>
                                <div class="product-price">from <ins>R<?= intval($edition_data['race_summary']['fees']['from']);?></ins></div>
                                <?php
                                }
                                ?>
                                <div class="product-rate">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half"></i>
                                </div>
                                <div class="product-reviews"><a href="#">3 customer reviews</a>
                                </div>

                                <div class="seperator m-b-10"></div>
                                <h3>
                                    <?php
                                    foreach ($edition_data['race_summary']['list'] as $race) {
                                        echo '<span class="badge badge-'.$race['color'].'">'.floatval($race['distance']).'<small>km</small><br><small>'.$race['type'].'</small></span> ';
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

                    <!-- Product additional tabs -->
                    <div class="tabs tabs-folder">
                        <ul class="nav nav-tabs" id="myTab3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="home-tab" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="false"><i class="fa fa-align-justify"></i>Description</a></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="true"><i class="fa fa-info"></i>Additional Info</a></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-star"></i>Reviews</a></a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent3">
                            <div class="tab-pane fade" id="home3" role="tabpanel" aria-labelledby="home-tab">
                                <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. </p>
                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                            </div>
                            <div class="tab-pane fade active show" id="profile3" role="tabpanel" aria-labelledby="profile-tab">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Size</td>
                                            <td>Small, Medium &amp; Large</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td>Pink &amp; White</td>
                                        </tr>
                                        <tr>
                                            <td>Waist</td>
                                            <td>26 cm</td>
                                        </tr>
                                        <tr>
                                            <td>Length</td>
                                            <td>40 cm</td>
                                        </tr>
                                        <tr>
                                            <td>Chest</td>
                                            <td>33 inches</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric</td>
                                            <td>Cotton, Silk &amp; Synthetic</td>
                                        </tr>
                                        <tr>
                                            <td>Warranty</td>
                                            <td>3 Months</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="comments" id="comments">
                                    <div class="comment_number">
                                        Reviews <span>(3)</span>
                                    </div>

                                    <div class="comment-list">
                                        <!-- Comment -->
                                        <div class="comment" id="comment-1">
                                            <div class="image"><img alt="" src="images/blog/author.jpg" class="avatar"></div>
                                            <div class="text">
                                                <div class="product-rate">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-half-o"></i>
                                                </div>
                                                <h5 class="name">John Doe</h5>
                                                <span class="comment_date">Posted at 15:32h, 06 December</span>
                                                <a class="comment-reply-link" href="#">Reply</a>
                                                <div class="text_holder">
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end: Comment -->
                                        <!-- Comment -->
                                        <div class="comment" id="comment-1-1">
                                            <div class="image"><img alt="" src="images/blog/author2.jpg" class="avatar"></div>
                                            <div class="text">
                                                <div class="product-rate">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-half-o"></i>
                                                </div>
                                                <h5 class="name">John Doe</h5>
                                                <span class="comment_date">Posted at 15:32h, 06 December</span>
                                                <a class="comment-reply-link" href="#">Reply</a>
                                                <div class="text_holder">
                                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end: Comment -->
                                        <!-- Comment -->
                                        <div class="comment" id="comment-1-2">
                                            <div class="image"><img alt="" src="images/blog/author3.jpg" class="avatar"></div>
                                            <div class="text">
                                                <div class="product-rate">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-half-o"></i>
                                                </div>
                                                <h5 class="name">John Doe</h5>
                                                <span class="comment_date">Posted at 15:32h, 06 December</span>
                                                <a class="comment-reply-link" href="#">Reply</a>
                                                <div class="text_holder">
                                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end: Comment -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end: Product additional tabs -->
                </div>


                <div class="heading-text heading-line text-center">
                    <h4>Related Products you may be interested!</h4>
                </div>
                <div class="row">
                    <div class="col-lg-4">


                        <div class="widget-shop">
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/10.jpg" alt="Shop product image!">
                                    </a>
                                </div>
                                <div class="product-description">
                                    <div class="product-category">Women</div>
                                    <div class="product-title">
                                        <h3><a href="#">Bolt Sweatshirt</a></h3>
                                    </div>
                                    <div class="product-price"><del>$30.00</del><ins>$15.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/6.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Women</div>
                                    <div class="product-title">
                                        <h3><a href="#">Consume Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/7.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Man</div>
                                    <div class="product-title">
                                        <h3><a href="#">Logo Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">


                        <div class="widget-shop">
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/11.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Man</div>
                                    <div class="product-title">
                                        <h3><a href="#">Logo Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/9.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Women</div>
                                    <div class="product-title">
                                        <h3><a href="#">Consume Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/3.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Man</div>
                                    <div class="product-title">
                                        <h3><a href="#">Logo Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">


                        <div class="widget-shop">
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/1.jpg" alt="Shop product image!">
                                    </a>
                                </div>
                                <div class="product-description">
                                    <div class="product-category">Women</div>
                                    <div class="product-title">
                                        <h3><a href="#">Bolt Sweatshirt</a></h3>
                                    </div>
                                    <div class="product-price"><del>$30.00</del><ins>$15.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/2.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Women</div>
                                    <div class="product-title">
                                        <h3><a href="#">Consume Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="product">
                                <div class="product-image">
                                    <a href="#"><img src="images/shop/products/5.jpg" alt="Shop product image!">
                                    </a>
                                </div>

                                <div class="product-description">
                                    <div class="product-category">Man</div>
                                    <div class="product-title">
                                        <h3><a href="#">Logo Tshirt</a></h3>
                                    </div>
                                    <div class="product-price"><ins>$39.00</ins>
                                    </div>
                                    <div class="product-rate">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">
                <!--widget newsletter-->
                <div class="widget clearfix widget-tags">
                    <h4 class="widget-title">Tags</h4>
                    <div class="tags">
                        <a href="#">Design</a>
                        <a href="#">Portfolio</a>
                        <a href="#">Digital</a>
                        <a href="#">Branding</a>
                        <a href="#">HTML</a>
                        <a href="#">Clean</a>
                        <a href="#">Peace</a>
                        <a href="#">Love</a>
                        <a href="#">CSS3</a>
                        <a href="#">jQuery</a>
                    </div>
                </div>
                <div class="widget clearfix widget-archive">
                    <h4 class="widget-title">Product categories</h4>
                    <ul class="list list-lines">
                        <li><a href="#">Bags</a> <span class="count">(6)</span>
                        </li>
                        <li><a href="#">Jeans</a> <span class="count">(8)</span>
                        </li>
                        <li><a href="#">Shoes</a> <span class="count">(7)</span>
                        </li>
                        <li><a href="#">Sweaters</a> <span class="count">(7)</span>
                        </li>
                        <li><a href="#">T-Shirts</a> <span class="count">(9)</span>
                        </li>
                        <li><a href="#">Tops</a> <span class="count">(10)</span>
                        </li>
                        <li><a href="#">Women</a> <span class="count">(25)</span>
                        </li>
                    </ul>
                </div>
                <div class="widget clearfix widget-shop">
                    <h4 class="widget-title">Latest Products</h4>
                    <div class="product">
                        <div class="product-image">
                            <a href="#"><img src="images/shop/products/10.jpg" alt="Shop product image!">
                            </a>
                        </div>
                        <div class="product-description">
                            <div class="product-category">Women</div>
                            <div class="product-title">
                                <h3><a href="#">Bolt Sweatshirt</a></h3>
                            </div>
                            <div class="product-price"><del>$30.00</del><ins>$15.00</ins>
                            </div>
                            <div class="product-rate">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </div>
                        </div>
                    </div>
                    <div class="product">
                        <div class="product-image">
                            <a href="#"><img src="images/shop/products/6.jpg" alt="Shop product image!">
                            </a>
                        </div>

                        <div class="product-description">
                            <div class="product-category">Women</div>
                            <div class="product-title">
                                <h3><a href="#">Consume Tshirt</a></h3>
                            </div>
                            <div class="product-price"><ins>$39.00</ins>
                            </div>
                            <div class="product-rate">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </div>
                        </div>

                    </div>
                    <div class="product">
                        <div class="product-image">
                            <a href="#"><img src="images/shop/products/7.jpg" alt="Shop product image!">
                            </a>
                        </div>

                        <div class="product-description">
                            <div class="product-category">Man</div>
                            <div class="product-title">
                                <h3><a href="#">Logo Tshirt</a></h3>
                            </div>
                            <div class="product-price"><ins>$39.00</ins>
                            </div>
                            <div class="product-rate">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </div>

                        </div>

                    </div>
                </div>
                
                <div class="widget clearfix widget-newsletter">
                    <form class="form-inline" method="get" action="#">
                        <h4 class="widget-title">Subscribe for Latest Offers</h4>
                        <small>Subscribe to our Newsletter to get Sales Offers &amp; Coupon Codes etc.</small>
                        <div class="input-group">

                            <input type="email" placeholder="Enter your Email" class="form-control required email" name="widget-subscribe-form-email" aria-required="true">
                            <span class="input-group-btn">
                                <button type="submit" class="btn"><i class="fa fa-paper-plane"></i></button>
                            </span>
                        </div>
                    </form>
                </div>


            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->
<?php
if (isset($file_list[1])) {
    echo "<img src='" . base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'] . "' />";
}
wts($edition_data);
wts($race_list);
wts($file_list);
//wts($url_list);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

