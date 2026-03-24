<?php

function getCountry($ip)
{
$baseurl="http://api.wipmania.com/";
$call=$baseurl.$ip;


$http = curl_init();
curl_setopt($http,CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($http,CURLOPT_URL,$call);

$result = curl_exec($http);

return $result;
}

function isIPIn($IP, $CIDR) {
     list ($net, $mask) = explode ("/", $CIDR);
   
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);

    $ip_ip = ip2long ($IP);

    $ip_ip_net = $ip_ip & $ip_mask;

    return ($ip_ip_net == $ip_net);
    
}


?>
