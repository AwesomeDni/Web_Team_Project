<?php
session_start();
require_once("db_conn.php");
$pdo=DB_conn();

if(isset($_GET['category_nm']) && $_GET['category_nm']!='')
{   $category_nm=$_GET['category_nm'];
    try 
    {   $sql="SELECT * FROM category_tb WHERE category_nm=:category_nm ";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
        $stmh->execute();
        $countCG=$stmh->rowCount();
    }
    catch(PDOException $Exception){print 'error:'.$Exception->getMessage();
    }
    if($countCG)
    {   try
        {   $sql = "DELETE FROM category_tb WHERE category_nm = :category_nm"; 
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(':category_nm',$category_nm);
            $stmh->execute();
            $countCG = $stmh->rowCount();
        } 
        catch(PDOException $Exception){print "error:".$Exception->getMessage();}
        if($countCG)
        {   echo "<script> alert('삭제 성공'); </script>";   }
        else
        {   echo "<script> alert('삭제 실패'); </script>";   }
    }
    else
    {   echo "<script> alert('없는 카테고리입니다.'); </script>";  }
}
print "<script>window.close();</script>"
?>
