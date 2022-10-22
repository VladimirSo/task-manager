<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/check_authorized.php');
checkAuth();

// var_dump($_SESSION);

require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/db_connect.php');
$pdo = getDbConnect();

$stmt = $pdo -> prepare("
SELECT users.id, group_id, user_id, groups.id, name FROM users 
  LEFT JOIN groups_of_user ON user_id = users.id 
  LEFT JOIN groups ON groups.id = group_id
  WHERE login = :login");

$stmt->execute(['login' => $_COOKIE['task_manager_l']]);

function checkWriteRight ($stmt) {
  $right = false;

  while($group = $stmt -> fetch(PDO::FETCH_LAZY))
  {
    if ($group['name'] === 'writing') {
      $right = true;
    }
  }

  return $right;
}

$is_writing = checkWriteRight($stmt);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="/styles.css" rel="stylesheet">
  <title>Project - Корреспонденция пользователя</title>
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">

  <style>
    main {
      background:#fff;
      padding: 20px;
      font-size: 16px;
    }

    h1 { 
      margin:0;
      margin-bottom: 25px;
      text-align: center;
    }

    .messages {
      display:  grid;
      grid-template-columns: 1fr 1fr;
      grid-template-rows: 1fr;
      width:  100%;
      margin-bottom: 50px;
    }

    .messages h2 {
      text-align:  center;
      border-bottom: 1px solid #999;
      padding-bottom: 10px;
    }

    .unread-msg {
      border-right:  1px solid #999;
    }

    .message-link {
      display:  flex;
      margin-bottom: 15px;
      text-decoration:  none;
    }

    .read-msg .message-link {
      margin-left: 20px;
    }

    .message-title {
      margin-right: auto;
    }

    .colormark {
      display:  block;
      width:  16px;
      height: 16px;
      border: 1px solid transparent;
      margin-left: 10px;
    }

    .unread-msg .colormark {
      margin-right: 20px;
    }

    .read-msg .colormark {
      margin-right: 0;
    }

    .btn {
      grid-column: 1 / -1;
      display: block;
      border:  1px solid #999;
      border-radius: 5px;
      width:  max-content;
      padding:  16px 15px;
      margin:  50px auto 0 auto;
      text-decoration: none;
    }

    .btn:hover {
      color:  inherit;
      text-decoration:  none;
      background-color: #d0d0d7;
    }

    .warning {
      color:  red;
    }
  </style>
</head>

<body>
  <?php
    require $_SERVER['DOCUMENT_ROOT'] . ('/templates/header.php');
  ?>

  <main>
    <h1>Корреспонденция для пользователя <?= $_COOKIE['task_manager_l']; ?></h1>

<?php
if (! $is_writing) {
?>
    <p class="warning">* Вы сможете отправлять сообщения после прохождения модерации</p>
<?php
} else {
?>
    <div class="messages">
      <div class="unread-msg">
        <h2>Непрочитанные сообщения</h2>

        <!-- <a class="message-link" href="./?id=ID">
          <span class="message-title">message.title</span>
          <span>sections.title</span>
          <span class="colormark" style="background-color: red"></span>
        </a> -->
      <?php
      
      $stmt = $pdo -> prepare("
        SELECT messages.id AS 'msg_id', messages.title AS 'msg_title', recipient, is_read, sections_id, sections.title AS 'section_title', colormark, colors.id, value FROM messages 
          LEFT JOIN sections ON sections.id = sections_id
          LEFT JOIN colors ON colors.id = colormark
          WHERE recipient = :login AND is_read = :read_mark");

      $stmt->execute(['login' => $_COOKIE['task_manager_l'], 'read_mark' => 0]);

      while($row = $stmt -> fetch(PDO::FETCH_LAZY))
        {
      ?>
        <a class="message-link" href="/posts/detail.php?id=<?= $row['msg_id']; ?>">
          <span class="message-title"><?= $row['msg_title']; ?></span>
          <span><?= $row['section_title']; ?></span>
          <span class="colormark" style="background-color: <?= $row['value']; ?>"></span>
        </a>
      <?php
        }
      ?>
      </div>

      <div class="read-msg">
        <h2>Прочитанные сообщения</h2>

      <?php
      $stmt->execute(['login' => $_COOKIE['task_manager_l'], 'read_mark' => 1]);

      while($row = $stmt -> fetch(PDO::FETCH_LAZY))
        {
      ?>

        <a class="message-link" href="/posts/detail.php?id=<?= $row['msg_id']; ?>">
          <span class="message-title"><?= $row['msg_title']; ?></span>
          <span><?= $row['section_title']; ?></span>
          <span class="colormark" style="background-color: <?= $row['value']; ?>"></span>
        </a>
      <?php
        }

      $pdo = null;
      ?>
      </div>

      <a class="btn" href="./add/">Написать сообщение</a>
    </div>
<?php
}
?>
  </main>

  <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . ('/templates/footer.php');
  ?>
</body>
</html>
