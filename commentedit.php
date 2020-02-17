<?php
session_start();
//DB 연결
require_once('db_conn.php');
$dbo=DB_conn();
$content_no = $_POST['content_no'];
$coment = $_POST['coment'];


//페이지 실행 시간
$write_dt = date('Y-m-d H:i:s');
if(!isset($_SESSION['id'])){
    echo "<script>window.alert('로그인 후 이용해주세요.');location.href='login.html';</script>";
}
else if(!$_POST['coment']){
    echo "<script>window.alert('내용을 입력하세요.');history.back(-1);</script>";
}
else{
    $user_id=$_SESSION['id'];

    try
    {   $query = 'SELECT user_no from user_tb where id=:id';
        $stmh = $dbo->prepare($query);
        $stmh->bindValue(':id',$user_id);
        $stmh->execute();
        while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
            $user_no = $row['user_no'];
        }
    }
    catch(PDOException $e){ print 'err' . $e->getMessage();}

    try
    {   $coment_sql = "UPDATE coments_tb SET coment=:coment WHERE coment_no=:coment_no";
        $coment_stt=$dbo->prepare($coment_sql);
        $coment_stt->execute();
        echo "<script>window,alert('댓글을 정상적으로 수정하였습니다!');location.href='show.php?content_no={$content_no}';</script>";
    }
    catch(PDOException $e){ print 'err: '.$e->getMessage();}
}