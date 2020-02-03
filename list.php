<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Web Project</title>
</head>
<body>
<header>
    
<?php
# 로그인 체크
if(isset($_SESSION['id']))
{   $id = $_SESSION['id'];
    print $id.' 님';
    ?>
	<button onclick="location.href='logout.php'"> 로그아웃</button>
	<?php
}
else
{?>
	<button onclick="location.href='login.html'"> 로그인</button>
	<?php
}
?>
</header>
<hr>
<h1>게시글</h1>

<?php 
# DB연결
require_once('db_conn.php');
$pdo = DB_conn();

# 게시글 불러오기
try
{   //쿼리문 작성
    $query = "select content_no,title,id,view_cnt from show_view order by content_no desc";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
    $stmh->execute();
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();   }
?>

<table border=1>
    <tr align="center">
        <td>글번호</td><td>제목</td><td>작성자</td><td>조회수</td>
    </tr>
<?php
# 게시글 리스트 출력
while($row=$stmh->fetch(PDO::FETCH_ASSOC))
{   print "<tr>";
    print "<td align=center>" . $row['content_no'] . "</td>";
    print "<td width=500px><a href='show.php?content_no=". $row['content_no'] ."'>" . $row['title'] . "</a></td>";
    print "<td align=center>".$row['id']."</td>";
    print "<td align=center>".$row['view_cnt']."</td>";
    print "</tr>";
}
?>
</table>
<button onclick="location.href='insert.php'">글쓰기</button>
</body>
</html>