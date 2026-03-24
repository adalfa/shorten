<?php
include_once 'RedisServer.php';


$iso3166 = array('AX','AF','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BQ','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CG','CD','CK','CR','CI','HR','CU','CW','CY','CZ','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR','GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK','HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT','JM','JP','JE','JO','KZ','KE','KI','KP','KR','KW','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO','RU','RW','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST','SA','SN','RS','SC','SL','SG','SX','SK','SI','SB','SO','ZA','GS','SS','ES','LK','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG','UA','AE','GB','US','UM','UY','UZ','VU','VE','VN','VG','VI','WF','EH','YE','ZM','ZW','XX');

function getDaily($hash)
{
$date=date("Ynj");
$red= new RedisServer();
$arrret;
for($i=0;$i<24;$i++)
{
 	$value=$red->hget("stat:".$hash,$date.$i);
	$arrret[$i]=(isset($value))?$value:0;	
}
return $arrret;
}


function getDailyDate($hash,$date)
{
$date=date("Ynj",$date);
$red= new RedisServer();
$arrret;
for($i=0;$i<24;$i++)
{
 	$value=$red->hget("stat:".$hash,$date.$i);
	$arrret[$i]=(isset($value))?$value:0;	
}
return $arrret;
}

function getMonthly($hash)
{
$date=date("Yn");
$red= new RedisServer();
$arrret;
for($i=1;$i<32;$i++)
{
	$value=$red->hget("stat:".$hash,$date.$i); 
$arrret[$i]=(isset($value))?$value:0;	
}
return $arrret;
}


function getMonthlyDate($hash,$month)
{
$date=date("Yn",$month);
$red= new RedisServer();
$arrret;
for($i=1;$i<32;$i++)
{
	$value=$red->hget("stat:".$hash,$date.$i); 
$arrret[$i]=(isset($value))?$value:0;	
}
return $arrret;
}
function getYearly($hash)
{
$date=date("Y");
$red= new RedisServer();
$arrret;
for($i=1;$i<13;$i++)
{
$value=$red->hget("stat:".$hash,$date.$i); 
$arrret[$i]=(isset($value))?$value:0;	
}
return $arrret;
}

function getYearlyDate($hash,$year)
{
$date=date("Y",$year);
$red= new RedisServer();
$arrret;
for($i=1;$i<13;$i++)
{
$value=$red->hget("stat:".$hash,$date.$i); 
$arrret[$i]=(isset($value))?$value:0;	
}
return $arrret;
}

function getCountries($hash)
{
$red= new RedisServer();
$arrret;
global $iso3166;
$c=count($iso3166);
for($i=0;$i<$c;$i++)
{
$value=$red->hget("stat:".$hash,$iso3166[$i]); 
if (isset($value))
$arrret[$iso3166[$i]]=$value;	
}
return $arrret;
}

//print_r(getDailyDate("1",mktime(0,0,0,8,10,2011)));
//print_r(getMonthlyDate("1",mktime(0,0,0,8,1,2011)));
//print_r(getMonthly("1"));
//print_r(getYearly("1"));
//print_r(getYearlyDate("1",mktime(0,0,0,1,1,2011)));
//print_r(getYearly("1"));
//print_r(getCountries("1"));
?>
