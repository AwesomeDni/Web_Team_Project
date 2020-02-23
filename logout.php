<?php
session_start();
$reset=session_destroy();//모든세션 지우기
#모든 쿠키 지우기
$past = time() - 3600;
foreach ( $_COOKIE as $key => $value ) 
{   setcookie( $key, $value, $past );  }

if($reset){
    header('location: ./main.php'); 
    //세션지워서 로그아웃 성공하면 메인페이지로
}else{
    print "<script>alert('세션정보 삭제에 실패. 관리자에게 문의해주세요.');</script>";
    print "<script>location.href='main.php';</script>";
}