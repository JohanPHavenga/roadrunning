<?php header('Content-type: text/xml'); ?>
<?= '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php
    // STATIC PAGES
    foreach ($this->session->static_pages as $page) {
        if (!empty($page['loc'])) {
            ?>
            <url>
                <loc><?= $page['loc'] ?></loc>
                <lastmod><?= $page['lastmod'] ?></lastmod>
                <priority><?= $page['priority']; ?></priority>
                <changefreq><?= $page['changefreq']; ?></changefreq>
            </url>
            <?php
        }
        if (isset($page['sub-menu'])) {
            foreach ($page['sub-menu'] as $sub_page) {
                if (!empty($sub_page['loc'])) {
                    ?>
                    <url>
                        <loc><?= $sub_page['loc'] ?></loc>
                        <lastmod><?= $sub_page['lastmod'] ?></lastmod>
                        <priority><?= $sub_page['priority']; ?></priority>
                        <changefreq><?= $sub_page['changefreq']; ?></changefreq>
                    </url>
                    <?php
                }
            }
        }
    }

    // PROVINCES
    foreach ($this->session->province_pages as $data) {
        ?>
        <url>
            <loc><?= $data['loc'] ?></loc>
            <lastmod><?= $data['lastmod'] ?></lastmod>
            <priority><?= $data['priority']; ?></priority>
            <changefreq><?= $data['changefreq']; ?></changefreq>
        </url>
        <?php
    }

    // REGIONS
    foreach ($this->session->region_pages as $data) {
        ?>
        <url>
            <loc><?= $data['loc'] ?></loc>
            <lastmod><?= $data['lastmod'] ?></lastmod>
            <priority><?= $data['priority']; ?></priority>
            <changefreq><?= $data['changefreq']; ?></changefreq>
        </url>
        <?php
    }
    
    // CALENDAR
    foreach ($calendar_xml as $data) {
        ?>
        <url>
            <loc><?= $data['loc'] ?></loc>
            <lastmod><?= $data['lastmod'] ?></lastmod>
            <priority><?= $data['priority']; ?></priority>
            <changefreq><?= $data['changefreq']; ?></changefreq>
        </url>
        <?php
    }

    // RACES
    foreach ($edition_list_xml as $data) {
        ?>
        <url>
            <loc><?= $data['loc'] ?></loc>
            <lastmod><?= $data['lastmod'] ?></lastmod>
            <priority><?= $data['priority']; ?></priority>
            <changefreq><?= $data['changefreq']; ?></changefreq>
        </url>
        <?php
    }
    ?>

</urlset>