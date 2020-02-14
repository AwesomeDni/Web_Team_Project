<?php
    session_start();
    require_once("db_conn.php");

    $pdo=DB_conn();
    $id=$_SESSION['id'];
?>
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
        else
        {

        }
    ?>
        <hr>

<!--카테고리추가,삭제 폼-->
    <form name="cateform" method="POST" action="categorymodify.php">
        <label>카테고리 관리</label>
        <br>
        <input type="text" name="category_nm" autocomplete="off" placeholder="추가하거나 삭제할 카테고리를 입력해주세요."><br>
        <input type="submit" name="createcate" value="카테고리 삭제"><br>
        <input type="submit" name="deletecate" value="카테고리 추가"><br>
        <button><a href="list.php">전체 게시글로</a></button>
    </form>
</body>
</html>