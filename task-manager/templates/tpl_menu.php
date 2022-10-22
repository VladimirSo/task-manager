<ul class="main-menu <?= $style ?>">
<?php 
for ($i=0; $i<count($newArr); $i++) { 
	$title = cutString($newArr[$i]['title']);
?>
	<li><a href="<?= $newArr[$i]['path']; ?>" target="_self" style="<?= ($newArr[$i]['path'] == getPagePath\getPagePath()) ? 'text-decoration:underline;': '';?>"><?= $title; ?></a></li>
<?php } ?>
</ul>
