<html>
<head>
<?php
$id=$_SESSION['id'];
?>
</head>
<body>
    <!--내가 쓴글-->
    <div class="list table table-bordered">
        <center>
            <br>
            <h3>내가 쓴 글</h3>
            <br><br>
            <article>
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
                <!-- 페이징 -->
            <span class="paging">
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
                if(isset($key)) // 검색 키워드가 있을시
                {   print ($prev_block > 0) ? "<a href='".$_SERVER['PHP_SELF']."?search=$key&category=$category&page=$prev_block_page'>[이전]</a> " : "[이전] ";   }
                else // 아니면
                {   print ($prev_block > 0) ? "<a href='".$_SERVER['PHP_SELF']."?category=$category&page=$prev_block_page'>[이전]</a> " : "[이전] ";   }

                for ($i=$first_page; $i<=$last_page; $i++) 
                {   if(isset($key)) // 검색 키워드가 있을시
                    {   print ($i != $page) ? "<a href='".$_SERVER['PHP_SELF']."?search=$key&category=$category&page=$i'>$i</a> " : "<b>$i</b> ";   }
                    else // 아니면
                    {   print ($i != $page) ? "<a href='".$_SERVER['PHP_SELF']."?category=$category&page=$i'>$i</a> " : "<b>$i</b> ";   }
                    
                }
                if(isset($key)) // 검색 키워드가 있을시
                {   print ($next_page <= $total_page) ? "<a href='".$_SERVER['PHP_SELF']."?search=$key&category=$category&page=$next_block_page'>[다음]</a>" : "[다음]";    }
                else // 아니면
                {   print ($next_page <= $total_page) ? "<a href='".$_SERVER['PHP_SELF']."?category=$category&page=$next_block_page'>[다음]</a>" : "[다음]";    }

                ?>
            </span>
            <button onclick="location.href='insert.php'" class="info">글쓰기</button>
    </div>
</body>
</html>