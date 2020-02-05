<?php
session_start();

require_once("db_conn.php");

$pdo = DB_conn();
$id=$_POST['id'];
$pw=$_POST['pw'];
$email=$_POST['email'];

#로그인상태확인
if(!isset($_SESSION['id'])){
    header('location: ./main.php');
}

#id확인(일치시 정보 갖고옴)
if($id!=$_SESSION['id']){
    print "<script>alert('id does not match');</script>";
}else{
    try{
        $sql="SELECT * FROM user_tb where id= :id";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_STR);
        $stmh->execute();
        $count=$stmh->rowCount();
    }catch(PDOException $Exception){
        print 'error:'.$Exception->getMessage();
    } 
}

#id 맞을때 갖고온 정보로 email과 pw맞는지 확인. 일치하면 회원정보 삭제 진행
if(!$count){
    print '해당 계정의 정보 없음';
}else{
    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
        if($row['pw']!=$pw || $row['email']!=$email){
            print "입력정보가 일치하지 않습니다.";
        }else{
            try{
                $pdo->beginTransaction();
                $sql="DELETE FROM user_tb WHERE id=:id";
                $stmh=$pdo->prepare($sql);
                $stmh->bindValue(':id',$id,PDO::PARAM_STR);
                $stmh->execute();
                $pdo->commit();
                $check=$stmh->rowCount();
            }catch(PDOException $Exception){
                $pdo->rollBack();
                print 'error : '.$Exception->getMessage();
            }
        }
    }
}

#회원탈퇴 성공하면 쿠키와 세션 지우고 메인으로
if(!$check){
    print "회원탈퇴에 실패하였습니다. 다시 시도해주세요.";
}else{
    header('location: ./main.php');
}
