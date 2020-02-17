<html>
<head>
<title>유저 관리</title>
<body>
<a href="main.php"><button>홈으로</button></a>
<br>
<hr>
<?php
session_start();
require_once("db_conn.php");

$pdo = DB_conn();
$id = '';

if(isset($_SESSION['id']))
{
    $id = $_SESSION['id'];
}
else
{
    print "<script>alert('로그인 상태가 아닙니다 홈으로 돌아갑니다.');</script>";
    print "<script>location.href='main.php';</script>";
}


##관리자 인증 쿼리문
try
{
    $adm_sql = "SELECT is_admin from user_tb where id = :id";
    $adm_stmh = $pdo->prepare($adm_sql);
    $adm_stmh->bindValue(':id',$id,PDO::PARAM_STR);
    $adm_stmh->execute();
    $adm_cnt=$adm_stmh->rowCount();
}
catch(PDOException $e)
{
    print 'error : '.$e->getMessage();
}

##유저 조회 쿼리문(ADMIN은 제외)
try
{
    $usr_sql = "SELECT id from user_tb where id not in (:id)";
    $usr_stmh = $pdo->prepare($usr_sql);
    $usr_stmh->bindValue(':id',$id,PDO::PARAM_STR);
    $usr_stmh->execute();
    $usr_cnt=$usr_stmh->rowCount();
}
catch(PDOException $e)
{
    print 'error : '.$e->getMessage();
}


##관리자 인증 확인
if ($adm_cnt == 0)
{
    print "<script>alert('관리자 인증 결과 불러오기 실패. 홈으로 돌아갑니다.');</script>";
    print "<script>location.href='main.php';</script>";
}
else
{
    $adm_row = $adm_stmh->fetch(PDO::FETCH_ASSOC);
    if($adm_row['is_admin']==1)
    {
        print "<script>alert('관리자 권한이 없습니다. 홈으로 돌아갑니다.');</script>";
        print "<script>location.href='main.php';</script>";
    }
    else
    {
        if($usr_cnt == 0)
        {
            print "회원 정보가 없습니다.";
            print "<button><a href=main.php>홈으로</a></button>";
        }
        else
        {
            print "<table border=1>\n";
            print "<tr><th>아이디</th><th>삭제</th></tr>\n";
            while($usr_row=$usr_stmh->fetch(PDO::FETCH_ASSOC))
            {
                print "<tr>\n";
                print "<td>".htmlspecialchars($usr_row['id'])."</td>\n";
                print "<td><a href='usrDelete.php?action=delete&id=".$usr_row["id"]."'>삭제</a></td>";
                print "</tr>\n";
            }
            print "</table>";
        }
    }
}
?>
</body>
</html>