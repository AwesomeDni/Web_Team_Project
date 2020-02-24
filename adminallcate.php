<?php
    session_start();
    require_once("db_conn.php");

    $pdo=DB_conn();
    $id=$_SESSION['id'];
    if(isset($_POST['category_nm']))
    {   $category_nm =  $_POST['category_nm'];  }

    $flag=1;
    
?>
<html>
    <head>
        <title>카테고리 관리</title>
        <meta http-equiv="Content-Type" content="text/html charset=utf8";>
        <link rel="stylesheet" href="css/bootstrap.css">
        <script src="http://code.jquery.com/jquery.js"></script>
        <style>
            li{list-style: none;}
            a:hover{color:black; text-decoration: none;}
            .user{
                float: left; border-right:1px solid black; 
                height: 100%; width: 20%;
                }
            .category{float: auto; 
                height: 100%; width: 100%;}
                @font-face{font-family:'A프로젝트'; src:url('A프로젝트.woff'); }
            body{font-family:'A프로젝트';}
        </style>
    </head>
    <body>
        <div class="user">
        <!--관리자표시-->
        <?php
            if($id=='admin'){
                print "<br>";
                print "당신은 관리자입니다.";
                print "<br>";
                print "카테고리 관리는 아래에서 해주세요.";
                print '<br>';
        ?>
                <button class="btn btn-secondary btn-sm" ><a href="list.php">전체 게시글로</a></button><br>
                <button class="btn btn-secondary btn-sm" ><a href="main.php">메인 화면으로</a></button>
        <?php
            }
            else{}
        ?>
        </div>
        <hr>

        <!--카테고리추가,삭제 폼-->
        <div class="category">
            <div class="inputform">
            <nav>
                <p class="form-control-static">카테고리 관리</p>
                <form name="cateform" method="POST" class="form-inline">
                    <div class="form-group">
                <br><hr>
                <input type="text" id="focusInput" name="category_nm" autocomplete="off" placeholder="카테고리 이름"><br>
                    </div>
                <input type="submit" class="btn btn-primary btn-sm" name="createcate" value="카테고리 추가" onclick="chk_category();"><br>
                <input type="submit" class="btn btn-danger btn-sm" name="deletecate" value="카테고리 삭제" onclick="chk_delete();"><br>
                <input type="hidden" name="flag" value="1";> 
                <hr>
            </form>
            </div>
            <script>
                function chk_category(){
                if(cateform.category_nm.value=='')
                    {   
                        alert ('카테고리이름을 입력해주세요');
                        cateform.category_nm.focus();
                    }
                }
                
                function chk_delete(){
                    cateform.flag=0;
                    if(cateform.category_nm.value=='')
                    {   
                        alert ('카테고리이름을 입력해주세요');
                        cateform.category_nm.focus();
                    }
                    else{
                        var answer;
                        answer=confirm('정말로 삭제하시겠습니까? 원하시는 경우 확인을 눌러주세요.');
                        if(answer){
                            var cate=cateform.category_nm.value;
                            window.open('deletecate.php?category_nm='+cate,'삭제창','height=400px, width=800px','삭제완료');
                            cateform.category_nm.value=null;
                        }
                    }
                }
                //쿼리문
                <?php
                if(isset($_POST['flag']))
                {   $flag=$_POST['flag'];   }
                
                if($flag)
                {   try
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
                //DB에 없거나 빈칸이 없으면 인서트
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
                }
                ?>
        </script>
            </nav>
            <div>
                <?php print "현재 존재하는 카테고리는 아래와 같습니다.";?>  
                <table class="table">
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
                </table>
            </div>
        </div>
    </body>
</html>
