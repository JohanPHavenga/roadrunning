<?php
$page_title = $edition_data['event_name'];
$page_sub_title = fdateHumanFull($edition_data['edition_date']);
?>
<!-- Page title -->
<section id="page-title" class="text-light page-title-left <?= $page_title_small; ?>" style="background-image:linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.6)),url(<?= $edition_data['img_url']; ?>);">

    <div class="container">
        <?php
        // CRUMBS WIDGET
        $this->load->view('widgets/crumbs');
        ?>
        <div class="page-title">
            <h2><?= $page_title; ?></h2>
        </div>
        <?php
        if (isset($page_sub_title)) {
            echo "<div class='page-sub-title'><h4>" . $page_sub_title . "</h4></div>";
        }
        ?>
    </div>
</section>
<!-- end: Page title -->
