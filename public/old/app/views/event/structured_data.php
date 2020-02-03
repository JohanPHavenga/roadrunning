<?php
$id=$event_detail['edition_id'];
$start_date=fdateShort($event_detail['edition_date']);
//$start_date=fdateShort($event_detail['date_list'][1][$id]['date_start']); // top one TBR
$end_date=fdateShort($event_detail['date_list'][1][0]['date_end']);
$today_date=date("Y-m-d").'T'."00:00:00+02:00";

// Online entries
if (isset($event_detail['date_list'][3])) {
    $valid_from_date= fdateStructured($event_detail['date_list'][3][0]['date_start']); 
    $valid_to_date= fdateStructured($event_detail['date_list'][3][0]['date_end']); 
} else {
    $valid_from_date=fdateStructured(date("Y-m-d")); 
    $valid_to_date=$end_date.'T'."08:00:00+02:00";
}
?>
        
<script type="application/ld+json">
{        
    "@context": "http://schema.org",
    "@type": "SportsEvent",
    "name": "<?=$event_detail['edition_name'];?>",
    "startDate": "<?=$start_date;?>",
    "endDate": "<?=$end_date;?>",
    "location": { 
        "@type": "Place",
        "name": "<?=$event_detail['edition_address_end'];?>",
        "address": { 
            "@type": "PostalAddress",
            "streetAddress": "<?=$event_detail['edition_address_end'];?>",
            "addressLocality": "<?=$event_detail['town_name'];?>",
            "addressRegion": "WC",
            "addressCountry": "ZA"
        }
    },
    <?php if ($event_detail['club_id']!=8) { ?>
    "performer": { 
        "@type" : "Organization",
        "name" : "<?=$event_detail['club_name'];?>"
        <?php if (isset($event_detail['club_url_list'][0])) { ?>
            ,"url" : "<?=$event_detail['club_url_list'][0]['url_name'];?>"
        <?php } ?>
    },
    <?php } ?>
    "description": "Join us for the annual <?=$event_detail['event_name'];?> road running race in <?=$event_detail['town_name'];?>.",
    <?php 
        if (isset($event_detail['file_list'][1])) { 
        $img_url = base_url("uploads/edition/" . $event_detail['edition_id'] . "/" . $event_detail['file_list'][1][0]['file_name']);
        } else {
            $img_url="";
        }
        ?>
    "image": "<?=$img_url;?>",     
    "offers": [
            
    <?php
    foreach ($event_detail['race_list'] as $race) {
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
            if (isset($event_detail['url_list'][5])) {
                $url=$event_detail['url_list'][5][0]['url_name']; ?>
                "url": "<?=$url;?>",
                "availability": "http://schema.org/InStock",
                "validFrom": "<?=$valid_from_date;?>",
                "validThrough": "<?=$valid_to_date;?>"            
            <?php
            } else { ?>
                "availability": "http://schema.org/InStoreOnly"
                <?php
            }
        }
            
        
        if ($race === end($event_detail['race_list'])) {
           echo "}";
        } else {
           echo "},";
        }
    } // end foreach
        ?>
                        
    ]
    
}
</script>
