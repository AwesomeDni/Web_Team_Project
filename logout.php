<?php
session_start();
$reset=session_destroy();//모든세션 지우기
if($reset){
    header('location: ./main.php'); 
    //세션지워서 로그아웃 성공하면 메인페이지로
    //메인페이지에서는 세션 없으면 로그인화면으로 감
}