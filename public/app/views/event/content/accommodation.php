<div class="row m-b-40">
    <div class="col-lg-12">
        <?php
        if ($in_past) {
        ?>
            <p>This race has already taken place. No accommodation options available in the past.</p>
        <?php
        } else {
        ?>
            <p>Use the interactive map below to find accommodation close to the race.</p>
            <iframe src="https://www.stay22.com/embed/gm?<?=$map_param_str;?>" id="stay22-widget" width="100%" height="560" frameborder="0"></iframe>
        <?php
        }
        ?>
    </div>
</div>