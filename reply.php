<?php
require_once('db_conn.php');
$dbo = DB_conn();
$cno =$_GET['content_no'];
$content_no_sql = "select * from contents_tb where content_no = $cno";
$content_stt=$dbo->prepare($content_no_sql);
$content_row=$content_stt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>
<form action="replyinsert.php" method="post">
<input type="hidden" name="content_no" value="<?=$content_row['content_no']?>">
<textarea name="coment" rows="8" cols="80"></textarea>
<input type="submit" value="댓글쓰기">
</form>
</body>
</html>
<?php
$coment_sql = "select * from coments_tb where content_no={$_GET['content_no']}";
$coment_stt=$dbo->prepare($coment_sql);
$coment_stt->execute();

if(!isset($_SESSION['user_id'])){
    $id = 'guest';
}
else{
    $id=$_SESSION['user_id'];
}