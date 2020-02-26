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
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="http://code.jquery.com/jquery.js"></script>
    
    <style>
        li { list-style: none; text-align: center; margin-right: 15px;}
        a:hover{color:black; text-decoration: none;
                animation-duration: 3s; animation-name: rainbowLink; animation-iteration-count: infinite; } 
                @keyframes rainbowLink {     
                0% { color: #ff2a2a; }
                15% { color: #ff7a2a; }
                30% { color: #ffc52a; }
                45% { color: #43ff2a; }
                60% { color: #2a89ff; }
                75% { color: #202082; }
                90% { color: #6b2aff; } 
                100% { color: #e82aff; }
            }
        .li{margin-left: auto; margin-right: auto;}
        .user{
            float: left; border-right:1px solid black; 
            height: 100%; width: 20%; background-color: #bfbfbf;
            }
        .list{float: auto; 
            height: 100%; width: 100%;}
        .nav-item{margin-left:380px;}
        @font-face{font-family:'A프로젝트'; src:url('A프로젝트.woff'); }
        body{font-family:'A프로젝트';}
        @font-face{font-family:'A펜글씨B'; src:url('A펜글씨B.woff');}
        .catename
        {font-family:'A펜글씨B';}
    </style>
</head>
<body>
    <div class="container">
        <center>
            <nav class="disabled">
                <br>
                <a href="main.php"> 메인으로</a></li> <!--누르면 main.php 로 이동-->
            </nav>
        </center>
    </div>
    <hr>
    <span class='category'>
        <div class="catename">
        <center>
            <h7>▼카테고리 선택▼</h7><br>
        </div>
    <ul>
        <ul class="nav">
            <li class="nav-item">
            <center>
            <a href="list.php">전체게시글로</a></li> <!--누르면 list.php 로 이동-->
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
            </li>
        </ul>
    </ul>
    </center>
    </span>

    <!--유저 정보, 내가 쓴 글 확인하는 링크-- 기본-->
    <div class='user'>
        <nav id="sidebar">
            <div class="p-4">
            <ul class="list-unstyled components mb-5">
                <center>
                    <br>
                    <span class="active">★<?=$id?>님★</span> <!--'접속중인 유저의 id'+'님' 이라고 출력됨-->
                    <br><hr>
                    <span class="out">
                        <button class="btn btn-primary" onclick="location.href='logout.php'">로그아웃</button> <!--누르면 logout.php로 이동함-->
                        <br>
                        <button class="btn btn-primary" onclick="location.href='accountDelete.html'">회원탈퇴</button> <!--누르면 accountDelete.html로 이동함-->
                    </span>
                </center>
            </ul>
            </div>
        </nav>
    </div>
    <?php
        if(!isset($_SESSION['id'])){ //만약 세션으로 받은 id가 없다면 아래 내용 실행 
            header('location: ./main.php'); //자동으로 main.php 로 이동함.
        }
    ?>
    <div class="list">
    <?php
    //내가 쓴 글
# DB연결
$pdo = DB_conn();
$id=$_SESSION['id'];
?>
<style>
    @font-face{font-family:'A타이틀고딕2'; src:url('A타이틀고딕2.woff');} 
    .list
    {font-family:'A타이틀고딕2';}
    @font-face{font-family:'A펜글씨B'; src:url('A펜글씨B.woff');}
    .name
    {font-family:'A펜글씨B';}
</style>
</head>
<body>
    <!--내가 쓴글-->
    <div class="list table table-bordered">
        <center>
            <div class="name">
                <br>
                <h3>내가 쓴 글</h3>
            </div>
            <br><br>
            <article>
                <?php
                    //총 데이터의 수 
                    try {
                        $query="SELECT count(content_no) as total FROM show_view ";
                        $stmh=$pdo->prepare($query);
                        $stmh->execute();
                    } catch (PDOException $exception) {
                        print '에러1:'.$exception->getMessage();
                    }

                    $row=$stmh->fetch(PDO::FETCH_ASSOC);
                    $total = $row['total']; // 전체글수
                    $page=1; //현재 페이지
                    if(isset($_GET['page'])){    $page= $_GET['page'];    } //현재 페이지(넘어온값)
                    //$page=1; //현재 페이지
                    $page_set=10; // 한 페이지에 표시되는 줄 수 
                    $block_set=4; // 나타날 블럭수

                    $total_page=ceil($total/$page_set); //총 페이지=반올림(전체글수/한 페이지에 나오는 갯수)
                    $total_block=ceil($total_page/$block_set); //총 블럭=반올림(총 페이지/나타날 블럭 수)
                    $now_block=ceil($page/$block_set); //현재 블럭=반올림(현재 페이지/나타날 블럭 수)

                    $start_page=($now_block*$block_set)-3; //시작페이지 
                    if($start_page<=1){$start_page=1;}
                    $end_page=$now_block*$block_set; //마지막 페이지 
                    if($total_page<=$end_page){$end_page=$total_page;}

                    $limit_idx = ($page - 1) * $page_set; // limit시작위치
                    $prev_page=$page - 1; // 이전페이지
                    $next_page=$page + 1; // 다음페이지

                    $prev_block = $block_set - 1; // 이전블럭
                    $next_block = $block_set + 1; // 다음블럭
                    if($next_page>=$total_page){
                        $next_page=$total_page;
                    }

                    //이전 블럭을 누르면 항목중 가장 첫번째 작은 수(첫번째 항목)으로 나오게 함  
                    //$prev_block_page=$prev_block*$block-($block + 1 ); // 이전블럭 페이지 번호 
                    //$next_block_page=$next_block*$block-($block - 1); // 다음블럭 페이지 번호 

                ?>

                <!--페이징-->
                    <div>
                        <?php
                        //이전 페이지
                        echo ($prev_page>0)?"<a href='".$_SERVER['PHP_SELF']."?page=$prev_page'>[이전 페이지로]</a>":"";
                        //현재 보는 중인 페이지 
                        for ($i=$start_page; $i<=$end_page; $i++){ //1,2,3 같은 페이지 번호 나오는 곳 
                            echo($i!=$page)?"<a href='".$_SERVER['PHP_SELF']."?page=$i'>$i</a>":"<b>$i</b>";
                        }
                        //다음 페이지\
                        echo ($prev_page>=0)?"<a href='".$_SERVER['PHP_SELF']."?page=$next_page'>[다음 페이지로]</a>":"[다음]";
                        //echo ($prev_page>0)?"<a href='".$_SERVER['PHP_SELF']."?page=$next_page'>[다음 페이지로]</a>":"[다음]";
                        ?>
                    </div>
                    <?php

                    ?>

                <?php
                # 게시글 불러오기
                try
                {   //쿼리문 작성
                    $query = "SELECT content_no, title, view_cnt, category_nm FROM show_view WHERE id='$id' limit $limit_idx,$page_set";
                    //SELECT content_no,title,id,view_cnt from show_view order by content_no desc limit $limit_idx, $page_set";
                    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
                    $stmh->execute();
                }
                catch(PDOException $e)
                {   print 'err: '. $e->getMessage();   }
                ?>
                    <table border=1>
                        <tr align="center">
                        <td>카테고리</td><td>글번호</td><td>제목</td><td>조회수</td>
                        </tr>
                    <?php
                # 게시글 리스트 출력
                    while($row=$stmh->fetch(PDO::FETCH_ASSOC))
                    {   print "<tr>";
                        print "<td align=center>".$row['category_nm']."</td>";
                        print "<td align=center>" . $row['content_no'] . "</td>";
                        print "<td width=500px><a href='show.php?content_no=". $row['content_no'] ."'>" . $row['title'] . "</a></td>";
                        print "<td align=center>".$row['view_cnt']."</td>";
                        print "</tr>";
                    }
                    ?>
                    </table>
                    <?php

                    ?>
            </article>
        </center>
    </div>
</body>
</html> 