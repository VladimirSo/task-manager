<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/check_authorized.php');
checkAuth();

require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/db_connect.php');
$pdo = getDbConnect();

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';

$sentError = '';

if (isset($_POST['sendMessage'])) {
  foreach ($_POST as &$value) {
    if (empty($value)) {
      $sentError = 'Заполнены не все обязательные поля!';

      break;
    }
  }

  if ($sentError === '') {
    $stmt = $pdo -> prepare("
      INSERT INTO messages 
      SET content = :content, title = :title, created_at = :created_at, sender = :sender, recipient = :recipient, sections_id = :sections_id");

    $stmt->execute(['content' => $_POST['msgContent'], 'title' => $_POST['msgTitle'], 'created_at' => $_POST['msgDate'], 'sender' => $_POST['msgSender'], 'recipient' => $_POST['msgRecipient'], 'sections_id' => $_POST['msgSection']]);
    $stmt->fetch(PDO::FETCH_LAZY);

    $pdo = null;
    echo "<script>alert('Сообщение отправлено')</script>";
    header('Refresh: 0.5; URL=..');
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project - Страница добавления сообщения</title>
    <link href="/styles.css" rel="stylesheet">

    <style>
      main {
        background:#fff;
        padding: 20px;
        font-size: 16px;
      }

      h1 {
        text-align:  center;
      }

      form {
        display:  flex;
        flex-direction: column;
        width:  75%;
        min-width: 280px;
        max-width: 950px;
        margin:  0 auto;
      }

      label {
        display:  flex;
        flex-direction: column;
        margin-bottom: 15px;
      }

      input, textarea {
        padding:  15px;
        font-size: inherit;
      }

      select {
        padding:  10px;
        font-size: inherit;
      }

      .send-btn {
        width: max-content;
        padding:  15px;
        margin:  25px auto 0 auto;
      }

      .error-msg {
        color:  red;
      }
    </style>
</head>
<body>
    <main>
      <h1>Страница добавления сообщения</h1>

      <form enctype="multipart/form-data" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" >
        <p>* Поля отмеченные звездочкой являются обязательными для заполнения</p>

        <label>* Заголовок:
          <input type="text" name="msgTitle" value="<?= (isset($_POST['msgTitle'])) ? $_POST['msgTitle'] : '' ?>">
        </label>
          
        <label>* Текст сообщения:
          <textarea name="msgContent"><?= (isset($_POST['msgContent'])) ? $_POST['msgContent'] : '' ?></textarea>
        </label>
          
        <label>* Пользователь (кому отправить сообщение):
          <select name="msgRecipient">
            <option selected><?= (isset($_POST['msgRecipient'])) ? $_POST['msgRecipient'] : '' ?></option>
<?php 

$stmt = $pdo -> prepare("
SELECT users.id, group_id, user_id, groups.id, login FROM users 
  LEFT JOIN groups_of_user ON user_id = users.id 
  LEFT JOIN groups ON groups.id = group_id
  WHERE name = 'writing'");

$stmt->execute();

while($row = $stmt -> fetch(PDO::FETCH_LAZY))
{

?>
            <option><?= $row['login'] ?></option>
<?php
}

?>            
          </select>
        </label>

        <label>* Раздел сообщения:
          <select name="msgSection">
            <option selected><?= (isset($_POST['msgSection'])) ? $_POST['msgSection'] : '' ?></option>
            <!-- <option>1</option> -->
<?php
// фнукция получает массив с элементами списка верхнего (начального) уровня, массив в который надо выгружать результат и объект PDO, и выдает результатом список всех элементов (разделов) меню отсортированный в зависимости от их вложенности
function getSelectList($array, $retArr, $pdo) {
  // echo '<pre>' . 'На входе:' . '<br>';
  // var_dump($array);
  // echo '</pre>';

  // идем циклом по полученному массиву 
  foreach ($array as $value) {
    // выборка поддерева по заданному узлу (от уровня этого узла к последнему уровню)
    $stmt = $pdo->prepare("
      SELECT id, title, child_id, parent_id, depth 
      FROM sections_treepath 
      LEFT JOIN sections
      ON id = child_id 
      WHERE parent_id=:id");

    $stmt->execute(['id' => $value['id']]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC); // <- массив элементов содержащихся в поддереве заданного узла

    // echo '<pre>' . 'Внутри цикла:';
    // var_dump($section);
    // echo '</pre>';

    // если длина массива больше 1 значит в поддереве заданного узла есть вложенные ветви
    if (count($sections) > 1) {
      // помещаем элемент заданного узла в массив который должна возвращать функция
      array_push($retArr, $value);

      // echo '<pre>' . 'Возвращаемый массив, 1-я ветка:';
      // var_dump($retArr);
      // echo '</pre>';

      // и удаляем его из массива для дальнейшего рассмотрения
      array_shift($sections);
      $newArr = $sections;
      // новый массив опять загоняем в функцию для получения списка
      $retArr = getSelectList($newArr, $retArr, $pdo);
    // в противном случае, и если глубина вложения заданнного узла равна 1, то значит у него нет поддерева с вложенными ветвями
    } else if ($value['depth'] === 1) {
      // помещаем элемент заданного узла в массив который должна возвращать функция
      array_push($retArr, $value);

      // echo '<pre>' . 'Возвращаемый массив, 2-я ветка: ';
      // var_dump($retArr);
      // echo '</pre>';
    }
  }
  // возвращаем массив с полученным списком
  return $retArr;
}

// выборка всех элементов верхнего уровня списка разделов сообщений
$stmt = $pdo->prepare("
  SELECT id, title, child_id, parent_id, depth 
  FROM sections 
  INNER JOIN sections_treepath 
  ON child_id = id 
  WHERE parent_id = child_id AND depth = 1");
$stmt->execute();
// $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
$firstLvlSections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$returnedArr = [];
// список разделов сообщений отсортированный по вложенности
$sectionsList = getSelectList($firstLvlSections, $returnedArr, $pdo);
// echo '<pre>' . 'returned arr:';
// var_dump($sectionsList);
// echo '</pre>';

// выборка элемента списка разделов сообщений в зависимости от id
$stmt = $pdo->prepare("
  SELECT id, title, child_id, parent_id, depth 
  FROM sections 
  INNER JOIN sections_treepath 
  ON child_id = id
  WHERE parent_id = child_id AND child_id = :id");


foreach ($sectionsList as $value) {
  // echo '<pre>' . 'list:';
  // var_dump($value['title']);
  // echo '</pre>';

  $stmt->execute(['id' => $value['id']]);
  $elem = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // echo '<pre>' . 'list:';
  // var_dump($elem);
  // echo '</pre>';

  // формируем название элемента для списка select и добавляем к нему число пробелов в зависимости от уровня вложенности элемента
  $elemTitle = '';
  if ($elem[0]['depth'] > 1) {    
    $elemTitle = str_repeat('&nbsp;&nbsp;', $elem[0]['depth'] - 1);
  }
  $elemTitle .= $elem[0]['title'];
  // формируем элемент списка select
?>
            <option value="<?= $value['id'] ?>"><?= $elemTitle ?></option>
<?php
}

$pdo = null;
?>          
          </select>
        </label>

        <input type="hidden" name="msgDate" value="<?= date('Y-m-d H:i:s'); ?>">
        <input type="hidden" name="msgSender" value="<?= $_COOKIE['task_manager_l']; ?>">

        <span class="<?php if ($sentError !== '') {echo 'error-msg';} ?>" >
          <?php if ($sentError !== '') {echo $sentError;} ?>
        </span>

        <input class="send-btn" type="submit" name="sendMessage" value="Отправить">
    </form>
  </main>
</body>
</html>
