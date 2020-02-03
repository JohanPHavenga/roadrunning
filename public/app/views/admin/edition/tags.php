
<div class="portlet light" id="dates_reg_flat">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">TAGS</span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="tags">
        <?php
        foreach ($tag_list as $tag_id=>$tag) {
            ?>
            <a class="badge badge-light"> <?=$tag['tag_name'];?> </a>
            <?php
        }
        ?>
        </div>
    </div>
</div>

