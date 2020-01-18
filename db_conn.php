<?php
## C:\xampp\php\pear 에 저장해야 reqire()로 불러올 수 있음
    //mysql 접속 계정 정보 설정
    $servarname="localhost";
    $username="root";
    $password="1234";
    $dbname="web_project";
    //create connection 
    $conn=new mysqli($servarname, $username, $password, $dbname);
    //check connection 
    if($conn->connect_error){
        die("연결에 실패했습니다. 사유는 다음과 같습니다. :" ."<BR>" .$conn->connect_error);
    }
?>