<?php session_start(); ?>
<HTML>
<HEAD>
    <TITLE>PHP test</TITLE>
</HEAD>
<BODY>
<?php
require_once("db_conn.php");
$pdo = DB_conn();

# 관리자가 list.php에서 선택 삭제시
if(isset($_POST['check']))
{
    $cnt = count($_POST['check']);
    for($i=0; $i<$cnt; $i++)
    {   try
        {   $sql = "DELETE FROM contents_tb WHERE content_no= :cno"; 
            $stmh = $pdo->prepare($sql); //sql문을 인잭션으로 부터 보호하기위한 처리
            $stmh->bindValue(':cno',$_POST['check'][$i],PDO::PARAM_INT);
            $stmh->execute();//sql문 실행
            $count = $stmh->rowCount();//sql문 실행 결과의 레코드 수 반환
        } 
        catch(PDOException $Exception)//에러 발생시 $Exception이라는 이름으로 PDO예외 처리 객체 생성
        {   print "error:".$Exception->getMessage();  }    
    }
    if($stmh){
        echo "<script> alert('삭제 성공'); </script>";
    }
    else{
        echo "<script> alert('삭제 실패'); </script>";
    }
}
# show.php에서 삭제시
else
{   $cno = $_SESSION['content_no'];
    try
    {   $sql = "DELETE FROM contents_tb WHERE content_no= :cno"; 
        $stmh = $pdo->prepare($sql); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->bindValue(':cno',$cno,PDO::PARAM_INT);
        $stmh->execute();//sql문 실행
        $count = $stmh->rowCount();//sql문 실행 결과의 레코드 수 반환
    } 
    catch(PDOException $Exception)//에러 발생시 $Exception이라는 이름으로 PDO예외 처리 객체 생성
    {   print "error:".$Exception->getMessage();  }

    if($stmh){
        echo "<script> alert('삭제 성공'); </script>";
    }
    else{
        echo "<script> alert('삭제 실패'); </script>";
    }
}
?>
<meta http-equiv='refresh' content='0, list.php'>   
</BODY>
</HTML>