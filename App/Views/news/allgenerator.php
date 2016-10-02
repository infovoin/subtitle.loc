<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php foreach ($items() as $news): ?>
<h3><?php echo $news->title ?><h3>
        <?php echo $news->text ?>
        <hr>
        <?php endforeach ?>
</body>
</html>