<div id="slider" class="inspiro-slider arrows-large arrows-creative dots-creative" data-height-xs="360" data-autoplay-timeout="2600" data-animate-in="fadeIn" data-animate-out="fadeOut" data-items="3" data-loop="true" data-autoplay="true">

    <?php
        foreach ($quote_arr as $quote_id=>$quote) {
            ?>
            <div class="slide background-overlay-<?=$quote_id;?>" style="background-image:url('<?= $quote['img_url']; ?>')">
                <div class="container">
                    <div class="slide-captions d-none d-md-block">
                        <h2 class="text-sm no-margin"><?=$quote['quote'];?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
    
<!--    <div class="slide background-overlay-one" style="background-image:url('<?= base_url('assets/img/slider/run_02.webp'); ?>')">
        <div class="container">
            <div class="slide-captions d-none d-md-block">
                <h2 class="text-sm no-margin">Any idiot can run</h2>
                <h2 class="text-medium no-margin">but it takes a special kind of idiot to run a marathon</h2>
            </div>
        </div>
    </div>

    <div class="slide background-overlay-two" style="background-image:url('<?= base_url('assets/img/slider/run_03.webp'); ?>')">
        <div class="container">
            <div class="slide-captions">
                <h2 class="text-sm no-margin text-colored">If a hill has a name</h2>
                <h2 class="text-medium no-margin">It's probably a pretty tough hill</h2>
            </div>
        </div>
    </div>-->

</div>
