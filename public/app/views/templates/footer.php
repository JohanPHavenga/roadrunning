<?php
// check for home page, set a few variables for header changes
if (($this->router->fetch_class() == "main") && ($this->router->fetch_method() == "index")) {
  $home = true;
  $topbar_class = 'topbar-transparent dark';
  $header_vals = 'data-transparent="true" class="dark"';
} else {
  $home = false;
  $topbar_class = '';
  $header_vals = '';
}
?>
<footer id="footer" class="inverted">
  <div class="footer-content">
    <div class="container">
      <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3">
          <!-- Footer widget area 1 -->
          <div class="widget">
            <h4>RACES</h4>
            <ul class="list">
              <?php
              foreach ($this->session->static_pages['races']['sub-menu'] as $key => $page) {
                echo "<li><a href='$page[loc]'>$page[display]</a></li>";
              }
              foreach ($this->session->static_pages['results']['sub-menu'] as $key => $page) {
                echo "<li><a href='$page[loc]'>$page[display]";
                if (isset($page['badge'])) {
                  echo " <span class='badge badge-danger'>$page[badge]</span>";
                }
                echo "</a></li>";
              }
              ?>
            </ul>
          </div>
          <!-- end: Footer RACES -->
        </div>

        <div class="col-xl-2 col-lg-2 col-md-3">
          <!-- Footer widget area 4 -->
          <div class="widget">
            <h4>PAGES</h4>
            <ul class="list">
              <li><a href='<?= $this->session->static_pages['resources']['sub-menu']['training']['loc']; ?>'><?= $this->session->static_pages['resources']['sub-menu']['training']['display']; ?></a></li>
              <li><a href='<?= $this->session->static_pages['resources']['sub-menu']['faq']['loc']; ?>'><?= $this->session->static_pages['resources']['sub-menu']['faq']['display']; ?></a></li>
              <li><a href='<?= $this->session->static_pages['about']['loc']; ?>'><?= $this->session->static_pages['about']['display']; ?></a></li>
              <?php
              $url_bits = explode("/", uri_string());
              if ($url_bits[0] == "event") {
                echo "<li><a href='" . base_url("event/" . $slug . "/contact") . "'>Contact Organisers</a></li>";
                $contact_disp = "Contact Web Admin";
              } else {
                $contact_disp = "Contact Us";
              }
              ?>
              <li><a href='<?= $this->session->static_pages['contact']['loc']; ?>'><?= $contact_disp; ?></a></li>
              <li><a href='<?= $this->session->static_pages['add-listing']['loc']; ?>'><?= $this->session->static_pages['add-listing']['display']; ?></a></li>
              <li><a href='<?= $this->session->static_pages['search']['loc']; ?>'><?= $this->session->static_pages['search']['display']; ?></a></li>
              <li><a href='<?= $this->session->static_pages['contact']['sub-menu']['support']['loc']; ?>'><?= $this->session->static_pages['contact']['sub-menu']['support']['display']; ?></a></li>
              <li><a href='<?= $this->session->static_pages['sitemap']['loc']; ?>'><?= $this->session->static_pages['sitemap']['display']; ?></a></li>
              <!--<li><a href='<?= base_url('old'); ?>'>Back to old site</a></li>-->
            </ul>
          </div>
          <!-- end: Footer PAGES -->
        </div>

        <div class="col-xl-2 col-lg-2 col-md-3">
          <!-- Footer widget area 3 -->
          <div class="widget">
            <h4>REGIONS</h4>
            <ul class="list">
              <?php
              foreach ($this->session->static_pages['featured-regions']['sub-menu'] as $key => $page) {
                echo "<li><a href='$page[loc]'>$page[display]";
                if (isset($page['badge'])) {
                  echo " <span class='badge badge-danger'>$page[badge]</span>";
                }
                echo "</a></li>";
              }
              ?>
              <li><a href='<?= $this->session->static_pages['featured-regions']['loc']; ?>'>
                  <?= $this->session->static_pages['featured-regions']['display']; ?> <span class='badge badge-danger'>VIEW</span>
                </a></li>
              <li><a href='<?= base_url('region/switch'); ?>'>Switch Regions</a></li>
            </ul>
          </div>
          <!-- end: Footer REGIONS -->
        </div>

        <div class="col-xl-2 col-lg-2 col-md-3">
          <!-- Footer widget area 2 -->
          <div class="widget">
            <h4>CALENDAR</h4>
            <ul class="list">
              <?php
              foreach ($this->session->calendar_date_list as $year => $month_list) {
                foreach ($month_list as $month_number => $month_name) {
                  echo "<li><a href='" . base_url() . "calendar/" . $year . "/" . $month_number . "'>" . $month_name . "</a></li>";
                }
              }
              ?>
            </ul>
          </div>
          <!-- end: Footer CALENDAR -->
        </div>

        <div class="col-xl-4 col-lg-4 col-md-12">
          <!-- Footer widget area 5 -->
          <div class="widget clearfix widget-newsletter">
            <h4 class="widget-title"><i class="fa fa-envelope"></i> Sign Up For a Newsletter</h4>
            <p>Monthly newsletter informing you of the latest loaded results as well as the upcoming races

            </p>
            <form class="widget-subscribe-form p-r-40" action="<?= base_url("contact/test"); ?>" role="form" method="post">

              <div class="input-group">
                <input aria-required="true" name="widget-subscribe-form-email" class="form-control required email" placeholder="Enter your Email" type="email">
                <!--<input name="widget-subscribe-form-email">-->
                <span class="input-group-btn">
                  <button type="submit" id="widget-subscribe-submit-button" class="btn"><i class="fa fa-paper-plane"></i></button>
                </span>
              </div>
            </form>
          </div>
          <!-- end: Footer widget area 5 -->

          <?php
          if ($url_bits[0] != "event") {
          ?>
            <p><span class='badge badge-info' style='font-size: 1.2em;'>SnapScan</span></p>
            <div class="m-b-10 m-r-20" style="float: left;">
              <a href="https://pos.snapscan.io/qr/LAzMFdGZ">
                <img style='width: 100px;' src='<?= base_url("assets/img/SnapCode_LAzMFdGZ_100.webp"); ?>' loading="lazy" />
              </a>
            </div>
            <p>Consider supporting the wesbite via SnapScan</p>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright-content">
    <div class="container">

      <div class="row">
        <div class="col-lg-3">
          <!-- Social icons -->
          <div class="social-icons social-icons">
            <ul>
              <li class="social-facebook"><a href="https://www.facebook.com/roadrunningcoza"><i class="fab fa-facebook-f"></i></a></li>
              <li class="social-twitter"><a href="https://twitter.com/roadrunningcoza"><i class="fab fa-twitter"></i></a></li>
            </ul>
          </div>
          <!-- end: Social icons -->
        </div>

        <div class="col-lg-9 text-right">
          <div class="copyright-text">&copy; <?= date("Y"); ?> RoadRunning.co.za. All Rights Reserved.
            <?php
            $white_list = ["terms", "disclaimer", "sitemap"];
            foreach ($this->session->static_pages as $key => $page) {
              if (!in_array($key, $white_list)) {
                continue;
              }
              echo " | <a href='$page[loc]'>$page[display]</a> ";
            }
            // KILL SESSION only in dev
            if ($this->ini_array['enviroment']['server'] != "production") {
              echo "| <a href='" . base_url("login/destroy") . "'>Kill Session</a> ";
            }
            // IF LOGGED IN USER IS ADMIN
            if ($logged_in_user) {
              if (in_array(1, $logged_in_user['role_list'])) {
                echo "| <a href='" . base_url("mailer") . "'>Mailer</a> ";
                echo "| <a href='" . base_url("admin") . "'>Admin Dashboard</a> ";
              }
            }
            //                        wts($logged_in_user);
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

</div>

<!-- logout confirmation modal -->
<div class="modal fade" id="confirm-logout" tabindex="-1" role="dialog" aria-labelledby="confirm-logout-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="text-uppercase">Confirmation required</h4>
      </div>
      <div class="modal-body">
        Please confirm that you did not hit the "<b>logout</b>" button by mistake.<br> Don't feel bad, it happens.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light btn-md" data-dismiss="modal">My Bad</button>
        <a class="btn btn-success btn-ok btn-md" href="<?= base_url("logout"); ?>">Logout</a>
      </div>
    </div>
  </div>
</div>
<!-- logout confirmation modal -->

<a id="scrollTop"><i class="icon-chevron-up1"></i><i class="icon-chevron-up1"></i></a>

<script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
<script src="<?= base_url('assets/js/plugins.min.js'); ?>" defer></script>
<script src="<?= base_url('assets/js/functions.min.js'); ?>" defer></script>
<script src="<?= base_url('assets/js/fa.js'); ?>" crossorigin="anonymous" defer></script>

<?php
if (isset($scripts_to_load)) :
  foreach ($scripts_to_load as $row) :
    if (substr($row, 0, 4) == "http") {
      $js_link = $row;
    } else {
      $js_link = base_url($row);
    }
    echo "<script src='$js_link'></script>";
  endforeach;
endif;
?>



<script>
  <?php
  foreach ($this->session->most_searched as $search_id => $search) {
  ?>
    $('#search_<?= $search_id; ?>').click(function() {
      $('#main_search').val('<?= $search['search_term']; ?>');
      $("#main_search_form").submit();
    });
  <?php
  }
  ?>
</script>

<!-- START: AD BLOCK CHECK -->
<div class="modal-strip cookie-notify background-dark fadeInUp" data-wow-delay="1s" id="ad-block-notification">
  <div class="container">
    <div class="row">
      <div class="col-md-10">
        <b>We get it!</b> Ads are annoying. But they help keep this website up and running. <br>
        Please help support this site by <u>disabling your ad block software</u>.
      </div>
      <div class="col-md-2 text-right">
        <button type="button" class="btn btn-rounded btn-light modal-close">OK!</button>
      </div>
    </div>
  </div>
</div>
<script src="/ads.js" type="text/javascript"></script>
<script type="text/javascript">
  //
  if (!document.getElementById('advertensieblok')) {
    document.getElementById('ad-block-notification').style.display = 'block';
  }
  //
</script>
<!-- END: AD BLOCK CHECK -->

<!-- lazy laoding -->
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
    var lazyloadImages;

    if ("IntersectionObserver" in window) {
      lazyloadImages = document.querySelectorAll(".lazy");
      var imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            var image = entry.target;
            image.classList.remove("lazy");
            imageObserver.unobserve(image);
          }
        });
      });

      lazyloadImages.forEach(function(image) {
        imageObserver.observe(image);
      });
    } else {
      var lazyloadThrottleTimeout;
      lazyloadImages = document.querySelectorAll(".lazy");

      function lazyload() {
        if (lazyloadThrottleTimeout) {
          clearTimeout(lazyloadThrottleTimeout);
        }

        lazyloadThrottleTimeout = setTimeout(function() {
          var scrollTop = window.pageYOffset;
          lazyloadImages.forEach(function(img) {
            if (img.offsetTop < (window.innerHeight + scrollTop)) {
              img.src = img.dataset.src;
              img.classList.remove('lazy');
            }
          });
          if (lazyloadImages.length == 0) {
            document.removeEventListener("scroll", lazyload);
            window.removeEventListener("resize", lazyload);
            window.removeEventListener("orientationChange", lazyload);
          }
        }, 20);
      }

      document.addEventListener("scroll", lazyload);
      window.addEventListener("resize", lazyload);
      window.addEventListener("orientationChange", lazyload);
    }
  })
</script>

<?php
if ($this->ini_array['enviroment']['server'] != "production") {
  //if (($logged_in_user) && (in_array(1, $logged_in_user['role_list'])) && (isset($_GET['debug']))) {
  //    wts($this->session->most_viewed_pages);
?>
  <div class="my-stuff">
    <h4 class="text-uppercase">Environment info</h4>
    <p>
      <?php
      echo "Enviroment: " . $this->ini_array['enviroment']['server'] . "<br>";
      echo "Controller: " . $this->router->fetch_class() . "<br>";
      echo "Method: " . $this->router->fetch_method();
      ?>
    </p>
    <h4 class="text-uppercase">New site</h4>
    <?php wts($new_page_count); ?>

    <h4 class="text-uppercase">User info</h4>
    <?php //wts($logged_in_user); 
    ?>

    <h4 class="text-uppercase">SESSION</h4>
    <?php //wts($_SESSION); 
    ?>

    <h4 class="text-uppercase">COOKIE</h4>
    <?php
    wts($_COOKIE);
    wts($rr_cookie);
    ?>
  </div>
<?php
}
?>
</body>

</html>