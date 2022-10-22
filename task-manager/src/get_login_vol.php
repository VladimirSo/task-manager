<?php
function getLoginVol(): string
{
  $login = '';

  if (isset($_POST['login'])) {
    $login = $_POST['login'];
  } elseif (isset($_COOKIE['task_manager_l'])) {
    $login = $_COOKIE['task_manager_l'];
  } 

  return $login;
} 
