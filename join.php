<?php
$id=$_POST['id'];
$pw=$_POST['pw'];
$pwc=$_POST['pwc'];
$name=$_POST['name'];
$email=$_POST['email'];

$servername="localhost";
$username="root";
$password="1111";
$dbname="project";

//비밀번호와 비밀번호 재확인이 같지 않은 경우
if($pw != $pwc){
    print "비밀번호와 비밀번호 재확인이 일치하지 않습니다.";
    print "<a href=join.html>돌아가기</a>";
    exit();
}

//빈칸이 있을 경우
if($id==NULL || $pw==NULL || $name == NULL || $email==NULL){ 
    print "빈 칸을 모두 채워주세요.";
    print "<a href=join.html>돌아가기</a>";
    exit();
}

//데이터베이스 연결문
$conn=mysqli_connect($servername,$username,$password,$dbname);

//아이디 중복 있는지 select통해 찾음
$check="select *from user where id='$id'";

//sql문 연결해서 결과에 담음
$result=$conn->query($check);

if(!$result){
    trigger_error('invalid 쿼리 : '. $conn->error);
}

//sql문 실행 결과가 있다면 중복이 있는것!
if($result->num_rows == 1){
    print "중복된 id입니다.";
    print "<a href=join.html>돌아가기</a>";
    exit();
}

//회원가입 완료된 회원의 정보를 db에 저장
$join=mysqli_query($conn, "insert into user (id,pw,email,user_name)
 values('$id','$pw','$email','$name')");

if($join){
    print "회원가입 완료";
}

