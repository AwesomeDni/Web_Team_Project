<?php

$id=$_POST['id'];
$pw=$_POST['pw'];
$pwc=$_POST['pwc'];
$email=$_POST['email'];

require_once("db_conn.php");
$pdo=DB_conn();

#비밀번호와 비밀번호 재확인이 같지 않으면 에러, 맞으면 암호 복호화
if($pw != $pwc)
{
    print "비밀번호와 비밀번호 재확인이 일치하지 않습니다.";
    print "<a href=join.html>돌아가기</a>";
    exit();
}
else
{
    $pw = crypt($pw,crypt($pw,'abc'));
}


#빈칸이 있을 경우
if($id==NULL || $pw==NULL || $email==NULL){ 
    print "빈 칸을 모두 채워주세요.";
    print "<a href=join.html>돌아가기</a>";
    exit();
}

#아이디 중복확인
try{
    $sql="SELECT * FROM user_tb where id=:id";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(':id',$id,PDO::PARAM_STR);
    $stmh->execute();
    $countID=$stmh->rowCount();
}catch(PDOException $Exception){
   print 'error:'.$Exception->getMessage();
}

#이메일 중복확인
try{
    $sql="SELECT * FROM user_tb WHERE email=:email";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(':email',$email,PDO::PARAM_STR);
    $stmh->execute();
    $countEmail=$stmh->rowCount();
}catch(PDOException $Exception){
   print 'error:'.$Exception->getMessage();
} 

##조건 확인 후 회원가입 쿼리문 진행
if($id=='admin'){
    print "<script>alert('쓸 수 없는 아이디입니다.');</script>";
    print "<script>location.href='join.html';</script>";
}elseif($countID){
    print "<script>alert('중복되는 아이디입니다.');</script>";
    print "<script>location.href='join.html';</script>";
}else if($countEmail){
    print "<script>alert('중복되는 이메일입니다.');</script>";
    print "<script>location.href='join.html';</script>";
}else{
    try{
        $pdo->beginTransaction();
        $sql="INSERT INTO user_tb (id,pw,email) VALUES (:id,:pw,:email)";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_STR);
        $stmh->bindValue(':pw',$pw,PDO::PARAM_STR);
        $stmh->bindValue(':email',$email,PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
        print "<script>alert('회원가입 완료');</script>";
        print "<script>location.href='main.php';</script>";
    }catch(PDOException $Exception){
        $pdo->rollBack();
        print 'error:'.$Exception->getMessage();
        print "<a href=main.php>홈으로</a>";
     } 
}


