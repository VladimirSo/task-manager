<?php
namespace getPagePath;

function getPagePath() 
{
$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);

return $path;	
}
