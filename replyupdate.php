<?php
require_once('db_conn.php');
?>
<!DOCTYPE html>
<html>
<head>
<!DOCTYPE html>
<!-- 댓글입력창!-->
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>
<h1>댓글 수정하기</h1>
<form action="commentedit.php?coment_no=<?=$_GET['coment_no']?>" method="post">
<input type="hidden" name="content_no" value="<?=$cno?>">
<textarea name="coment" rows="8" cols="80"></textarea>
<input type="submit" value="댓글수정">
</form>
</body>
</html>