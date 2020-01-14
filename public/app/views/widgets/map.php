<!--widget map-->
<div class="widget clearfix widget-map" style="height: 350px;">
    <h4 class="widget-title">Map</h4>
    <iframe
        width="100%"
        height="250"
        frameborder="0" style="border:0; margin-bottom: 10px;"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBERO5xjCtTOmjQ_zSSUvlp5YN_l-4yKQw&q=<?= $address_nospaces; ?>" allowfullscreen>
    </iframe>

    <a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_address'] . "," . $edition_data['town_name']; ?>" class="btn btn-light">
        <i class="fa fa-map"></i> Get Directions</a>

    <?php
    // If not in the past
    if (!$in_past) {
        ?>
        <a href="<?= base_url("event/" . $slug . "/accommodation"); ?>" class="btn btn-light"><i class="fa fa-bed"></i> Find Accommodation</a>
        <?php
    }
    ?>
</div>