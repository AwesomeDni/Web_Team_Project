<?php
    session_start();
    require_once("db_conn.php");
    $pdo=DB_conn();
    $check=0;
    $category = 0;
    $_SESSION['category'] = $category;
?>
<!--쿼리문-->
<?php
if(isset($_POST['content_no']))
{try {
        $sql="SELECT * FROM category_tb WHERE category_nm=:category_nm ";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
        $stmh->execute();
        $countCG=$stmh->rowCount();}
            //$countCG=countCateGory
    catch(PDOException $Exception){print 'error:'.$Exception->getMessage();}
}
?>
<?php
if(isset($_POST['content_no']))
{
    try{   $stmh=0;
        $sql = "DELETE FROM contents_tb WHERE content_no= :cno"; 
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
        $stmh->execute();
        $countCG = $stmh->rowCount();
        $pdo->commit();} 
    catch(PDOException $Exception){print "error:".$Exception->getMessage();}    
}
    if($stmh){
        echo "<script> alert('삭제 성공'); </script>";
    }
    else{
        echo "<script> alert('삭제 실패'); </script>";
    }
?>