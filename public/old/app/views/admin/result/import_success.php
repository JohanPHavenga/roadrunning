<div class="row">
    <div class="col-md-12">

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import Result Set</span>
                </div>
            </div>
            <div class="portlet-body">   
                <div class="row">
                    <div class='col-md-12 col-sm-12'>
                        <?php
                        if ($this->session->flashdata('import_counter')) {
                            ?>
                            <p>A total of <b><?= $this->session->flashdata('import_counter'); ?></b> results has been imported for <b><?= $this->session->flashdata('race_name'); ?></b>.</p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-12 col-sm-12'>
                        <div class='btn-group'>
                            <a href="<?=$return_url;?>" class="btn btn-default" role="button">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
