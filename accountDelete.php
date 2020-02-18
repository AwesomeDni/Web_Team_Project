<?php
session_start();

require_once("db_conn.php");

$pdo = DB_conn();
$pw=crypt($_POST['pw'],crypt($_POST['pw'],'abc'));
$email=$_POST['email'];
$check=0;

if(isset($_POST['delete']) && $_POST['delete']=='NO'){
    print "<script>alert('회원탈퇴 취소')</script>";
    header('location: ./accountDelete.html');
}else if (isset($_POST['delete']) && $_POST['delete']=='default'){
    print "<script>alert('로그인해주세요. 탈퇴 진행상황에 대한 정보 전달에 실패하였습니다. 관리자에게 문의 바랍니다.');</script>";
    header('location: ./accountDelete.html');
}else if (isset($_POST['delete']) && $_POST['delete']=='YES'){

#로그인상태확인
if(!isset($_SESSION['id'])){
    print "<script>alert('로그인해주세요.');</script>";
    header('location: ./main.php');
}else{
    $id=$_POST['id'];
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
## admin 삭제 시도시 돌아감
if(!$count){
    print '해당 계정의 정보 없음';
}else if($id == 'admin'){
    print "<script>alert('관리자 계정은 삭제할 수 없습니다.');</script>";
    print "<script>location.href='accountDelete.html';</script>";
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

#회원탈퇴 성공하면 logout.php이용해 쿠키와 세션 지우고 메인으로
if(!$check){
    print "<script>alert('회원탈퇴에 실패하였습니다. 다시 시도해주세요.');</script>";
    print "<script>location.href='accountDelete.html';</script>";
}else{
    header('location: ./logout.php');
}

}