<div class="widget clearfix widget-tags">
    <h4 class="widget-title">Tags</h4>
    <div class="tags">
        <?php
            foreach ($tag_list as $tag) {
                ?>
                <a href="<?=base_url("search/tag/".$tag['tag_name']);?>"><?=$tag['tag_name'];?></a>
                <?php
            }
        ?>
    </div>
    <?php
//        wts($tag_list);
    ?>
</div>

