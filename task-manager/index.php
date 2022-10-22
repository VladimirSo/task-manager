<?php
session_start();

$authNeed = (isset($_GET['login']) && $_GET['login'] == 'yes') ? true : false;
$logSuccess = false;
$passSuccess = false;

if (isset($_POST['login'])) {
  // require_once $_SERVER['DOCUMENT_ROOT'] . ('/data/users.php');
  // require_once $_SERVER['DOCUMENT_ROOT'] . ('/data/passwords.php');

  // foreach ($users as $key => $val) {
  //   if ($_POST['login'] == $val) {
  //     $logSuccess = true;

  //     if ($_POST['password'] == $passwords[$key]) {
  //       $passSuccess = true;

  //       setcookie('task_manager_l', $val, time()+3600*24*30, '/');
  //       $_SESSION['is_authorized'] = true;
  //     }
  //   } 
  // }

  require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/db_connect.php');
  $pdo = getDbConnect();

  $stmt = $pdo -> prepare("SELECT login, password, id FROM users WHERE login = :login");

  $stmt->execute(['login' => $_POST['login']]);
  $authData = $stmt->fetch(PDO::FETCH_LAZY);
  $pdo = null;

  if ( isset($authData['login']) ) {
    $logSuccess = true;
    $hash = $authData['password'];

    if (password_verify($_POST['password'], $hash)) {
      $passSuccess = true;

      setcookie('task_manager_l', $authData['login'], time()+3600*24*30, '/');
      $_SESSION['is_authorized'] = true;
    }
  }
}

// $loginInputVal = (isset($_POST['login'])) ? $_POST['login'] : '';
require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/get_login_vol.php');

$loginInputVal = getLoginVol(); 

$passInputVal = (isset($_POST['password'])) ? $_POST['password'] : '';

?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="styles.css" rel="stylesheet">
  <title>Project - ведение списков</title>
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
</head>

<body>
<?php
require $_SERVER['DOCUMENT_ROOT'] . ('/templates/header.php');
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="left-collum-index">
      <h1>Возможности проекта —</h1>

      <p>Вести свои личные списки, например покупки в магазине, цели, задачи и многое другое. Делится списками с друзьями и просматривать списки друзей.</p>
    </td>

    <td class="right-collum-index">
      <div class="project-folders-menu">
        <ul class="project-folders-v">
          <li class="project-folders-v-active">
            <a
<?php
if (! isset($_SESSION['is_authorized'])) {
  echo 'href="/?login=yes"';
}
?>
          >Авторизация</a>
          </li>
          <li><a href=
<?php
if (! isset($_SESSION['is_authorized'])) {
  echo '"#"';
} else {
  echo '"/profile/"';
}
?>
          >
<?php
if (! isset($_SESSION['is_authorized'])) {
  echo 'Регистрация';
} else {
  echo 'Профиль пользователя';
}
?>
          </a></li>
          <li><a href="#">Забыли пароль?</a></li>
        </ul>

        <div class="clearfix"></div>
      </div>

      <div class="index-auth">
<?php 
if (($authNeed || !empty($_POST)) && (!$logSuccess || !$passSuccess)) { 
  if (!empty($_POST) && (!$logSuccess || !$passSuccess)) {
    include __DIR__ . ('/include/error_message.php');
  }
?>
        <form action="/" method="POST">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="iat">
                <label for="login_id">Ваш e-mail:</label>
                <input id="login_id" size="30" name="login" value="<?= $loginInputVal ?>">
              </td>
            </tr>
            <tr>
              <td class="iat">
                <label for="password_id">Ваш пароль:</label>
                <input id="password_id" size="30" name="password" type="password" value="<?= $passInputVal ?>">
              </td>
            </tr>
            <tr>
              <td><input type="submit" value="Войти"></td>
            </tr>
          </table>
        </form>
<?php
} elseif ($logSuccess && $passSuccess) {
  include __DIR__ . ('/include/success_message.php');
} elseif (isset($_SESSION['is_authorized'])) {
  include __DIR__ . ('/include/already_auth_message.php');
} elseif (! isset($_SESSION['is_authorized'])) {
  include __DIR__ . ('/include/need_auth_message.php');
}
?>
      </div>
		</td>
  </tr>
</table>
    
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/templates/footer.php');
?>

</body>
</html>
