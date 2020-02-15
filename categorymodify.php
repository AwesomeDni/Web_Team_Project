<?php
    session_start();
    require_once("db_conn.php");
    $pdo = DB_conn();
    $id=$_SESSION['id'];
    $category_nm = $_POST['category_nm'];


    //빈칸인경우 
    if($category_nm==NULL)
    {
        print "빈칸입니다. 저장하지 않습니다.";
        exit();
    }

    //카테고리이름 중복확인
    try
    {
        $sql="SELECT * FROM category_tb WHERE category_nm=:category_nm ";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
        $stmh->execute();
        $countCG=$stmh->rowCount();
        //$countCG=countCateGory
    }
    catch(PDOException $Exception)
    {
        print 'error:'.$Exception->getMessage();
    }

    //DB에 없는 제목이면 인서트
    if(!$countCG)
    {
        try
        {
            $pdo->beginTransaction();
            $sql="INSERT INTO category_tb(category_nm) VALUES(:category_nm)";
            $stmh=$pdo->prepare($sql);
            $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
            print "카테고리 입력완료";
            print "";
        }
        catch(PDOException $Exception)
        {
            $pdo->rollBack();
            print 'error:'.$Exception->getMessage();
        }
    }

    //DB에 있는 제목이면 딜리트 
    if($countCG)
    {
        try
        {
            $pdo->beginTransaction();
            $sql="DELETE FROM category_tb WHERE category_nm=:category_nm";
            $stmh=$pdo->prepare($sql);
            $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
            print "카테고리 삭제완료";
        }
        catch(PDOException $Exception)
        {
            print "error:".$Exception->getMessage();
        }
    }
?>