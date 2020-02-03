<div class="row">
    <div class="col-md-3">
        <div class="portlet light sidebar-portlet">
            <div class="sidemenu">
                <ul class="nav">
                    <?php
                    foreach ($side_menu_arr as $menu_item) {
                        echo "<li class='$menu_item[class]'>";
                        echo "<a href='/admin/user/$menu_item[url]'><i class='icon-$menu_item[icon]'></i> $menu_item[text] </a>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
            <!-- END MENU -->
        </div>
    </div>
    <div class="col-md-9">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import Data</span>
                </div>
            </div>

            <p>Upload complete.</p>

            <div class='btn-group'>
                <?= fbuttonLink('./import','Upload Another File'); ?>
            </div>
        </div>
    </div>
</div>
