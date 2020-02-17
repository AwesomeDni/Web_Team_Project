<?php
session_start();
# 관리자 로그인 시도 실패 변수
$flag=0;
if(isset($_SESSION['flag'])) 
{   $flag=$_SESSION['flag'];}
?>
<html>
<head>
<title>home page</title>
<meta http-equiv="content-type" content="txt/html" ; charset="utf-8">

<style>
    body {
        text-align: center;
    }
</style>
</head>
<body>
<h2>Home Page<h2>
<hr>
<?php
//세션 존재하지 않는 경우, 즉 로그인상태 아닌 경우 비회원 접속
if(!isset($_SESSION['id'])){
?>
<font size="4">비회원 접속</font>
<p>로그인해주세요.</p>
<h4>마이페이지는 로그인 후 사용해주세요.</h4>
<h4>바로 이동시 이 화면으로 돌아옵니다.</h4>
<a href="login.html"><button>로그인</button></a>
<a href="join.html"><button>회원가입</button></a>
<?php
    # 관리자 로그인시도 5번 실패전이면 관리자 로그인 버튼 표시
    if($flag<5)
    {
    ?>
        <a href="login_admin.html"><button>관리자로 로그인</button></a>
    <?php
    }
}else{
    $id = $_SESSION['id']
?>
<font size="5">회원접속</font>
<p><?=$id?>님 환영합니다.</p>
<a href="logout.php"><button>로그아웃</button></a>
<a href="mypage.php"><button>마이페이지</button>
<a href="list.php"><button>게시판</button>
<a href="accountDelete.html"><button>회원탈퇴</button></a>
<?php
    if($id=='admin')
    {
        print "<a href='adminallcate.php'><button>관리자 게시판 관리</button></a>";
        print "<a href='userMgmt.php'><button>관리자 유저 관리</button></a>";
    }
?>
<?php
}
?>
</body>
</html>
