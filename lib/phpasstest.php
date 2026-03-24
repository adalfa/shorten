<?php
include_once 'RedisServer.php';

//$hasher = new PasswordHash(11, FALSE);
//$hash = $hasher->HashPassword("ciao");

//echo $hash;

$arrurl=parse_url("http://bit.ly/1");
if (!$arrurl) echo "nourl";
$red= new RedisServer();
$test=$red->Get("blacklist:".$arrurl["host"]);
if (isset($test)) echo "in blacklist";

return "ok";
?>