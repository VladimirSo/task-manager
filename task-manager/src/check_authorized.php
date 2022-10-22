<?php
function checkAuth()
{
  session_start();

  if (! isset($_SESSION['is_authorized'])) {
    header('Location: /index.php');
  } else {
    $login = $_COOKIE['task_manager_l'];

    setcookie('task_manager_l', $login, time()+3600*24*30, '/');
  }
} 
