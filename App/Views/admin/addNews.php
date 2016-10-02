<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
    <label>Заголовок новости: <input type="text" name="title"></label>
    <br>
    <label>Сама новость: <textarea name="text" rows="3" cols="40"></textarea></label>

    <input type="submit">
</form>
</body>
</html>