<?php
if ($this->session->flashdata()) {
    if ($this->session->flashdata('icon') !== null) {
        $icon = $this->session->flashdata('icon');
    } else {
        $icon = "info-circle";
    }
    ?>
    <div class="alert alert-<?= $this->session->flashdata('status'); ?> alert-dismissible" role="alert">
        <div class="container">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>
            <strong><i class="fa fa-<?= $icon; ?>"></i> <?= ucfirst($this->session->flashdata('status')); ?>!</strong> <?= $this->session->flashdata('alert'); ?> 
        </div>
    </div>
    <?php
}