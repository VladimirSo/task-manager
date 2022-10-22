<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/src/core.php');
?>

<header>
    <div class="header">
        <div class="logo"><img src="/i/logo.png" alt="Project"></div>
        <div class="author">Автор: <span class="author__name">Соломонов Владимир</span></div>

          <div class="auth-link">
<?php
if (isset($_SESSION['is_authorized'])) {
  echo '<a href="' . '/src/off_authorization.php' . '">Выйти</a>';
} else {
  echo '<a href="/?login=yes">Авторизация</a>';
}
?>
          </div>
    </div>

    <div class="clearfix">
    <?php
    showMenu\showMenu($mainMenu);
    ?>
    </div>
</header>
