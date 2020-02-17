<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Web Project</title>
    <link rel="stylesheet" href="list.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>
<body>
<header>
    <div class='header'>
        <button id="main" onclick="location.href='main.php'">메인</button>
    <?php
    # 로그인 체크
    if(isset($_SESSION['id']))
    {   $id = $_SESSION['id'];
        ?>
        <button onclick="location.href='logout.php'"> 로그아웃</button>
        <a href="mypage.php"><button>마이페이지</button></a>
        <?php
        print '<div id="name">'.$id.' 님</div>';
    }
    else
    {?>
        <button onclick="location.href='login.html'"> 로그인</button>
        <?php
    }
    ?>
    </div>
</header>
<hr>
<!-- 검색 창 -->
<div>
    <form action="list.php" method="get" autocomplete="off">
        <input type="text" name="search">
        <input type="hidden" name="category" value="<?= $_GET['category']?>">
        <input type="submit" value="찾기">
    </form>
</div>
<div class="main">
<div class='main_content'>
<h1>게시글</h1>

<?php 
# DB연결
include('db_conn.php');
$pdo = DB_conn();

# 관리자 체크
try
{   $query = "SELECT is_admin from user_tb where id='$id'";
    $stmh = $pdo->prepare($query);
    $stmh->execute();
}
catch(PDOException $e){print 'err: '.$e->getMessage();}
$row = $stmh->fetch(PDO::FETCH_ASSOC);
$power = $row['is_admin'];
if($power==0)
{?>
    <form action="delete.php" method="POST">
        <input type="submit" value="선택삭제">
<?php
}

# 카테고리 기본 설정
$category = 0;
$_SESSION['category'] = $category;
$category_nm = '';
# 카테고리 이름 get
if(isset($_GET['category']))
{   $category = $_GET['category'];
    $_SESSION['category'] = $category;

    try
    {   $query = "SELECT category_nm from category_tb where category_no = $category";
        $stmh=$pdo->prepare($query);
        $stmh->execute();

    }
    catch(PDOException $e){ print 'err: '.$e->getMessage();}
    while($row=$stmh->fetch(PDO::FETCH_ASSOC))
    {   $category_nm = $row['category_nm'];    }
    $_SESSION['category_nm'] = $category_nm;
}

# 페이징을 위한 글 갯수 count
try
{   //쿼리문 작성
    if(isset($_GET['search']) && $category==0) // 검색시
    {   $key = $_GET['search'];
        $query = "SELECT count(content_no) as total FROM list_view where title like '%$key%'";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
    elseif(isset($_GET['search']) && $category>0) // 검색시 선택한 카테고리가 있으면
    {   $key = $_GET['search'];
        $query = "SELECT count(content_no) as total FROM list_view where title like '%$key%' and category_no=$category";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
    elseif($category>0) // 선택한 카테고리가 있으면
    {   $query = "SELECT count(content_no) as total FROM list_view where category_no=$category";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
    else // 없으면
    {   $query = "SELECT count(content_no) as total FROM list_view";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();   }

$row=$stmh->fetch(PDO::FETCH_ASSOC);
$total = $row['total']; // 전체글수
$page_set = 10; // 한페이지 줄수
$block_set = 5; // 한페이지 블럭수
$total_page = ceil ($total / $page_set); // 총페이지수(올림함수)
$total_block = ceil ($total_page / $block_set); // 총블럭수(올림함수)
$page = 1; // 현재페이지(넘어온값)
if(isset($_GET['page'])){    $page= $_GET['page'];    }
$block = ceil ($page / $block_set); // 현재블럭(올림함수)
$limit_idx = ($page - 1) * $page_set; // 글 시작위치

# 게시글 불러오기
try
{   //쿼리문 작성
    if(isset($key) && $category==0) //검색시
    {   $query = "SELECT * from list_view where title like '%$key%' order by content_no desc limit $limit_idx, $page_set";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
        print $key . ' 검색결과';
    }
    else
    if(isset($key) && $category>0)// 검색시 선택한 카테고리가 있으면
    {   $query = "SELECT * from list_view where title like '%$key%' and category_no=$category order by content_no desc limit $limit_idx, $page_set";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
        ?>
        <script>// 카테고리 이름으로 게시판 이름 변경
            $(document).ready(function(){
                $("h1").text("<?= $category_nm ?>");
            });
        </script>
        <?php
    }
    else
    if($category>0)// 선택한 카테고리가 있으면
    {   $query = "SELECT * from list_view where category_no=$category order by content_no desc limit $limit_idx, $page_set";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
        ?>
        <script>// 카테고리 이름으로 게시판 이름 변경
            $(document).ready(function(){
                $("h1").text("<?= $category_nm ?>");
            });
        </script>
        <?php
    }
    else // 없으면
    {   $query = "SELECT * from list_view order by content_no desc limit $limit_idx, $page_set";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();   }
?>

<table border=1>
    <tr align="center">
        <td>글번호</td><td>제목</td><td>작성자</td><td>조회수</td><td>작성일</td>
    </tr>
<?php
# 게시글 리스트 출력
## 관리자면 체크박스 표시
if($power==0)
{   while($row=$stmh->fetch(PDO::FETCH_ASSOC))
    {   print "<tr>";
        print "<td align=center><input type='checkbox' name='check[]' value='".$row['content_no']."'>" . $row['content_no'] . "</td>";
        print "<td width=500px><a href='show.php?content_no=". $row['content_no'] ."'>" . $row['title'] . "</a></td>";
        print "<td align=center>".$row['id']."</td>";
        print "<td align=center>".$row['view_cnt']."</td>";
        $date = $row['write_dt'];
        $dateVal = substr($date,0,10);
        print "<td align=center>".$dateVal."</td>";
        print "</tr>";
    }    
}
## 아니면
else
{   while($row=$stmh->fetch(PDO::FETCH_ASSOC))
    {   print "<tr>";
        print "<td align=center>" . $row['content_no'] . "</td>";
        print "<td width=500px><a href='show.php?content_no=". $row['content_no'] ."'>" . $row['title'] . "</a></td>";
        print "<td align=center>".$row['id']."</td>";
        print "<td align=center>".$row['view_cnt']."</td>";
        $date = $row['write_dt'];
        $dateVal = substr($date,0,10);
        print "<td align=center>".$dateVal."</td>";
        print "</tr>";
    }
}
?>
</table>
</form>
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
print ($prev_page > 0) ? "<a href='".$_SERVER['PHP_SELF']."?page=$prev_page'>[이전]</a> " : "[이전] ";
print ($prev_block > 0) ? "<a href='".$_SERVER['PHP_SELF']."?page=$prev_block_page'>...</a> " : "... ";
for ($i=$first_page; $i<=$last_page; $i++) {   
    print ($i != $page) ? "<a href='".$_SERVER['PHP_SELF']."?category=$category&page=$i'>$i</a> " : "<b>$i</b> ";
}
print ($next_block <= $total_block) ? "<a href='".$_SERVER['PHP_SELF']."?page=$next_block_page'>...</a> " : "... ";
print ($next_page <= $total_page) ? "<a href='".$_SERVER['PHP_SELF']."?page=$next_page'>[다음]</a>" : "[다음]";
?>
<button onclick="location.href='insert.php'">글쓰기</button>
</div>

<!-- 카테고리 바-->
<div class='category'>
    <ul>
        <a href="list.php?category=0"><li><b>전체글보기</b></li></a>
        <?php
        try
        {   $query = "SELECT * from category_tb";
            $stmh = $pdo->prepare($query);
            $stmh->execute();
        }
        catch(PDOException $e){ print 'err: '.$e->getMessage(); }
        while($row=$stmh->fetch(PDO::FETCH_ASSOC))
        {   $cg_no = $row['category_no'];
            $cg_nm = $row['category_nm'];
            print "<a href='list.php?category=$cg_no'><li>$cg_nm</li></a>";
        }
        ?>
    </ul>
</div>
</div>
</body>
</html>