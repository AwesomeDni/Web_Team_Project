<?php
    $content_no = $_GET['id'];
    require_once('db_conn.php');
    $pdo = DB_conn();

    try{
        //쿼리문 작성
        $query = "select content_no,title from contents";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
    catch(PDOException $e){
        print 'err: '. $e->getMessage();
    }
?>