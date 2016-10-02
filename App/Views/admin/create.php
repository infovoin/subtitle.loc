<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>

<?php foreach ($errors as $error):?>
    <p class="bg-danger"><?= $error->getMessage();?></p>
<?php endforeach;?>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
    <label>Заголовок новости: <input type="text" name="title"></label>
    <br>
    <label>Сама новость: <textarea name="text" rows="3" cols="40"></textarea></label>

    <input type="submit">
</form>
</body>
</html>