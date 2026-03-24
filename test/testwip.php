<?php
include_once '../lib/wipmania.php';

$ip='172.20.1.23';
echo $ip;
		if (isIPIn($ip,'172.16.0.0/12')) echo "OK";


?>
