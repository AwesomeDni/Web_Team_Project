<?php

#join.html에서 post로 가져온 변수 할당
$id=$_POST['id'];
$pw=$_POST['pw'];
$pwc=$_POST['pwc'];
$email=$_POST['email'];

require_once("db_conn.php");
$pdo=DB_conn();

#비밀번호와 비밀번호 재확인이 같지 않으면 에러, 맞으면 암호 복호화
if($pw != $pwc)
{
    print "<script>alert('비밀번호와 비밀번호 재확인이 일치하지 않습니다.');</script>";
    print "<script>location.href='join.html';</script>";
}
else
{
    $pw = crypt($pw,crypt($pw,'abc'));
    //비밀번호를 'abc'라는 문자열을 salt값으로 암호화하고, 그 값을 salt값으로 다시 암호화
}


#빈칸이 있을 경우
if($id==NULL || $pw==NULL || $email==NULL){ 
    print "<script>alert('빈 칸을 모두 채워주세요.');</script>";
    print "<script>location.href='join.html';</script>";
}

#아이디 중복확인($countID에 결과 담김)
try{
    $sql="SELECT * FROM user_tb where id=:id";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(':id',$id,PDO::PARAM_STR);
    $stmh->execute();
    $countID=$stmh->rowCount();
}catch(PDOException $Exception){
   print 'error:'.$Exception->getMessage();
}

#이메일 중복확인($countEmail에 결과 담김)
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
        print "<a href=main.php><button>홈으로</button></a>";
     } 
}


