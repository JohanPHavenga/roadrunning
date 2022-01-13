<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase"> <?= $title; ?></span>
                </div>
            </div>
            <div class="portlet-body">

                <ul>
                    <li><a class='' href='<?=base_url('admin/edition/view/');?>'>ALL</a></li><br>
                    <?php
                    foreach ($asa_list as $asa_member) {
                        echo "<li><a class='' href='".base_url('admin/edition/view/'.$asa_member['asa_member_abbr'])."/1year'>".$asa_member['asa_member_abbr']."</a></li>";
                    }
                    ?>
                </ul>
                <?php
                // wts($asa_list);
                ?>

            </div>
        </div>
    </div>
</div>