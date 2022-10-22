<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/check_authorized.php');
checkAuth();

require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/db_connect.php');
$pdo = getDbConnect();

function getMsgId ($url) {
  $path = parse_url($url, PHP_URL_QUERY);

  $pathParts = explode('=', $path);

  $id = array_pop($pathParts);

  return $id;
}

$msgId = getMsgId($_SERVER['REQUEST_URI']);
// var_dump($msgId);

$stmt = $pdo -> prepare("
  SELECT messages.id, title, content, created_at, sender, recipient, email 
  FROM messages
  LEFT JOIN users ON login = sender
  WHERE messages.id = :id");

$stmt->execute(['id' => $msgId]);
// $stmt->execute(['id' => 1500]);

$msgData = $stmt->fetch(PDO::FETCH_LAZY);
// var_dump($msgData);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="/styles.css" rel="stylesheet">
  <title>Project - Просмотр сообщения</title>
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

    .btn {
      display: block;
      border:  1px solid #999;
      border-radius: 5px;
      width:  max-content;
      padding:  16px 15px;
      margin:  0 0 0 auto;
      text-decoration: none;
    }

    .btn:hover {
      color:  inherit;
      text-decoration:  none;
      background-color: #d0d0d7;
    }

    .message-info {
      margin:  25px 0;
      padding:  15px;
      background-color: #eee;
    }

    .message-info div {
      display: flex;
      margin-bottom: 5px;      
    }

    .message-info div span:first-child  {
      display:  block;
      width:  100%;
      max-width:  150px;
    }

    .message-content {
      padding:  0 15px;
    }

    .error {
      font-size: 24px;
      font-weight: 500;
      text-align:  center;
    }
  </style>
</head>

<body>
  <main>
    <h1>Просмотр сообщения </h1>

    <a class="btn" href="./index.php">Вернуться к списку сообщений</a>
<?php
  if ($msgData !== false) {
    $msgTitle = $msgData['title'];
    $msgContent = $msgData['content'];
    $msgSender = $msgData['sender'];
    $msgRecipient = $msgData['recipient'];
    $msgDate = $msgData['created_at'];
    $msgEmail = $msgData['email'];

    if ($msgRecipient === $_COOKIE['task_manager_l'] or $msgSender === $_COOKIE['task_manager_l']) {
?>
    <div class="message-info">
      <div class=message-title>
        <span>Заголовок: </span><span><?= $msgTitle; ?></span>
      </div>

      <div class="message-date">
        <span>Дата отправки: </span><time><?= $msgDate; ?></time>
      </div>

      <div class="sender-name">
        <span>Отправитель: </span><span><?= $msgSender; ?></span>
      </div>

      <div class="sender-email" >
        <span>E-mail: </span><a href="mailto:example@mail.com"><?= $msgEmail; ?></a>
      </div>
    </div>

    <div class="message-content">
      <p>
        <?= $msgContent; ?>
      </p>
    </div>
  <?php
      $stmt = $pdo -> prepare("
        UPDATE messages SET is_read = 1
        WHERE id = :id");

      $stmt->execute(['id' => $msgId]);
  
    } else {
      header('Location: /index.php');
    }
  } else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  ?>
    <p class="error">404 Not Found: Сообщение не найдено!</p>
<?php
  }

$pdo = null;  
?>    
  </main>
</body>

</html>

