<?php
$_token = trim(file_get_contents(__DIR__ . '/../config/admin.token'));
if (!isset($_GET['token']) || !hash_equals($_token, $_GET['token'])) {
    http_response_code(403);
    exit('Forbidden');
}
include_once 'stats.php';
require 'Benchmark/Timer.php';

function parse_tqx($tqx)
{

$tqxval=explode(";",$tqx);
$ltqxval=count($tqxval);

for($i=0;$i<$ltqxval;$i++)
{


 $val=explode(":",$tqxval[$i]);

$arr[$val[0]]="'".$val[1]."'";
}

return $arr;
}

$timer = new Benchmark_Timer(true);
$timer->start();
$id="0";
if (isset($_GET["id"]))
	$id=$_GET["id"];

if (isset($_GET["data"]))
	$data=$_GET["data"];
else $data=date("d-m-Y");
if (isset($_GET["type"]))
{
$type=$_GET["type"];
{
	//parse tqx
	if (isset($_GET["tqx"]))
		$tqx=parse_tqx($_GET["tqx"]);
		
$timer->setMarker("tqx parse");

$response="google.visualization.Query.setResponse({version:'0.6',status:'ok',";
if (isset($tqx["reqId"]))
	$response.="reqId:".$tqx["reqId"].",";
$timer->setMarker("reqid");
$arrdata=explode("-",$data);
switch($type)
{
	case "Daily":
	$arr_stats=getDailyDate($id,mktime(0,0,0,$arrdata[1],$arrdata[0],$arrdata[2]));
	$timer->setMarker("getstatsDaily");
	break;
	case "Monthly":
	$arr_stats=getMonthlyDate($id,mktime(0,0,0,$arrdata[1],1,$arrdata[2]));
	$timer->setMarker("getstatsMonthly");
	break;
	case "Yearly":
	$arr_stats=getYearlyDate($id,mktime(0,0,0,1,1,$arrdata[2]));
	$timer->setMarker("getstatsYearly");
	break;
	case "Country":
	$arr_stats=getCountries($id);
	$timer->setMarker("getstatsCountries");
	break;
}
$timer->setMarker("getstats");
$response.="table:{cols:[{label:'ora',type:'string'},{label:'accessi',type:'number'}],rows:["; 

$len=count($arr_stats);
if ($type<>"Country")
{
$start=1;
$end=$len+1;
if ($type=="Daily")
	{
	 $start=0;
	 $end=$len;
	}
for($c=$start;$c<$end;$c++)
{

$response=$response."{c:[{v:".$c.", f:'".$c."'},{v:".$arr_stats[$c].",f:'".$arr_stats[$c]."'}]}";
if ($c<$end-1) $response=$response.",";

}
$response.="]}});";
}
else
{

$keys=array_keys($arr_stats);
for($c=0;$c<$len;$c++)
{
$response=$response."{c:[{v:'".$keys[$c]."'},{v:".$arr_stats[$keys[$c]]."}]}";
if ($c<$len-1) $response=$response.",";

}
$response.="]}});";
}
$timer->setMarker("build response");
echo $response;
$timer->stop();
//$timer->display();
}



}
?>
