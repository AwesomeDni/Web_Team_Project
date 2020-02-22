<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web Project</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        header{   float: right;   }
    </style>
</head>
<body>
<header>
<?php
#세션 사용
session_start();
#로그인 체크
if(isset($_SESSION['id']))
{   $id = $_SESSION['id'];
    print $id.' 님';
?>
    <button onclick="location.href='logout.php'"> 로그아웃</button>
<?php
    $_SESSION['category_no'] = 1;

# 입력 폼
?>
</header>
<hr>
<div class="container">
<table class="table table-bordered">
    <thead>
        글쓰기
    </thead>
    <tbody>
        <form method="POST" action="post.php" autocomplete="off">
            <tr>
                <th>카테고리: </th>
                <td>
                <select name="category">
                <option value="" selected>--카테고리를 선택하세요--</option>
                <?php
                require_once('db_conn.php');
                $pdo=DB_conn();
                try
                {   $query = "SELECT * from category_tb";
                    $stmh = $pdo->prepare($query);
                    $stmh->execute();
                }
                catch(PDOException $e){ print 'err: '.$e->getMessage(); }
                while($row=$stmh->fetch(PDO::FETCH_ASSOC))
                {   $cg_no = $row['category_no'];
                    $cg_nm = $row['category_nm'];
                    print "<option value='$cg_no'>$cg_nm</option>";
                }
                ?>
	            </select>
                </td>
            </tr>
            <tr>
                <th>제목: </th>
                <td><input class="form-control" type="text" placeholder="제목을 입력하세요. " name="title"/></td>
            </tr>
            <tr>
                <th>내용: </th>
                <td><textarea class="form-control" cols="50" rows="25" placeholder="내용을 입력하세요. " name="content"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="button" value="목록보기" onclick="location.href='list.php'">
                    <input class="pull-right" type="submit" value="확인">
                </td>
            </tr>
        </form>
    </tbody>
</table>
</div>
</body>
</html>
<?php
}
# 비로그인 시 로그인 페이지로 이동
else
{   print "<script>alert('로그인 후에 작성 가능합니다.');</script>"; 
    print "<script>location.href='login.html';</script>";
}
?>