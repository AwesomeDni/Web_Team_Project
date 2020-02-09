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
    <p id="hea1">category here</p>
    <style>
        nav{
            float:left;
            width:20%;
            height:100%;
            padding:5px;
            border-right:1px solid black;
            background-color:#B0C953;
        }
        nav ul{
            list-style-type:none;
            padding:0;
        }
        section{
            float:right;
            width:77.8%;
            height:100%;
            padding:5px;
            background-color:#ccff33;
        }
    </style>
</head>
<body>
    <hr>
    <!--유저 정보, 내가 쓴 글 확인하는 링크-->
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
    <!--내가 쓴글-->
    <section>
        <center>
            <h3>내가 쓴 글</h3>
            <br><br><hr>
            <article>
                <h3>list 내용 올곳</h3>
                <?php
                # DB연결
                $pdo = DB_conn();

                // 페이지 설정
                $page_set = 10; // 한페이지 줄수
                $block_set = 5; // 한페이지 블럭수

                try
                {   //쿼리문 작성
                    $query = "SELECT count(content_no) as total FROM show_view WHERE id='$id'";
                    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
                    $stmh->execute();
                }
                catch(PDOException $e)
                {   print 'err: '. $e->getMessage();   }

                $row=$stmh->fetch(PDO::FETCH_ASSOC);

                $total = $row['total']; // 전체글수

                $total_page = ceil ($total / $page_set); // 총페이지수(올림함수)
                $total_block = ceil ($total_page / $block_set); // 총블럭수(올림함수)

                $page = 1; // 현재페이지(넘어온값)
                if(isset($_GET['page'])){    $page= $_GET['page'];  }

                $block = ceil ($page / $block_set); // 현재블럭(올림함수)
                $limit_idx = ($page - 1) * $page_set; // 글 시작위치
                # 게시글 불러오기
                try
                {   //쿼리문 작성
                    $query = "SELECT content_no, title, view_cnt FROM show_view WHERE id='$id'" ;
                    //SELECT content_no,title,id,view_cnt from show_view order by content_no desc limit $limit_idx, $page_set";
                    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
                    $stmh->execute();
                }
                catch(PDOException $e)
                {   print 'err: '. $e->getMessage();   }
                ?>

                <table border=1>
                    <tr align="center">
                        <td>글번호</td><td>제목</td><td>조회수</td>
                    </tr>
                <?php
                # 게시글 리스트 출력
                while($row=$stmh->fetch(PDO::FETCH_ASSOC))
                {   print "<tr>";
                    print "<td align=center>" . $row['content_no'] . "</td>";
                    print "<td width=500px><a href='show.php?content_no=". $row['content_no'] ."'>" . $row['title'] . "</a></td>";
                    print "<td align=center>".$row['view_cnt']."</td>";
                    print "</tr>";
                }
                ?>
                </table>
                <?php

                // 페이지번호 & 블럭 설정
                $first_page = (($block - 1) * $block_set) + 1; // 첫번째 페이지번호
                $last_page = min ($total_page, $block * $block_set); // 마지막 페이지번호

                $prev_page = $page - 1; // 이전페이지
                $next_page = $page + 1; // 다음페이지

                $prev_block = $block - 1; // 이전블럭
                $next_block = $block + 1; // 다음블럭

                // 이전블럭을 블럭의 마지막으로 하려면...
                $prev_block_page = $prev_block * $block_set; // 이전블럭 페이지번호
                // 이전블럭을 블럭의 첫페이지로 하려면...
                //$prev_block_page = $prev_block * $block_set - ($block_set - 1);
                $next_block_page = $next_block * $block_set - ($block_set - 1); // 다음블럭 페이지번호

                // 페이징 화면
                print ($prev_page > 0) ? "<a href='".$_SERVER['PHP_SELF']."?page=$prev_page'>[prev]</a> " : "[prev] ";
                print ($prev_block > 0) ? "<a href='".$_SERVER['PHP_SELF']."?page=$prev_block_page'>...</a> " : "... ";

                for ($i=$first_page; $i<=$last_page; $i++) 
                {   print ($i != $page) ? "<a href='".$_SERVER['PHP_SELF']."?page=$i'>$i</a> " : "<b>$i</b> ";
                }

                print ($next_block <= $total_block) ? "<a href='".$_SERVER['PHP_SELF']."?page=$next_block_page'>...</a> " : "... ";
                print ($next_page <= $total_page) ? "<a href='".$_SERVER['PHP_SELF']."?page=$next_page'>[next]</a>" : "[next]";
                ?>
                </body>
                </html>

            </article>
        </center>
    </sction>
</body>
</html> 