<?php header('Content-type: text/xml'); ?>
<?= '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    <?php foreach($static_pages as $data) { ?>
    <url>
        <loc><?= $data['loc'] ?></loc>
        <lastmod><?= $data['lastmod'] ?></lastmod>
        <priority><?=$data['priority'];?></priority>
        <changefreq><?=$data['changefreq'];?></changefreq>
    </url>
    <?php } ?>
    
    <?php foreach($edition_list_xml as $data) { ?>
    <url>
        <loc><?= $data['loc'] ?></loc>
        <lastmod><?= $data['lastmod'] ?></lastmod>
        <priority><?=$data['priority'];?></priority>
        <changefreq><?=$data['changefreq'];?></changefreq>
    </url>
    <?php } ?>

</urlset>