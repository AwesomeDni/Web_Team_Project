<?php session_start();?>
<html>
    <head>
    <title>댓글 수정</title></head>
<body>

<?php
require_once('db_conn.php');
$dbo=DB_conn();
$cono = $_GET['coment_no'];

try{
    $sql="SELECT * FROM coments_tb WHERE coment_no=:cono";
    $stmh=$dbo->prepare($sql);
    $stmh->bindValue(':cono', $cono, PDO::PARAM_STR);
    $stmh->execute();
    $count=$stmh->rowcount();
}
catch(Excpetion $e){
    print "error:".$e->getMessage();
}
if($count<1){
    print "no have update date!!<br>";
}
else{
    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){?>
    <h1>댓글 수정하기</h1>
    <form name="form1" method="post" action="commentedit.php">
        <textarea name="coment" rows="8" cols="80"><?=htmlspecialchars($row['coment'])?></textarea><br>
        <input type="hidden" name="coment_no" value="<?=$row['coment_no']?>">
        <input type="submit" value="댓글수정">
    </form>
    <?php
    }
}
?>
</body>
</html>