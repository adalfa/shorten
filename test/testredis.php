<?php
include_once '../lib/RedisServer.php';
include_once '../lib/shorten.php';
$red= new RedisServer();



for ($i=0; $i <1000000; $i++)
{
 $c=$red->Incr("counter:id");
$hash=baseCAPencode($c);
$red->SetNX("urls:".$hash,"http://maps.google.it/maps/place?q=Genova+lungobisagno+dalmazia+71&hl=it&cid=25765999211048494".$c);
echo "\nc:".$c." hash:".$hash;


 //insert_url($conn,$hash,"http://maps.google.it/maps/place?q=Genova+lungobisagno+dalmazia+71&hl=it&cid=257659992110484964".$i)
	

}


$key="2jy";
echo baseCAPdecode($key)."\n";
$url= $red->Get("urls:abbbbbbbbbbb");
if (!isset($url)) echo "nullo";

?>
