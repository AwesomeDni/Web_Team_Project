<?php
session_start();
$id=$_POST['id'];
$pw=$_POST['pw'];

$servername="localhost";
$username="root";
$password="1111";
$dbname="project";

$conn=mysqli_connect($servername,$username,$password,$dbname);

$check="select * from user where id='$id'";

$result=$conn->query($check);

if($result->num_rows==1){
    $row=$result->fetch_array(MYSQLI_ASSOC); 
    //sql문 결과에서 하나의 열을 배열로 받아옴. 여기서는 연관배열로 받아오는 옵션줌 (MYSQLI_NUM 도 있음)
    if($row['pw']==$pw && $row['id']==$id){ //컬럼이름을 key로 값 찾기 가능해짐
        $_SESSION['id']=$id;
        if(isset($_SESSION['id'])){
            header('location:./main.php');
        }else{
            print "세션 저장 실패";
        }
    }else{
        print "패스워드나 아이디가 틀렸습니다.";
    }
}else{
    print "패스워드나 아이디가 틀렸습니다.";
}
