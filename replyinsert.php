<?php
session_start();
//DB 연결
require_once('db_conn.php');
$dbo=connect();
$coment_no = $_POST['coment_no'];
$coment = $_POST['coment'];
$user_no = $_POST['user_no'];

//페이지 실행 시간
$write_dt = time();
if(!isset($_SESSION['user_id'])){
    echo "<script>window.alert('로그인 후 이용해주세요.');location.href='login.php';</script>";
}
else if(!$_POST['content']){
    echo "<script>window.alert('내용을 입력하세요.');history.back(-1);</script>";
}
else{
    $user_id=$_SESSION['user_id'];
    $coment_sql = "INSERT INTO coments_tb (content_no, coment_no, coment, user_no, write_dt) VALUES(:content_no, :coment_no, :coment, :user_no, $write_dt)";
    $coment_stt=$dbo->prepare($coment_sql);
    $coment_stt=execute(array(':content_no'=>$_POST['content_no'], ':user_id'=>$user_id,':coment'=>$_POST['coment']));
    echo "<script>window,alert('댓글을 정상적으로 등록하였습니다!');location.href='show.php?content_no={$_POST['content_no']}';</script>";
}


/*insert
try
    {   
        $sql = ""; 
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':coment_no',$coment_no);
        $stmh->bindValue(':coment',$coment);
        $stmh->bindValue(':user_no',$user_no);
        $stmh->bindValue(':write_dt',$write_dt);
        $stmh->execute();
        $result = $stmh->fetch(PDO::FETCH_ASSOC);
        
    }
 
catch(PDOException $e) 
    {   
        $pdo->rollBack();
        print"error:".$e->getMessage(); 
    }
try{
    $sql2 = "SELECT coment_no FROM coments_tb WHERE user_no=:user_no ORDER BY write_dt desc limit 1";
    $stmh=$pdo->prepare($sql2);
    $stmh->bindValue(':user_no',$user_no,PDO::PARAM_INT);
    $stmh->execute();
    $row=$stmh->fetch(PDO::FETCH_ASSOC);
    $cono=$row['coment_no'];
}
catch(PDOException $e){
    print 'err: '. $e->getmessage();
    $pdo->rollBack();
}

print "<script>location.href='show.php?content_no=".$cono."';</script>";
?>
*/
?>