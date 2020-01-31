<html>
<head>
<title>home page</title>
<meta http-equiv="content-type" content="txt/html" ; charset="utf-8">
</head>
<style>
body {text-align: center; padding: 10px;}
</style>
<body>
<?php
session_start();
if(!isset($_SESSION['id'])){ //세션 존재하지 않는 경우, 즉 로그인상태 아닌 경우
    header('Location:./login.html'); //로그인페이지로 바로 이동
}
?>
<h2>Home Page<h2>
<hr>
<pre>
홈 (로그인 성공)<br>
여기는 홈페이지 입니다(게시글 페이지) <br><br>
<a href=logout.php><button>로그아웃</button></a>
<pre>
</body>
</html>
