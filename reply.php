<?php
session_start();
require_once('db_conn.php');
$dbo = DB_conn();
$cno =$_GET['content_no'];

?>
<!DOCTYPE html>
<!-- 댓글입력창!-->
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>
<form action="replyinsert.php" method="post">
<input type="hidden" name="content_no" value="<?=$cno?>">
<textarea name="coment" rows="8" cols="80"></textarea>
<input type="submit" value="댓글쓰기">
</form>
</body>
</html>

<?php
//댓글 입력 결과 출력
try{
    $coment_sql = "select * from coments_tb where content_no = $cno";
    $coment_stt=$dbo->prepare($coment_sql);
    $coment_stt->execute();
    
}
catch(PDOException $e){
    print 'err: '. $e->getMessage();
    $dbo->rollBack();
}
while($coment_row=$coment_stt->fetch(PDO::FETCH_ASSOC))
    {
        echo "<table>
            <tr>
                <td>작성자</td>
                <td>{$coment_row['user_no']}</td>
            </tr>
            <tr>
                <td>내용</td>
                <td>{$coment_row['coment']}</td>
            </tr>
            <tr>
                <td>날짜</td>
                <td>{$coment_row['write_dt']}</td>
            </tr>
            </table>";
        
        //$coment_row['coment_no'];
        //$coment_row['coment'];
        //$coment_row['user_no'];
        //$coment_row['content_no'];
        //$coment_row['write_dt'];
    }
?>