<div class="c-content-box c-size-sm <?= $box_color; ?>">
    <div class="container">
        <div class="c-content-bar-2 c-opt-1">
            <div class="row" data-auto-height="true">
                <div class="col-md-12">
                    <?php
                    // Edition BUTTONS
                    $this->load->view("/event/buttons_edition", $event_detail);
                    // Race BUTTONS
                    $this->load->view("/event/buttons_race", $event_detail);
                    ?>
                    <div class="btn-group">
                        <a class="btn c-theme-btn c-btn-uppercase btn-md c-btn-bold c-margin-t-20" href="<?= base_url("event");?>">
                            <i class="icon-arrow-left"></i> Back to Events Calendar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//wts($event_detail['date_list']);
//wts($event_detail);
?>

</div>
<!-- END: PAGE CONTAINER -->
