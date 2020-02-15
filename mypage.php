<?php
    session_start();
    //아이디, 유저, list 함수 불러오기
?>
<?php
    require_once("db_conn.php");
    $id=$_SESSION['id'];
?>
<html>
<head>
    <title>mypage</title>
    <meta http-equiv="Content-Type" content="text/html charset=utf8";>
    <link rel="stylesheet" href="mypage.css"
</head>
<body>
    <header>
        <center>
        <a href="main.php"> 메인으로</a>
        <a href="list.php"> 전체게시글</a>
        </center>
    </header>
    <hr>
    <span class='category'>
    <ul>
        <center>
        <b>▼카테고리 선택▼</b><br>
        <a href="list.php?category=0"><b>전체글보기</b></a>
        <a href="list.php?category=1">PHP</a>
        <a href="list.php?category=2">JAVA</a>
        <a href="list.php?category=3">PYTHON</a>
        <a href="list.php?category=4">Laravel</a>
        <a href="list.php?category=5">Eclips</a>
        </center>
    </ul>
    </span>
    <hr>
    <!--유저 정보, 내가 쓴 글 확인하는 링크-- 기본-->
    <aside>
        <nav>
            <ul>
                <center>
                    <li><?=$id?>님</li>
                    <br><br><br><hr>
                    <li><a href="#list">내가 적은글</a></li>
                    <br><br>
                    <li class="out">
                        <button onclick="location.href='logout.php'">로그아웃</button>
                        <br>
                        <button onclick="location.href='accountDelete.html'">회원탈퇴</button>
                    </li>
                </center>
            </ul>
        </nav>
    </aside>
    <?php
        if(!isset($_SESSION['id'])){
            header('location: ./main.php'); 
        }
    ?>
    <?php
    //내가 쓴 글
        include 'mypagelist.php';
    ?>
</body>
</html> 