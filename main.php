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
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="main.css">
</head>
<body>
<div class="covervid-wrapper">
    <video class="covervid-video" loop="" autoplay="" muted="">
        <source src="dreamscapes.mp4" type="video/mp4">
    </video>
    <div class="masthead-overlay"></div>
	<div class="masthead-arrow"></div>
    <div class="layer">
    <span class="content">
    <div class="bg-light">
    <div class="welcom"><font size="50%">Welcome</font></div>
    <div>
    <?php
    //세션 존재하지 않는 경우, 즉 로그인상태 아닌 경우 비회원 접속
    if(!isset($_SESSION['id']))
    {
    ?>
        <font size="4">비회원 접속</font>
        <p>로그인해주세요.</p>
        <a href="login.html"><button>로그인</button></a>
        <a href="join.html"><button>회원가입</button></a>
    <?php
    # 관리자 로그인시도 5번 실패전이면 관리자 로그인 버튼 표시
        if($flag<5 && isset($_SESSION['adm_act']))
        {
            print "<a href='login_admin.html'><button>관리자로 로그인</button></a>";
        }
    }
    else
    {
        $id = $_SESSION['id'];
        if($id == 'admin')
        {
    ?>
            <font size="5">관리자접속</font><BR><BR>
            <a href='logout.php'><button>로그아웃</button></a> 
            <a href='list.php'><button>게시판</button></a>
            <a href='adminallcate.php'><button>관리자 게시판 관리</button></a>
            <a href='userMgmt.php'><button>관리자 유저 관리</button></a>      
    <?php
        }
        else
        {
    ?>
            <font size="5">회원접속</font>
            <p><?=$id?>님 환영합니다.</p>
            <a href="logout.php"><button>로그아웃</button></a>
            <a href="mypage.php"><button>마이페이지</button></a>
            <a href="list.php"><button>게시판</button></a>
            <a href="accountDelete.html"><button>회원탈퇴</button></a>
    <?php
        }
    }
    ?>   
    </span>
    </div> 
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="covervid.min.js"></script>
<script type="text/javascript">
		$('.covervid-video').coverVid(1920, 1080);
</script>
</body>
</html>