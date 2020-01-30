<?php session_start(); ?>
<HTML><HEAD><TITLE>php test</TITLE><BODY>
<?php
    require_once("db_conn.php");
    $pdo=DB_conn();
    $cno=$_SESSION['content_no'];
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
        print "<script>location.href='show.php?id=" . $cno . "';</script>";
    } 
    catch(PDOException $Exception)
    {   $pdo->rollBack(); print"error:".$Exception->getMessage();   }
    //$_SESSION = array(); 
    //session_destroy(); 
?>
</body></html>