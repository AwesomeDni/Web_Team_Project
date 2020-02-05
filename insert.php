<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Project</title>
</head>
<body>
<?php
#세션 사용
session_start();
#로그인 체크
if(isset($_SESSION['id']))
{   $id = $_SESSION['id'];
    print $id.' 님';
?>
    <button onclick="location.href='logout.php'"> 로그아웃</button>
<?php
    $_SESSION['category_no'] = 1;

# 입력 폼
?>
    <form method="POST" action="post.php" autocomplete="off">
        카테고리:<select name="category">
            <option value="" selected>--카테고리를 선택하세요--</option>
            <option value="1">PHP</option>
            <option value="2">JAVA</option>
            <option value="3">PYTHON</option>
            <option value="4">Laravel</option>
            <option value="5">Eclips</option>
	    </select><br>
        제목: <input type="text" name="title"><br>
        내용: <textarea name="content"></textarea>
        <input type="submit" value="확인">
    </form>
</body>
</html>
<?php
}
# 비로그인 시 로그인 페이지로 이동
else
{   print "<script>alert('로그인 후에 작성 가능합니다.');</script>"; 
    print "<script>location.href='login.html';</script>";
}
?>