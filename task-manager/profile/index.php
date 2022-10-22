<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/check_authorized.php');
checkAuth();

require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/db_connect.php');
$pdo = getDbConnect();

$stmt = $pdo -> prepare("
SELECT id, login, email, phone FROM users 
  WHERE login = :login");

$stmt->execute(['login' => $_COOKIE['task_manager_l']]);
$userData = $stmt->fetch(PDO::FETCH_LAZY);

// echo '<pre>';
// print_r($userData);
// echo '</pre>';
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/styles.css" rel="stylesheet">
    <title>Project - Страница профиля пользователя</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <style>
      main {
        background:#fff;
        padding: 20px;
        font-size: 16px;
      }

      h1 { 
        margin:0;
        margin-bottom: 25px;
      }

      table {
        width:  auto;
        margin:  20px 0;
        border:  1px solid;
        border-spacing: 0;
      }

      td {
        padding:  10px;
      }
    </style>
</head>

<body>
  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . ('/templates/header.php');
  ?>

  <main>
    <h1>Профиль пользователя</h1>

    <h2>Персональные данные:</h2>

    <table>
      <tr>
        <td>Ф. И. О.</td>
        <td><?= $userData['login']; ?></td>
      </tr>

      <tr>
        <td>Email</td>
        <td><?= $userData['email']; ?></td>
      </tr>

      <tr>
        <td>Телефон</td>
        <td><?= $userData['phone']; ?></td>
      </tr>
    </table>

    <div>
      <h2>Группы пользователя:</h2>

      <ul>
<?php

$stmt = $pdo -> prepare("
SELECT users.id, group_id, user_id, groups.id, description FROM users 
  LEFT JOIN groups_of_user ON user_id = users.id 
  LEFT JOIN groups ON groups.id = group_id
  WHERE login = :login");

$stmt->execute(['login' => $_COOKIE['task_manager_l']]);

while($row = $stmt -> fetch(PDO::FETCH_LAZY))
{
?>
        <li><?= $row['description'] ?></li>
<?php
}

$pdo = null;
?>
      </ul>
    </div>

  </main>

  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . ('/templates/footer.php');
  ?>
</body>
</html>
