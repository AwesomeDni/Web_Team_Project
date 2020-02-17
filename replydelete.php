<?php
require_once("db_conn.php");
$pdo = DB_conn();
$content_no=$_POST['content_no'];

if(!isset($_SESSION['id'])){
    echo "<script>window.alert('로그인 후 이용해주세요.');location.href='login.html';</script>";
}

else{   
    $cono = $_SESSION['coment_no'];
    try{   
        $sql = "DELETE FROM coments_tb WHERE coment_no= :cono"; 
        $stmh = $pdo->prepare($sql); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->bindValue(':cono',$cono,PDO::PARAM_INT);
        $stmh->execute();//sql문 실행
        $count = $stmh->rowCount();//sql문 실행 결과의 레코드 수 반환
    } 
    catch(PDOException $Exception){//에러 발생시 $Exception이라는 이름으로 PDO예외 처리 객체 생성
        print "error:".$Exception->getMessage();  
    }

    if($stmh){
        echo "<script> window.alert('댓글 삭제 성공!'); location.href='show.php?content_no={$content_no}';</script>";
    }
    else{
        echo "<script> alert('댓글 삭제 실패'); </script>";
    }
}
?>
