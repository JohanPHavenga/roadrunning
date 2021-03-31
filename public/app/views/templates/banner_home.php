<div id="slider" class="inspiro-slider arrows-large arrows-creative dots-creative" data-height-xs="360" data-autoplay-timeout="2600" data-animate-in="fadeIn" data-animate-out="fadeOut" data-items="3" data-loop="true" data-autoplay="true">
    <?php
    foreach ($quote_arr as $quote_id => $quote) {
        ?>
        <div class="lazy slide background-overlay-<?= $quote_id; ?>" style="background-image:url('<?= $quote['img_url']; ?>')">
            <div class="container">
                <div class="slide-captions d-none d-md-block">
                    <h2 class="text-sm no-margin"><?= $quote['quote']; ?></h2>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
