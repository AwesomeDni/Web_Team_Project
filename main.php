<?php
session_start();
?>
<html>
<head>
<title>home page</title>
<meta http-equiv="content-type" content="txt/html" ; charset="utf-8">
</head>
<style>
body {text-align: center;}
</style>
<body>
<h2>Home Page<h2>
<hr>
<?php
//세션 존재하지 않는 경우, 즉 로그인상태 아닌 경우 비회원 접속
if(!isset($_SESSION['id'])){
?>
<font size="4">비회원 접속</font>
<p>로그인해주세요.</p>
<a href="login.html"><button>로그인</button></a>
<a href="join.html"><button>회원가입</button></a>
<?php
}else{
    $id = $_SESSION['id']
?>
<font size="5">회원접속</font>
<p><?=$id?>님 환영합니다.</p>
<a href=logout.php><button>로그아웃</button></a>
<a href="accountDelete.html"><button>회원탈퇴</button></a>
<?php
}
?>
</body>
</html>
