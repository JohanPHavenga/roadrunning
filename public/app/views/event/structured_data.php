<?php
$id=$edition_data['edition_id'];
$start_date=fdateShort($edition_data['edition_date']);
//$start_date=fdateShort($edition_data['date_list'][1][$id]['date_start']); // top one TBR
$end_date=fdateShort($date_list[1][0]['date_end']);
$today_date=date("Y-m-d").'T'."00:00:00+02:00";

// Online entries
if (isset($edition_data['date_list'][3])) {
    $valid_from_date= fdateStructured($date_list[3][0]['date_start']); 
    $valid_to_date= fdateStructured($date_list[3][0]['date_end']); 
} else {
    $valid_from_date=fdateStructured(date("Y-m-d")); 
    $valid_to_date=$end_date.'T'."08:00:00+02:00";
}

if ($edition_data['edition_intro_detail']) {
    $description=strip_tags($edition_data['edition_intro_detail']);
} else {
    $description="Annual ".$edition_data['event_name']." road running event in ".$edition_data['town_name'].", ".$edition_data['province_name'].".";
}
if (isset($file_list[1])) { 
    $img_url=base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'];
} else {
    $img_url='';
}
?>
        
<script type="application/ld+json">
{        
    "@context": "http://schema.org",
    "@type": "SportsEvent",
    "name": "<?=$edition_data['edition_name'];?>",
    "startDate": "<?=$start_date;?>",
    "endDate": "<?=$end_date;?>",
    "location": { 
        "@type": "Place",
        "name": "<?=$edition_data['edition_address_end'];?>",
        "address": { 
            "@type": "PostalAddress",
            "streetAddress": "<?=$edition_data['edition_address_end'];?>",
            "addressLocality": "<?=$edition_data['town_name'];?>",
            "addressRegion": "WC",
            "addressCountry": "ZA"
        }
    },
    <?php if ($edition_data['club_id']!=8) { ?>
    "performer": { 
        "@type" : "Organization",
        "name" : "<?=$edition_data['club_name'];?>"
        <?php if (isset($edition_data['club_url_list'][0])) { ?>
            ,"url" : "<?=$edition_data['club_url_list'][0]['url_name'];?>"
        <?php } ?>
    },
    <?php } ?>
    "description": "<?= html_escape($description);?>",    
    "image": "<?=$img_url;?>",     
    "offers": [
            
    <?php
    foreach ($race_list as $race) {
        // race name
        if (!empty($race['race_name'])) { $rn=$race['race_name']; } else { $rn=fraceDistance($race['race_distance'])." ".$race['racetype_name']; }
        // race fee
        if ($race['race_fee_flat']>0) {
            $price=$race['race_fee_flat'];
        } elseif ($race['race_fee_senior_licenced']>0) {
            $price=$race['race_fee_senior_licenced'];
        } else {
            $price=0;
        }
        // get date
        if ($race['race_date']>0) {  $race_start_date=date("Y-m-d", strtotime($race['race_date'])); } else { $race_start_date=$start_date; }
    ?>
        { 
        "@type": "Offer",
        "description": "<?=$rn;?>"
        <?php
        if ($price>0) { ?>
            ,"price": "<?=$price;?>",
            "priceCurrency": "ZAR",
            <?php
            if (isset($url_list[5])) {
                $url=$url_list[5][0]['url_name']; ?>
                "url": "<?=$url;?>",
                "validFrom": "<?=$valid_from_date;?>",
                "validThrough": "<?=$valid_to_date;?>"
            <?php
            } else { ?>
                "availability": "http://schema.org/InStoreOnly"
                <?php
            }
        }
        if ($race === end($race_list)) {
           echo "}";
        } else {
           echo "},";
        }
    } // end foreach
        ?>
                        
    ]
    
}
</script>
