<?php
namespace getPageTitle;

function getPageTitle($arr) 
{
$pageTitle = '';
$pagePath = \getPagePath\getPagePath();

foreach ($arr as $value) {
    if ($value['path'] == $pagePath) {
        $pageTitle = $value['title'];
    }
}

return $pageTitle;
}
