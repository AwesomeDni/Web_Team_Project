<?php
session_start();
//DB 연결
require_once('db_conn.php');
$dbo=DB_conn();
$co=$_POST['coment'];
$cono=$_POST['coment_no'];
try{
    $sql="SELECT * from coment_view where coment_no=:cono";
    $stt=$dbo->prepare($sql);
    $stt->bindValue(':cono',$cono,PDO::PARAM_INT);
    $stt->execute();
}
catch(PDOException $e){
    print 'err: '.$e->getMessage();
    $pdo->rollBack();
}
while($row=$stt->fetch(PDO::FETCH_ASSOC)){
    $cno=$row['content_no'];
}

//페이지 실행 시간
$write_dt = date('Y-m-d H:i:s');
try{     
    $dbo->beginTransaction();
    $coment_sql = "UPDATE coments_tb SET coment=:co WHERE coment_no=:cono";
    $coment_stt=$dbo->prepare($coment_sql);
    $coment_stt->bindValue(':co',$co,PDO::PARAM_STR);
    $coment_stt->bindValue(':cono',$cono,PDO::PARAM_INT);
    $coment_stt->execute();
    $dbo->commit();

    echo "<script>window,alert('댓글을 정상적으로 수정하였습니다!');location.href='show.php?content_no={$cno}';</script>";
}
catch(PDOException $e){
        print 'err: '.$e->getMessage();
        $pdo->rollBack();
}
?>