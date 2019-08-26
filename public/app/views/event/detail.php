<h2><?=$edition_data['edition_name'];?></h2>
<?php
if (isset($file_list[1])) {
    echo "<img src='".base_url("file/edition/".$edition_data['edition_slug'])."/logo/".$file_list[1][0]['file_name']."' />";
}
wts($edition_data);
wts($race_list);
wts($file_list);
wts($url_list);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

