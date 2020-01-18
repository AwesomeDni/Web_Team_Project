<?php
session_start();
$title = $_GET['title'];
$content = nl2br($_GET['content']);
$date=date('Y-m-d H:i:s');
?>
<?php require('db_conn.php') ?>
<?php
//쿼리문 작성
$query = "insert into contents_tb(title,content,user_no,category_no,write_dt) 
        values('".$title. "','" . $content. "'," . $_SESSION['user_no'] . "," . $_SESSION['category_no'] . ",'" . $date."')";

//쿼리보내고 결과를 변수에 저장
$result = mysqli_query($conn,$query);

print "<script>location.href='list';</script>";
?>
