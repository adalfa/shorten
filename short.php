<?php
include_once 'lib/RedisServer.php';
include_once 'lib/shorten.php';
include_once 'lib/googlesafe.php';
include_once 'lib/wipmania.php';
require_once 'lib/Twig/Autoloader.php';
require 'Benchmark/Timer.php';
function isValidURL($url,&$status)
{
$status="valid";
if  (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) 

{
$status="malformed";
return false ;
}
else
{
$arrurl=parse_url($url);
if (!$arrurl) return false;
$red= new RedisServer();
$test=$red->sIsMember("blacklist",$arrurl["host"]);

if (isset($test)&&$test) 

{
$status="blacklist:".$arrurl["host"];
return false;
}
}
return true;
}


Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('template');
$twig = new Twig_Environment($loader);
$errtemplate = $twig->loadTemplate('error.html');
$data["name"]='adalfa';


if (isset($_GET["url"]))
{
	$timer = new Benchmark_Timer(true);
	$timer->start();
	$red= new RedisServer();
	$hash=$_GET["url"];
	$url=$red->Get("urls:".$hash);
	$timer->setMarker("retrieve");
	if (isset($url))
	{
		
		$date=explode("-", date("Y-n-j-G"));
		$timer->setMarker("date");
		$red->hIncrBy("stat:".$hash,"count",1);
		$red->hIncrBy("stat:".$hash,$date[0],1);
		$red->hIncrBy("stat:".$hash,$date[0].$date[1],1);
		$red->hIncrBy("stat:".$hash,$date[0].$date[1].$date[2],1);
		$red->hIncrBy("stat:".$hash,$date[0].$date[1].$date[2].$date[3],1);
		$timer->setMarker("counters");
		//registro la nazione di richiesta (sse non è un ip privato)
		$ip=$_SERVER['REMOTE_ADDR'];
		//echo isIPIn($ip,'10.0.0.0','255.0.0.0');
		//echo isIPIn($ip,'172.16.0.0','255.240.0.0');
		//echo isIPIn($ip,'192.168.0.0','255.255.0.0');	
		if (isset($ip) && !(isIPIn($ip,'10.0.0.0/8')==true ||isIPIn($ip,'172.16.0.0/12')==true || isIPIn($ip,'192.168.0.0/16')==true || isIPIn($ip,'127.0.0.1/32')))
		
		$red->hIncrBy("stat:".$hash,getCountry($ip),1);
		
		$timer->setMarker("country");
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$url);
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
		
		$timer->stop();
		//$timer->display();
		return;
	}
	else

{

$data["msg"]="url ".$url." non presente";
$errtemplate->display($data);
}
}




elseif (isset($_POST["url"]))
{

$url=$_POST["url"];
$safe=false;
if ($valid=isValidURL($url,$urlvalid))
	$safe=isSafeUrl($url,$safestatus);

if ($valid && $safe)
{
$red= new RedisServer();
$c=$red->Incr("counter:id");
$hash=baseCAPencode($c);
$red->SetNX("urls:".$hash,$url);
$red->bgsave();

echo "short url:".$hash;
}
else 
{

if (!$valid)
{
$data["msg"]="url: ".$url." non valida:".$urlvalid;
$errtemplate->display($data);
return;
}
if (!$safe)
{
$data["msg"]=$url;
$data["status"]=$safestatus;
$errtemplate = $twig->loadTemplate('google.html');
$errtemplate->display($data);
return;
}




}
}
return;