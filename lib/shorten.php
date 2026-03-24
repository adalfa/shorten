<?php
$alphabet='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-._~';
$len=66;
function baseCAPencode($number)
{
if ($number==0)
	return 0;
if ($number==1)
	return '1';
$baseCAP='';
$alphabet='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-._~';
$len=66;
$hshalpha=str_split($alphabet);
while ($number > 0.999999)
{
$i=$number % $len;
$number=$number / $len;
$baseCAP=$hshalpha[$i].$baseCAP;
}
return $baseCAP;	
}
function baseCAPdecode($str)
{
$alphabet='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-._~';
$len=66;
$string=strrev($str);
$hsh=str_split($string);
$number=0;
for($i=0;$i<strlen($string);$i++)
{
 $number=$number+strpos($alphabet,$hsh[$i])*pow(66,$i);	

}
return $number;
}

?>
