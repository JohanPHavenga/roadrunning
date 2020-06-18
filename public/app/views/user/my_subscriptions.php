<?php
if (!empty($newsletter_subs)) {
    ?>
    <!-- modals for confirmation -->
    <div class="modal fade" id="confirm-newsletter" tabindex="-1" role="dialog" aria-labelledby="confirm-newsletter-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">            
                <div class="modal-body">
                    <p>You are about to unsubscribe from the Monthly Newsletter?.</p>

                    <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok btn-xs" href="<?= $newsletter_subs[0]['unsubscribe_url']; ?>">Unsubscribe</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
if (!empty($edition_subs)) {
    foreach ($edition_subs as $key => $sub) {
        ?>
        <!-- modals for confirmation -->
        <div class="modal fade" id="confirm-edition-<?= $key; ?>" tabindex="-1" role="dialog" aria-labelledby="confirm-newsletter-label-<?= $key; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">            
                    <div class="modal-body">
                        <p>You are about to unsubscribe from the <b><?= $sub['edition_name']; ?></b> race notifications?.</p>

                        <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-danger btn-ok btn-xs" href="<?= $sub['unsubscribe_url']; ?>">Unsubscribe</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <?php
                // CRUMBS WIDGET
                $this->load->view('widgets/crumbs');
                ?>
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <?php
                if (!empty($newsletter_subs)) {
                    ?>
                    <h4 class="text-uppercase">Newsletter Subscriptions</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Monthly Newsletter
                                </td>
                                <td>
                                    <button class="btn btn-light btn-xs" data-href="" data-toggle="modal" data-target="#confirm-newsletter">
                                        Unsubscribe
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                } else {
                    ?>
                    <div class="m-b-40">
                        <?php
                        $subscribe_url = base_url("user/subscribe/newsletter");
                        $attributes = array('class' => 'form-inline', 'role' => 'form');
                        echo form_open($subscribe_url, $attributes);
                        ?>
                        <div class="mx-lg-3 m-t-5">
                            <h5>Subscribe to my monthly newsletter:</h5>
                        </div>
                        <div class="form-group mx-sm-3 m-t-5">
                            <label for="email_sub" class="sr-only">Email</label>
                            <input class="form-control" id="email_sub" name="user_email" placeholder="info@example.com" type="email" required="" value="<?= $logged_in_user['user_email']; ?>">
                        </div>
                        <?php
                        $data = array(
                            'type' => 'submit',
                            'content' => 'Subscribe',
                            'class' => 'btn m-t-5',
                        );
                        echo form_button($data);
                        echo form_close();
                        ?>
                    </div>
                    <?php
                }

                if (!empty($edition_subs)) {
                    ?>
                    <h4 class="text-uppercase">Race Subscriptions</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($edition_subs as $key => $sub) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $sub['edition_name']; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-light btn-xs" data-href="" data-toggle="modal" data-target="#confirm-edition-<?= $key ?>">
                                            Unsubscribe
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }

//                wts($newsletter_subs);
//                wts($edition_subs);
//                wts($logged_in_user);
                ?>
            </div>
            <!-- end: Content-->
            
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // PROFILE WIDGET
                $this->load->view('widgets/profile');
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->

        </div>
    </div>
</section>
<!-- end: PROFILE -->





