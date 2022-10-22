<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/styles.css" rel="stylesheet">
    <title>Project - ведение списков</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
</head>

<body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/templates/header.php');
?>

<main style="background:#fff;">
    <h1><?= getPageTitle\getPageTitle($mainMenu); ?></h1>

    <p>main content</p>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ('/templates/footer.php');
?>
</body>
</html>
