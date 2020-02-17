<?php
    session_start();
    require_once("db_conn.php");

    $pdo=DB_conn();
    $id=$_SESSION['id'];
    $category = 0;
    $_SESSION['category'] = $category;
    $category_nm =  $_POST['category_nm'];
?>
<html>
    <head>
        <title>mypage</title>
        <meta http-equiv="Content-Type" content="text/html charset=utf8";>
        <link rel="stylesheet" href="mypage.css">
    </head>
    <body>
        <!--관리자표시-->
        <?php
            if($id=='admin'){
                print "<br>";
                print "당신은 관리자입니다.";
                print "<br>";
            print "카테고리 관리는 아래에서 해주세요.";
                print '<br>';
            }
            else{}
        ?>
        <hr>

        <!--카테고리추가,삭제 폼-->
        <nav>
        <form name="cateform" method="POST">
            <label>카테고리 관리</label>
            <hr>
            <input type="text" name="category_nm" autocomplete="off" placeholder="카테고리 이름을 입력하세요"><br>
            <input type="submit" name="createcate" value="카테고리 추가" onclick="chk_category();"><br>
            <input type="submit" name="deletecate" value="카테고리 삭제" onclick="chk_delete();"><br>
            <hr>
            <button><a href="list.php">전체 게시글로</a></button><br>
            <button><a href="main.php">메인 화면으로</a></button>
        </form>
        <script>
            function chk_category(){
            if(cateform.category_nm.value=='')
                {   
                    alert ('카테고리이름을 입력해주세요');
                    cateform.category_nm.focus();
                }
            }
            
            function chk_delete(){

                if(cateform.category_nm.value=='')
                {   
                    alert ('카테고리이름을 입력해주세요');
                    cateform.category_nm.focus();
                }
                else{
                    var answer;
                    answer=confirm('정말로 삭제하시겠습니까? 원하시는 경우 확인을 눌러주세요.');
                    if(answer=true){
                        window.open('deletecate.php','삭제창','height=400, width=400','삭제완료');
                    }
                    return false;
                }
            }
            //쿼리문
            <?php
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
            ?>
            //DB에 없거나 빈칸이 없으면 인서트
            <?php
                if(!$countCG && $category_nm!=NULL)
                {
                    try
                    {
                        $pdo->beginTransaction();
                        $sql="INSERT INTO category_tb(category_nm) VALUES(:category_nm)";
                        $stmh=$pdo->prepare($sql);
                        $stmh->bindValue(':category_nm',$category_nm,PDO::PARAM_STR);
                        $stmh->execute();
                        $pdo->commit();
            ?>
                        alert('카테고리 입력완료');
                        cateform.category_nm.focus();
            <?php
                    }
                    catch(PDOException $Exception)
                    {
                        $pdo->rollBack();
                        print 'error:'.$Exception->getMessage();
                    }
                }
            ?>
       </script>
        </nav>
        <section>
            <?php print "현재 존재하는 카테고리는 아래와 같습니다.";?>  
            <div class='category'>
            <ul>
                <?php
                try
                {   $query = "SELECT * from category_tb";
                    $stmh = $pdo->prepare($query);
                    $stmh->execute();
                }
                catch(PDOException $e){ print 'err: '.$e->getMessage(); }
                while($row=$stmh->fetch(PDO::FETCH_ASSOC))
                {   $cg_no = $row['category_no'];
                $cg_nm = $row['category_nm'];
                print "<a href='list.php?category=$cg_no'><li>$cg_nm</li></a>";
                }
                ?>
            </ul>
            </div>
        </section>
    </body>
</html>
