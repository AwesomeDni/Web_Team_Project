<html>
<head>
<title>유저 관리</title>
<body>
<a href="main.php"><button>홈으로</button></a>
<br>
<hr>
<form name="sort_frm" method="get" action="userMgmt.php">
    <input type="radio" name="sort" value="time_asc">처음 가입 
    <input type="radio" name="sort" value="time_desc">최근 가입 
    <input type="radio" name="sort" value="id_asc">아이디 순 
    <input type="radio" name="sort" value="id_desc">아이디 역순 
    <input type="radio" name="sort" value="email_asc">이메일 순 
    <input type="radio" name="sort" value="email_desc">이메일 역순 
    <button type="submit" >정렬</button>
    <a href="userMgmt.php"><button>정렬 초기화</button></a>
</form>
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
{   $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no desc";
    $usr_cnt=0;
    if(isset($_GET['sort'])){
        $sort = $_GET['sort'];
        switch($sort){
            case 'time_asc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no";
            break;
            case 'id_asc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by id";
            break;
            case 'id_desc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by id desc";
            break;
            case 'email_asc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by email";
            break;
            case 'email_desc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by email desc";
            break;
            default:
            $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no desc";
        break;
        }
    }
    $usr_stmh = $pdo->prepare($usr_sql);
    $usr_stmh->execute();
    $usr_cnt=$usr_stmh->rowCount();
}
catch(PDOException $e)
{
    print 'user view error : '.$e->getMessage();
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
            print "<form name='usr_del_frm' method='post' action='usrDelete.php'>";
            print "<table border=1 width='350' cellpadding='8'>\n";
            print "<tr><th>체크</th><th>아이디</th><th>email</th></tr>\n";
            while($usr_row=$usr_stmh->fetch(PDO::FETCH_ASSOC))
            {
                print "<tr>\n";
                print "<td align='center'><input type='checkbox' name='usr_del[]' value='".$usr_row['user_no']."'></td>";
                print "<td align='center'>".htmlspecialchars($usr_row['id'])."</td>\n";
                print "<td align='center'>".htmlspecialchars($usr_row['email'])."</td>";
                print "</tr>\n";
            }
            print "<input type=submit name='del' value='일괄삭제'";
            print "</table>";
            print "</form>";
        }
    }
}
?>
</body>
</html>