<?php header('Content-type: text/xml'); ?>
<?= '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    <?php foreach($pages as $data) { ?>
    <url>
        <loc><?= base_url().$data['url'] ?></loc>
        <priority><?=$data['priority'];?></priority>
        <changefreq><?=$data['change_freq'];?></changefreq>
    </url>
    <?php } ?>

</urlset>