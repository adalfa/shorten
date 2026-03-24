<?php

function isSafeUrl($url,&$status)
{
$baseurl="https://sb-ssl.google.com/safebrowsing/api/lookup?";
$client="shortener";
$apikey=trim(file_get_contents(__DIR__.'/../config/safeapi.key'));
$appver="1.0";
$pver="3.0";
$encurl=urlencode($url);
$glsfapicall=$baseurl."client=".$client."&apikey=".$apikey."&appver=".$appver."&pver=".$pver."&url=".$encurl;


$http = curl_init();
curl_setopt($http,CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($http,CURLOPT_URL,$glsfapicall);

$result = curl_exec($http);

if (isset($result)) $status=$result;
$http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
curl_close($http);
//echo $http_status;
if ($http_status==204) return true;

return false; 

}


?>