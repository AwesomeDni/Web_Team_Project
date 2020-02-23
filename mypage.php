<?php
    session_start();
    //아이디, 유저, list 함수 불러오기
?>
<?php
    require_once("db_conn.php"); /*db연결을 위해 db.conn을 불러온다.*/
    $pdo = DB_conn(); /*db와 연결한다.*/
    $id=$_SESSION['id']; /*db와 연결한다.*/
?>
<html>
<head>
    <title>mypage</title>
    <meta http-equiv="Content-Type" content="text/html charset=utf8";>  <!--유니코드 문자를 쓰고, 텍스트 기반-->
</head>
<body>
    <header>
        <center>
        <a href="main.php"> 메인으로</a> <!--누르면 main.php 로 이동-->
        <a href="list.php"> 전체게시글</a> <!--누르면 list.php 로 이동-->
        </center>
    </header>
    <hr>
    <span class='category'>
    <ul>
        <center>
        <b>▼카테고리 선택▼</b><br>
        <a href="list.php?category=0"><b>전체글보기</b></a> <!--list.php에서 categoey=0 에 해당하는 것 가져옴-->
        <?php
            try
            {   $query = "SELECT * from category_tb";  //category_tb 테이블을 조회하는 쿼리문 생성
                $stmh = $pdo->prepare($query); //$stmh는 $query의 쿼리문을 담은 것
                $stmh->execute(); //stmh를 실행
            }
            catch(PDOException $e){ print 'err: '.$e->getMessage(); } //*error 발생시 error 코드를 잡고 에러가 일어난 줄 수, 문구 생성
            while($row=$stmh->fetch(PDO::FETCH_ASSOC)) /*$row($stmh의 칼럼을 키로 값는다) 가 있는 동안 반복*/
            {   $cg_no = $row['category_no']; //$cg_no는 category_no 를 줄씩 읽은 것. 
                $cg_nm = $row['category_nm']; //$cg_nm category_nm 를 줄씩 읽은 것.
                print "<a href='list.php?category=$cg_no'><li>$cg_nm</li></a>"; //$cg_no로 조회된 카테고리들 중 $cg_nm를 선택하는 창 
            }
        ?>
        </center>
    </ul>
    </span>
    <hr>
    <!--유저 정보, 내가 쓴 글 확인하는 링크-- 기본-->
    <aside>
        <nav>
            <ul>
                <center>
                    <li><?=$id?>님</li> <!--'접속중인 유저의 id'+'님' 이라고 출력됨-->
                    <br><br><br><hr>
                    <li><a href="#list">내가 적은글</a></li> <!--#list에서 적은 글 리스트 갖고옴-->
                    <br><br>
                    <li class="out">
                        <button onclick="location.href='logout.php'">로그아웃</button> <!--누르면 logout.php로 이동함-->
                        <br>
                        <button onclick="location.href='accountDelete.html'">회원탈퇴</button> <!--누르면 accountDelete.html로 이동함-->
                    </li>
                </center>
            </ul>
        </nav>
    </aside>
    <?php
        if(!isset($_SESSION['id'])){ //만약 세션으로 받은 id가 없다면 아래 내용 실행 
            header('location: ./main.php'); //자동으로 main.php 로 이동함.
        }
    ?>
    <?php
    //내가 쓴 글
        include 'mypagelist.php'; //mypagelist.php 의 내용을 갖고옴 
    ?>
</body>
</html> 