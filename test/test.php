<?php
include_once './shorten.php';
include_once './sqllite.php';


$conn=open_db();
//init_hash_db($conn);
$start=get_lastid($conn);
echo "Start:".$start."\n";
/*for ($i=$start+1; $i <$start+1000000; $i++)
{
 $hash=baseCAPencode($i);
 echo "Hash:".$hash."\n";
 insert_url($conn,$hash,"http://maps.google.it/maps/place?q=Genova+lungobisagno+dalmazia+71&hl=it&cid=257659992110484964".$i);	

}
*/
$url=get_url($conn,"3vAs");
echo "id:".baseCAPdecode("3vAs")."\nURL:".$url;
?>

