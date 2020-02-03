<title>Web Project</title>
<?php session_start(); ?>
<header>
<?php
#로그인 체크
if(isset($_SESSION['id']))
{   $id = $_SESSION['id'];
    print $id.' 님';
    ?>
	<button onclick="location.href='logout.php'"> 로그아웃</button>
<?php
}
else
{   $id='';
?>
	<button onclick="location.href='login.html'"> 로그인</button>
<?php
}
?>
</header>
<hr>

<?php
#DB연결
require_once('db_conn.php');
$pdo = DB_conn();

#사용자가 요청한 문서 번호 획득
$_SESSION['content_no'] = $content_no = $_GET['content_no'];

#이전,다음 게시글 보기
try
{   //쿼리문 작성
    $query = "select max(content_no) max, min(content_no) min from contents_tb";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->execute();
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();
$pdo->rollBack();
}
##글번호의 최대,최소값을 구함
while ($row=$stmh->fetch(PDO::FETCH_ASSOC)) {
    $max=$row['max'];
    $min=$row['min'];
}
##마지막 글에는 다음 페이지 표시 x
if (!($max==($content_no))) {
    print "<button onclick='location.href=\"show.php?content_no=".($content_no + 1)."\"'>다음 글 ▲</button>";
}
##처음 글에는 이전 페이지 표시 x 
if (!($min==($content_no))) {
    print "<button onclick='location.href=\"show.php?content_no=".($content_no - 1)."\"'>이전 글 ▼</button>";
}

#문서의 조회수
$cnt_flag = 0;
if(!isset($_COOKIE['view'.$content_no])) {//한 방문자가 새로고침으로 조회수를 올리는것 방지
    $cnt_flag += 1;
    setcookie('view'.$content_no,$cnt_flag);
    try
    {   //조회수 증가 쿼리
        $query = "update contents_tb set view_cnt = view_cnt + 1 where content_no = :no";
        $stmh=$pdo->prepare($query);
        $stmh->bindValue(':no',$content_no);
        $stmh->execute();
    }
    catch(PDOException $e)
    {   print 'err: '. $e->getMessage();
    $pdo->rollBack();
    }
}

#DB에서 문서내용 가져오기
try
{   //쿼리문 작성
    $query = "select * from show_view where content_no = :no";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->bindValue(':no',$content_no);
    $stmh->execute();
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();
    $pdo->rollBack();
}
?>
<TABLE border="1">
<TBODY>
    <TR>
        <TH>No</TH>
        <TH width=100px>Title</TH>
        <TH width=300px>contetnt</TH>
        <TH width=70px>작성자</TH>
        <TH>작성일</TH>
        <TH width=70px>조회수</TH>
    </TR>
<?php
#문서 내용 출력
$writer='';//작성자 저장하기 위한 변수
while($row=$stmh->fetch(PDO::FETCH_ASSOC))//PDO::FETCH_ASSOC 결과값을 한 행씩 읽어오는 메소드
{?>
    <TR>
        <TD align="center"><?=htmlspecialchars($row['content_no'])?></TD>
        <TD><?=$row['title']?></TD>
        <TD><?=nl2br($row['content'])?></TD>
        <TD><?=$writer=$row['id']?></TD>
        <TD><?=$row['write_dt']?></TD>
        <TD><?=$row['view_cnt']?></TD>
    </TR>
<?php 
} 
?>
</TBODY>
</TABLE>

<footer>
<button onclick="location.href='list.php'">목록 보기</button>
<button onclick="location.href='insert.php'">글쓰기</button>
<?php
if($id==$writer)//글 작성자만 수정 및 삭제 가능
{?>
    <button onclick="location.href='updateForm.php'">수정</button>
    <button onclick="location.href='delete.php'">삭제</button>
<?php
}
?>
</footer>