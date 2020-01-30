<?php session_start(); ?>
<?php
$_SESSION['content_no'] = $content_no = $_GET['id'];
require_once('db_conn.php');
$pdo = DB_conn();

try{
    //쿼리문 작성
    $query = "select * from show_view where content_no = :no";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->bindValue(':no',$content_no);
    $stmh->execute();
}
catch(PDOException $e){
    print 'err: '. $e->getMessage();
    $pdo->rollBack();
}
?>
<TABLE border="1" cellspacing="0" cellpadding="8">
<TBODY>
    <TR>
        <TH>No</TH>
        <TH width=100px>Title</TH>
        <TH width=300px>contetnt</TH>
        <TH width=50px>작성자</TH>
        <TH>작성일</TH>
        <TH width=50px>조회수</TH>
    </TR>
<?php
while($row=$stmh->fetch(PDO::FETCH_ASSOC))//PDO::FETCH_ASSOC 결과값을 한 행씩 읽어오는 메소드
{   ?>
    <TR>
        <TD align="center"><?=htmlspecialchars($row['content_no'])?></TD>
        <TD><?=htmlspecialchars($row['title'])?></TD>
        <TD><?=htmlspecialchars($row['content'])?></TD>
        <TD><?=htmlspecialchars($row['id'])?></TD>
        <TD><?=htmlspecialchars($row['write_dt'])?></TD>
        <TD><?=htmlspecialchars($row['view_cnt'])?></TD>
    </TR>
<?php 
} 
?>
</TBODY>
</TABLE>

<button onclick="location.href='list.php'">목록 보기</button>
<button onclick="location.href='updateForm.php'">수정</button>
<button onclick="location.href='delete.php'">삭제</button>