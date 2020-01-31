<?php

$id=$_POST['id'];
$pw=$_POST['pw'];
$pwc=$_POST['pwc'];
$email=$_POST['email'];

require_once("db_conn.php");
$pdo=DB_conn();

#비밀번호와 비밀번호 재확인이 같지 않은 경우
if($pw != $pwc){
    print "비밀번호와 비밀번호 재확인이 일치하지 않습니다.";
    print "<a href=join.html>돌아가기</a>";
    exit();
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

if($countID){
    print "중복되는 아이디입니다.";
}else if($countEmail){
    print "중복되는 이메일입니다.";
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
        print "회원가입 완료<br>\n";
        print "<a href=main.php>홈으로</a>";
    }catch(PDOException $Exception){
        $pdo->rollBack();
        print 'error:'.$Exception->getMessage();
        print "<a href=main.php>홈으로</a>";
     } 
}


