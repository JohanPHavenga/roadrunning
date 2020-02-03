</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2016-<?= date("Y"); ?> &copy; Project Codename
        <a target="_blank" href="http://www.johanhavenga.co.za" style="color: #999;">Coyote</a> | 
        Codeigniter # <?= CI_VERSION; ?>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>

    <!--[if lt IE 9]>
    <script src="<?= base_url('assets/admin/plugins/respond.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/plugins/excanvas.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/plugins/ie8.fix.min.js'); ?>"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?= base_url('assets/admin/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/admin/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/admin/plugins/js.cookie.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/admin/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/admin/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>

    <script src="<?= base_url('assets/js/fa.js'); ?>" crossorigin="anonymous"></script>
    <!-- END CORE PLUGINS -->
    <?php
    // load extra JS files from controller
    if (isset($js_to_load)) :
        foreach ($js_to_load as $row):
            $js_link = base_url($row);
            echo "<script src='$js_link' type='text/javascript'></script> ";
        endforeach;
    endif;
    ?>
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?= base_url('assets/admin/scripts/app.min.js'); ?>" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->

    <?php
    // load script files from controller
    if (isset($scripts_to_load)) :
        foreach ($scripts_to_load as $row):
            $js_link = base_url($row);
            echo "<script src='$js_link'  ></script> ";
        endforeach;
    endif;
    ?>
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?= base_url('assets/admin/scripts/layout.min.js'); ?>" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->


    <script type="text/javascript">
        if (window.location.hash) {
            var hash = window.location.hash;

            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 1500, 'swing');
        }
    </script>
</body>

</html>
