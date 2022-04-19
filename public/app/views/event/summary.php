<section id="page-content" class="sidebar-right">
  <div class="container">
    <div class="row m-b-5">
      <div class="col-lg-10">
        <h2><?= $edition_data['edition_name']; ?>
          <span class="fa fa-<?= $status_notice['icon']; ?> text-<?= $status_notice['state']; ?>" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?= $status_notice['short_msg']; ?>"></span>
        </h2>
      </div>

    </div>
    <?php $this->load->view('widgets/race_meta'); ?>
    <div class="row m-t-40">
      <!-- Content-->
      <div class="content col-lg-9" style="margin-bottom: 0;">
        <div class="product" style="margin-bottom: 0;">
          <div class="row m-b-10">
            <div class="col-lg-5">
              <div class="product-image m-b-20">
                <!-- Carousel slider -->
                <div class="carousel dots-inside dots-dark arrows-visible arrows-only arrows-dark" data-items="1" data-loop="true" data-autoplay="true" data-animate-in="fadeIn" data-animate-out="fadeOut" data-autoplay-timeout="2500" data-lightbox="gallery">
                  <?php
                  if (isset($file_list[1])) {
                    echo "<img src='" . base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'] . "' title='" . $edition_data['edition_name'] . " Logo' />";
                  }
                  // potential to add photos here
                  ?>
                  <!-- <a href="images/shop/products/product-large.jpg" data-lightbox="image" title="Shop product image!"><img alt="Shop product image!" src="images/shop/products/1.jpg">
                  </a>
                  <a href="images/shop/products/product-large.jpg" data-lightbox="image" title="Shop product image!"><img alt="Shop product image!" src="images/shop/products/2.jpg">
                  </a> -->
                </div>
                <!-- Carousel slider -->
              </div>
            </div>


            <div class="col-lg-7">
              <?php
              // if (($edition_data['edition_status'] == 1) || ($edition_data['edition_status'] == 17)) {
              if (strlen($edition_data['edition_intro_detail']) > 13) {
                echo $edition_data['edition_intro_detail'];
              }

              if (isset($edition_data['sponsor_list'])) {
                echo "<p><b>Sponsored</b> by ";
                foreach ($edition_data['sponsor_list'] as $sponsor_id => $sponsor) {
                  if (!empty($sponsor['url_name'])) {
                    echo "<a href='" . $sponsor['url_name'] . "' title='Visit sponsor website' class='link'>" . $sponsor['sponsor_name'] . "</a> ";
                  } else {
                    echo $sponsor['sponsor_name'] . " ";
                  }
                }
                echo "</p>";
              }
              // }
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

                <!--                                    <div class="product-rate">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half"></i>
                                                    </div>
                                                    <div class="product-reviews"><a href="#">3 customer reviews</a>
                                                    </div>
                -->
                <!-- <div class="seperator m-b-10"></div> -->
                <div class="m-b-20"></div>
                <div id="race_badges">
                  <?php
                  foreach ($edition_data['race_summary']['list'] as $race) {
                    echo '<h3><a href="' . base_url('event/' . $slug . '/distances/' . url_title($race['name'])) . '"><span class="badge badge-' . $race['color'] . '">' . fraceDistance($race['distance'], true) . '<br>'
                      . '<small>' . $race['type'] . '</small></span></a></h3>';
                  }
                  ?>
                </div>
                <div class="seperator m-t-10 m-b-10"></div>
                <!-- <div class="col-lg-12">  -->
                <p style='font-size: 0.9em;'>
                  <?php
                  // CLUB
                  if ($edition_data['club_id'] != 8) {
                  ?>
                    Organised by the
                    <?php
                    if (isset($edition_data['club_url_list'][0])) {
                      echo "<a href='" . $edition_data['club_url_list'][0]['url_name'] . "' target='_blank' title='Visit club website' class='link'>" . $edition_data['club_name'] . "</a>";
                    } else {
                      echo $edition_data['club_name'];
                    }
                    ?>
                    <br>
                  <?php
                  }
                  // ASA MEMBERSHIP
                  if ($edition_data['asa_member_id'] > 0) {
                    echo "Held under the rules and regulations of "
                      . "<u><a href='https://www.athletics.org.za/' target='_blank' title='Athletics South Africa'>ASA</a></u> "
                      . "and <u><a href='" . $edition_data['asa_member_url'] . "' target='_blank' title='" . $edition_data['asa_member_abbr'] . "'>" . "" . $edition_data['asa_member_name'] . "</a></u>";
                  }
                  ?>
                </p>
                <!-- </div> -->
              </div>
              <?php
              if (($edition_data['edition_status'] == 1) || ($edition_data['edition_status'] == 17)) {
                if (!$in_past) {
              ?>
                  <div class="row">
                    <div class="col-lg-12">
                      <?php
                      if ($edition_data['edition_status'] == 17) {
                      ?>
                        <a class="btn btn-primary btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/race-day-information"); ?>">How do I track my run?
                          <i class="fa fa-arrow-right"></i></a>
                      <?php
                      }
                      // BUTTONS
                      if (!in_array(5, $edition_data['entrytype_list'])) {
                        switch ($online_entry_status) {
                          case "open":
                            $btn_text = "Entries Open!";
                            $btn_type = "success";
                            break;
                          case "closed":
                            $btn_text = "Entries Closed";
                            $btn_type = "danger";
                            break;
                          default:
                            $btn_text = "Entry Details";
                            $btn_type = "light";
                            break;
                        }
                      ?>
                        <a class="btn btn-<?= $btn_type; ?> btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/entries"); ?>"><?= $btn_text; ?>
                          <i class="fa fa-arrow-right"></i></a>
                      <?php
                      }
                      ?>
                      <a class="btn btn-light btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/race-day-information"); ?>">Race day info
                        <i class="fa fa-arrow-right"></i></a>
                    </div>
                  </div>
                  <div class="row m-b-10">
                    <div class="col-lg-12">
                      <?php
                      // flyer
                      if (isset($flyer['edition'])) {
                      ?>
                        <a href="<?= $flyer['edition']['url']; ?>" class="btn btn-light">
                          <i class="fa fa-<?= $flyer['edition']['icon']; ?>"></i> <?= $flyer['edition']['text']; ?></a>

                      <?php
                      }
                      // website link
                      if (isset($url_list[1])) {
                      ?>
                        <a href="<?= $url_list[1][0]['url_name']; ?>" class="btn btn-light">
                          <i class="fa fa-external-link-alt"></i> Race Website</a>

                      <?php
                      }
                      // press release
                      if (isset($file_list[10])) {
                      ?>
                        <a href="<?= base_url("file/edition/" . $slug . "/press release/" . $this->data_to_views['file_list'][10][0]['file_name']); ?>" class="btn btn-default">
                          <i class="fa fa-file-pdf"></i> Official Press Release</a>

                      <?php
                      }
                      // facebook
                      if (isset($url_list[6])) {
                      ?>
                        <a href="<?= $url_list[6][0]['url_name']; ?>" class="btn btn-default btn-icon-holder btn-facebook" target="_blank">
                          <?= $url_list[6][0]['urltype_buttontext']; ?><i class="fa fa-facebook"></i></a>

                      <?php
                      }
                      // twitter
                      if (isset($url_list[13])) {
                        // wts($url_list[13]);
                      ?>
                        <a href="<?= $url_list[13][0]['url_name']; ?>" class="btn btn-default btn-icon-holder btn-twitter" target="_blank">
                          <?= $url_list[13][0]['urltype_buttontext']; ?><i class="fa fa-twitter"></i></a>

                      <?php
                      }
                      // instagram
                      if (isset($url_list[14])) {
                        // wts($url_list[13]);
                      ?>
                        <a href="<?= $url_list[14][0]['url_name']; ?>" class="btn btn-default btn-icon-holder btn-instagram" target="_blank">
                          <?= $url_list[14][0]['urltype_buttontext']; ?><i class="fa fa-instagram"></i></a>

                      <?php
                      }
                      ?>
                    </div>
                  </div>

                  <?php
                  if (isset($date_list[3])) {
                  ?>
                    <div class="row">
                      <div class="col-lg-12">
                        <?php
                        // check vir closing date
                        if (strtotime($date_list[3][0]['date_end']) != strtotime($edition_data['edition_date'])) {
                          $d = '';
                          // check if already closed
                          if (strtotime($date_list[3][0]['date_end']) < time()) {
                            $d = "d";
                          }
                          echo "<div class='alert alert-info' role='alert'><i class='fa fa-info-circle' aria-hidden='true'></i> Online entries close$d on <b>" . fdateEntries($date_list[3][0]['date_end']) . "</b></div>";
                        }
                        ?>
                      </div>
                    </div>
                  <?php
                  }
                } else {
                  // IF IN PAST        
                  // results button        
                  ?>
                  <div class="row">
                    <div class="col-lg-12">
                      <a class="btn btn-default btn-icon-holder btn-creative" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/results"); ?>">
                        View results
                        <i class="fa fa-arrow-right" style="position: relative; right: -12px;"></i></a>
                    </div>
                  </div>
                  <?php
                  // photos button
                  if (isset($url_list[9])) {
                  ?>
                    <div class="row">
                      <div class="col-lg-12">
                        <a class="btn btn-secondary btn-icon-holder" href="<?= $url_list[9][0]['url_name']; ?>" target="_blank">
                          <?= $url_list[9][0]['urltype_buttontext']; ?>
                          <i class="fa fa-arrow-right"></i></a>
                      </div>
                    </div>
              <?php
                  }
                }
              }
              ?>
            </div>
          </div>

          <!-- ad box -->
          <div class="row m-b-30">
            <div class="col-lg-12">
              <?php
              // LANDSCAPE ADS WIDGET
              $this->load->view('widgets/horizontal_ad');
              ?>
            </div>
          </div>


          <?php
          if (!$in_past) {
          ?>
            <div class="row m-b-20">
              <div class="col-lg-12">
                <div class="accordion accordion-shadow">
                  <?php
                  foreach ($race_list as $race_id => $race) {
                    $ac_data['race'] = $race;
                    $ac_data['active'] = '';
                    if ($race_id === array_key_first($race_list)) {
                      $ac_data['active'] = "ac-active";
                    }

                    $this->load->view('widgets/race_accordion_item', $ac_data);
                  }
                  ?>
                </div>
              </div>
            </div>

            <!-- ENTRIES info -->
            <div class="heading-text heading-line">
              <h4 class="text-uppercase">How to enter</h4>
            </div>
            <?php
            $this->load->view('event/content/entries');
            ?>

            <!-- RACE DAY info -->
            <?php
            // if you touvh this, update the race-day-info view as well
            if (
              (strlen($edition_data['edition_general_detail']) > 10) ||
              ($edition_data['edition_info_medals']) ||
              ($edition_data['edition_info_togbag']) ||
              ($edition_data['edition_info_headphones']) ||
              ($edition_data['edition_info_prizegizing'] != "00:00:00")
            ) {
              $this->load->view('event/content/race-day-information');
            }

            // virtual check
            if ($edition_data['edition_status'] != 17) {
            ?>

              <!-- Accommodation -->
              <div class="heading-text heading-line">
                <h4 class="text-uppercase">Map</h4>
              </div>
              <?php
              $this->load->view('event/content/accommodation');
              ?>

              <!-- Route Maps -->
              <div class="heading-text heading-line">
                <h4 class="text-uppercase">Race Route Maps</h4>
              </div>
              <?php
              $this->load->view('event/content/route-maps');
              ?>

          <?php
            }
          }
          ?>
          <!-- race organisers info -->
          <div class="heading-text heading-line m-t-50">
            <h4 class="text-uppercase">Race Organisers info</h4>
          </div>
          <div class="row m-b-60">
            <div class="col-lg-12">
              <p class="contact_info">
                <?php
                if ($edition_data['club_id'] != 8) {
                ?>
                  This event is organised by the
                  <?php
                  if (isset($edition_data['club_url_list'][0])) {
                    echo "<a href='" . $edition_data['club_url_list'][0]['url_name'] . "' target='_blank' title='Visit club website' class='link'>" . $edition_data['club_name'] . "</a>";
                  } else {
                    echo $edition_data['club_name'];
                  }
                  ?>
                  <br>
                <?php
                }
                ?>
                <i class="fa fa-envelope"></i> <a href="mailto:<?= $edition_data['user_email']; ?>?subject=<?= $edition_data['event_name']; ?> query from roadrunning.co.za"><?= $edition_data['user_email']; ?></a>
                <?php
                if ($edition_data['user_contact']) {
                ?>
                  <br><i class="fa fa-phone"></i> <?= fphone($edition_data['user_contact']); ?>
                <?php
                }
                ?>
              </p>
              <p>Got a question regarding this race? Use the form below to contact the race organisers directly. You will receive a copy of the email in your inbox as well.</p>
              <div class="m-t-30">
                <?php
                $attributes = array('class' => 'contact_form', 'role' => 'form');
                echo form_open($contact_url, $attributes);
                ?>
                <div class="row">
                  <div class="form-group col-md-3">
                    <?php
                    echo form_label('Name *', 'user_name');
                    echo form_input([
                      'name' => 'user_name',
                      'id' => 'user_name',
                      'value' => set_value('user_name'),
                      'class' => 'form-control required',
                      'placeholder' => 'Enter your Name',
                      'required' => '',
                    ]);
                    ?>
                  </div>
                  <div class="form-group col-md-4">
                    <?php
                    echo form_label('Surname *', 'user_surname');
                    echo form_input([
                      'name' => 'user_surname',
                      'id' => 'user_surname',
                      'value' => set_value('user_surname'),
                      'class' => 'form-control required',
                      'placeholder' => 'Enter your Surname',
                      'required' => '',
                    ]);
                    ?>
                  </div>
                  <div class="form-group col-md-5">
                    <?php
                    echo form_label('Email', 'user_email');
                    echo form_input([
                      'name' => 'user_email',
                      'id' => 'user_email',
                      'type' => 'email',
                      'value' => set_value('user_email'),
                      'class' => 'form-control required',
                      'placeholder' => 'Enter your Email',
                      'required' => '',
                    ]);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <?php
                  echo form_label('Query', 'user_message');
                  echo form_textarea([
                    'name' => 'user_message',
                    'id' => 'user_message',
                    'value' => set_value('user_message'),
                    'class' => 'form-control required',
                    'placeholder' => 'Enter your Query',
                    'required' => '',
                    'rows' => 5,
                  ]);
                  // hidden field for the slug
                  echo form_input([
                    'name' => 'edition_name',
                    'type' => 'hidden',
                    'value' => $edition_data['edition_name'],
                  ]);
                  ?>
                </div>
                <div class="form-group g-recaptcha" data-sitekey="6LcxdoYUAAAAAADszn1zvLq3C9UFfwnafqzMWYoV"></div>
                <?php
                $data = array(
                  'id' => 'form-submit',
                  'type' => 'submit',
                  'content' => '<i class="fa fa-paper-plane"></i>&nbsp;Send message',
                  'class' => 'btn',
                );
                echo form_button($data);
                $data = array(
                  'id' => 'form-clear',
                  'type' => 'reset',
                  'content' => '<i class="fa fa-eraser"></i>&nbsp;Clear',
                  'class' => 'btn btn-light',
                );
                echo form_button($data);
                echo form_close();
                ?>
              </div>
            </div>
          </div>

          <!-- <div class="heading-text heading-line text-center m-t-30">
            <h4>Event Reviews</h4>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="comments" id="comments">
                <div class="comment-list">
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

                </div>
              </div>
            </div> 
          </div>-->


        </div>

      </div>
      <!-- end: Content-->

      <!-- Sidebar-->
      <div class="sidebar col-lg-3">
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
        // if not virtual
        if ($edition_data['edition_status'] != 17) {
          $this->load->view('widgets/map');
        }

        // RACE STATUS
        // if ($edition_data['edition_status'] == 1) {
        //     $this->load->view('widgets/race_status', $status_notice);
        //     echo "<div class='m-b-30'></div>";
        // }
        ?>
      </div>
      <!-- end: Sidebar-->
    </div>


    <!-- more info row -->
    <div class="row">
      <div class="col-lg-12">
        <!-- more race info -->
        <div class="heading-text heading-line m-t-30">
          <h4 class="text-uppercase">More race information</h4>
        </div>

        <div class="row m-b-40">
          <div class="col-lg-12">
            <?php
            if ($edition_data['edition_status'] == 17) {
            ?>
              <a class="btn btn-primary btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/race-day-information"); ?>">How do I track my run?
                <i class="fa fa-arrow-right"></i></a>
            <?php
            }
            // BUTTONS
            if (!in_array(5, $edition_data['entrytype_list'])) {
              switch ($online_entry_status) {
                case "open":
                  $btn_text = "Entries Open!";
                  $btn_type = "success";
                  break;
                case "closed":
                  $btn_text = "Entries Closed";
                  $btn_type = "danger";
                  break;
                default:
                  $btn_text = "Entry Details";
                  $btn_type = "light";
                  break;
              }
            ?>
              <a class="btn btn-<?= $btn_type; ?> btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/entries"); ?>"><?= $btn_text; ?>
                <i class="fa fa-arrow-right"></i></a>
            <?php
            }
            ?>
            <a class="btn btn-light btn-icon-holder" href="<?= base_url("event/" . $edition_data['edition_slug'] . "/race-day-information"); ?>">Race day info
              <i class="fa fa-arrow-right"></i></a>
          </div>
          <div class="col-lg-6">
            <div class="row">
              <div class="col-lg-12">
                <?php
                if (isset($flyer['edition'])) {
                ?>
                  <a href="<?= $flyer['edition']['url']; ?>" class="btn btn-light">
                    <i class="fa fa-<?= $flyer['edition']['icon']; ?>"></i> <?= $flyer['edition']['text']; ?></a>

                <?php
                }
                if (isset($url_list[1])) {
                ?>
                  <a href="<?= $url_list['1'][0]['url_name']; ?>" class="btn btn-light">
                    <i class="fa fa-external-link-alt"></i> Race <?= $url_list['1'][0]['urltype_buttontext']; ?></a>

                <?php
                }
                ?>
              </div>
            </div>

            <?php
            // running mann link
            if (isset($url_list[10])) {
            ?>
              <div class="row">
                <div class="col-lg-12">
                  <a href="<?= $url_list['10'][0]['url_name']; ?>" class="btn btn-light" title="<?= $url_list['10'][0]['urltype_helptext']; ?>">
                    <i class="fa fa-external-link-alt"></i> <i class="fa fa-running"></i> <?= $url_list['10'][0]['urltype_buttontext']; ?></a>
                </div>
              </div>
            <?php
            }
            ?>
          </div>
          <div class="col-lg-6">
            <?php
            // VIDEO
            // You Tube
            if (isset($url_list[11])) {
              // wts($url_list[11]);
              $pos = strpos($url_list[11][0]['url_name'], "=");
              $video_code = substr($url_list[11][0]['url_name'], $pos + 1);
              // echo $video_code;
              // echo $pos;
            ?>
              <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/<?= $video_code; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php
            }
            if (isset($file_list[11])) {
              $video_url = $flyer_list['edition']['url'] = base_url("file/edition/" . $slug . "/video/" . $this->data_to_views['file_list'][11][0]['file_name']);
              // wts($file_list[11]);
              // add code for local video here
            ?>
              <video src="<?= $video_url; ?>" width="100%" controls></video>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end: Shop products -->