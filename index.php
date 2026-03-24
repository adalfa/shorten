<?php


require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('template');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('index.html');
$data["name"]='adalfa';
$template->display($data);


?>