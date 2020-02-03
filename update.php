<?php session_start(); ?>
<HTML><HEAD><TITLE>php test</TITLE><BODY>
<?php
# DB 연결
    require_once("db_conn.php");
    $pdo=DB_conn();
    $cno=$_SESSION['content_no'];
# Form에 입력된 정보로 문서 수정
    try
    {   $pdo->beginTransaction(); 
        $sql="UPDATE contents_tb SET title=:title, content=:content WHERE content_no=:cno";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':title',$_POST['title'],PDO::PARAM_STR);
        $stmh->bindValue(':content',$_POST['content'],PDO::PARAM_STR);
        $stmh->bindValue(':cno',$cno);
        $stmh->execute(); 
        $pdo->commit();
        $cnt = $stmh->rowCount();
        
        print "<script>alert('data" . $cnt . "EA update!');</script>";
        print "<script>location.href='show.php?content_no=" . $cno . "';</script>";
    } 
    catch(PDOException $Exception)
    {   $pdo->rollBack(); print"error:".$Exception->getMessage();   }
?>
</body></html>