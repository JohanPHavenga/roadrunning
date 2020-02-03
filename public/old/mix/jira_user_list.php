<?php


$username = 'johanh';
$password = 'Xcecs8gmKKc8ZsZlxyOeKkRA';

$url = 'https://jira.mixtelematics.com/rest/api/latest/group/member?startAt=0&maxResults=56&groupname=jira-users';
$url = 'https://jira.mixtelematics.com/rest/api/latest/user?username=johanh';

$curl = curl_init();
curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

$issue_list = (curl_exec($curl));
//echo $issue_list;


//$arrContextOptions = array(
//    "ssl" => array(
//        "verify_peer" => false,
//        "verify_peer_name" => false,
//    ),
//);
//
//$response = file_get_contents("https://jira.mixtelematics.com/rest/api/latest/group/member?groupname=jira-users&maxResults=1000", false, stream_context_create($arrContextOptions));
$response_array = json_decode($issue_list);

echo "<p>There are <b>".count($response_array->values)."</b> users.</p>";
foreach ($response_array->values as $entry) {
    $name_array[] = $entry->displayName;
    echo $entry->displayName . "<br>";
}


echo "<pre>";
print_r($response_array->values);
echo "</pre>";

die;

?>