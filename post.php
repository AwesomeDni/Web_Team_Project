<?php
session_start();
$title = $_POST['title'];
$content = ($_POST['content']);
$date=date('Y-m-d H:i:s');
?>
<?php require('db_conn.php') ?>
<?php
//쿼리문 작성
$pdo = DB_conn();
try
{   //쿼리문 작성
    $query = "insert into contents_tb(title,content,user_no,category_no,write_dt) 
            values( :title, :content, :user_no, :category_no, :date)";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->bindValue(':title',$title);
    $stmh->bindValue(':content',$content);
    $stmh->bindValue(':user_no',$_SESSION['user_no']);
    $stmh->bindValue(':category_no',$_SESSION['category_no']);
    $stmh->bindValue(':date',$date);
    $stmh->execute();
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();
    $pdo->rollBack();
}

try{
    //쿼리문 작성
    $query = "select content_no from contents_tb where user_no= :no order by write_dt desc limit 1";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->bindValue(':no',$_SESSION['user_no'],PDO::PARAM_INT);
    $stmh->execute();
    $row=$stmh->fetch(PDO::FETCH_ASSOC);
    $cno=$row['content_no'];
}
catch(PDOException $e){
    print 'err: '. $e->getMessage();
    $pdo->rollBack();
}

print "<script>location.href='show.php?id=".$cno."';</script>";
?>
