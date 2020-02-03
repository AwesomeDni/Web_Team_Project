<?php
session_start();

require_once("db_conn.php");

$pdo = DB_conn();
$id=$_POST['id'];
$pw=$_POST['pw'];
$_SESSION['id'];

#id확인(일치시 해당 정보 갖고옴)
if($id!=$_SESSION['id']){
    print "<script>alert('id does not match');</script>";
}else{
    try{
        $sql="SELECT * FROM user_tb where id= :id";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_INT);
        $stmh->execute();
        $count=$stmh->rowCount();
    }catch(PDOException $Exception){
        print 'error:'.$Exception->getMessage();
    } 
}

if(!$count){
    print '일치정보 없음';
}else{
    while($stmh->fetch(PDO::FETCH_ASSOC)){
        if
    }
}

