<?php
namespace showMenu;

function showMenu(&$arr, $direct = SORT_ASC, $style = '')
{
	$newArr = arraySort($arr, $direct);

	include $_SERVER['DOCUMENT_ROOT'] . '/templates/tpl_menu.php';
}
