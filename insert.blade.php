<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php 
session_start();
$_SESSION['user_no'] = 1;
$_SESSION['category_no'] = 1;
?>
    <form action="post">
        제목: <input type="text" name="title"><br>
        내용: <textarea name="content"></textarea>
        <input type="submit" value="확인">
    </form>
</body>
</html>