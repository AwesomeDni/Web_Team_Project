<?php
require_once('db_conn.php');
$dbo = DB_conn();
$cno =$_GET['content_no'];
?>
<!-- 댓글입력창!-->
<form action="replyinsert.php" method="post">
    <input type="hidden" name="content_no" value="<?=$cno?>">
    <textarea class="form-control" name="coment" rows="3"></textarea>
    <input class="button" type="submit" value="댓글쓰기">
</form>

<?php
//댓글 입력 결과 출력
try{
    $coment_sql = "select * from coment_view where content_no = $cno";
    $coment_stt=$dbo->prepare($coment_sql);
    $coment_stt->execute();
    
}
catch(PDOException $e){
    print 'err: '. $e->getMessage();
    $dbo->rollBack();
}
?>
<?php
$id=$_SESSION['id'];
while($coment_row=$coment_stt->fetch(PDO::FETCH_ASSOC))
{?>
<table class="table table-borderless table-sm">
    <tr>
        <td width="85%"><?=$writer=$coment_row['id']?> &emsp;|&emsp; <?=$coment_row['write_dt']?></td>
        <td>
            <?php
            if($id==$writer){//글 작성자만 수정 및 삭제 가능
            ?>
            <button onclick="location.href='replyupdate.php?coment_no=<?=$coment_row['coment_no']?>'">수정</button>
            <button onclick="location.href='replydelete.php?coment_no=<?=$coment_row['coment_no']?>'">삭제</button>
            <?php
            }
            # 관리자면 글 삭제 가능
            else if(isset($_SESSION['admin'])){
            ?>
            <button onclick="location.href='replydelete.php?coment_no=<?=$coment_row['coment_no']?>'">삭제</button>
            <?php
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><?=nl2br($coment_row['coment'])?></td>
    </tr>
</table>
<hr>
<?php
}
?>
