<?php
$_token = trim(file_get_contents(__DIR__ . '/config/admin.token'));
if (!isset($_GET['token']) || !hash_equals($_token, $_GET['token'])) {
    http_response_code(403);
    exit('Forbidden');
}
include_once 'lib/RedisServer.php';
include_once 'lib/shorten.php';
require 'Benchmark/Timer.php';

$timer = new Benchmark_Timer(true);
$timer->start();
$red= new RedisServer();
$timer->setMarker("Init redis");
//Twig_Autoloader::register();
//$loader = new Twig_Loader_Filesystem('template');
//$twig = new Twig_Environment($loader);
//$listtemplate = $twig->loadTemplate('list.html');
//$data["name"]="adalfa";
//$timer->setMarker("Init twig");
$urls=array();
$timer->setMarker("Init");
$start=intval($_GET['iDisplayStart']);
$start=($start==0)?1:$start;
$end=$start+intval($_GET['iDisplayLength']);
$tot=(int)$red->Get("counter:id");
$end=($tot>$end)?$end:$tot;
for($i=$start;$i<$end;$i++)
{
 $hash=baseCAPencode($i);
 
$url=$red->Get("urls:".$hash);
 $row=array();
 $row[0]=$hash;
 $row[1]=$url;
 $urls[]=$row;	


}	
//$data["urls"]=$urls;
//$timer->setMarker("getall");
//$timer->stop();
//$data["perf"]=str_replace("-",".",$timer->getOutput(true,'plain'));

//$listtemplate->display($data);
//$timer->setMarker("display");
$data = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => intval($tot),
		"iTotalDisplayRecords" =>intval($tot),
		"aaData" => array()
		);
$data["aaData"]=$urls;
echo json_encode($data);


?>