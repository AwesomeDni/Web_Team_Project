<html>
<head>
<title>유저 관리</title>
<style>
    a {text-decoration:none; }
</style>
</head>
<body>
<a href="main.php"><button>홈으로</button></a>
<br>
<hr>
<!--검색창-->
<form name="search_frm" action="userMgmt.php" method="get" autocomplete="off">
    검색 : <input type="text" name="search">
    <input type="submit" value="찾기">
</form>
<!--정렬창-->
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


##페이징 구현 쿼리문(전체 유저수 세기)
try
{   //쿼리문 작성
    if(isset($_GET['search'])) // 검색시
    {   $key = '%'.$_GET['search'].'%';
        $usr_cnt_sql = "SELECT count(user_no) as total FROM user_tb where id like :id";
        $usr_cnt_stmh=$pdo->prepare($usr_cnt_sql); //sql문을 인잭션으로 부터 보호하기위한 처리
        $usr_cnt_stmh->bindValue(':id',$key,PDO::PARAM_STR);
        $usr_cnt_stmh->execute();
    }
    else // 없으면
    {   $usr_cnt_sql = "SELECT count(user_no) as total FROM user_tb";
        $usr_cnt_stmh=$pdo->prepare($usr_cnt_sql); //sql문을 인잭션으로 부터 보호하기위한 처리
        $usr_cnt_stmh->execute();
    }
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();   }


##페이징 구현 변수
$usr_cnt_row=$usr_cnt_stmh->fetch(PDO::FETCH_ASSOC);
$total = $usr_cnt_row['total']; //전체유저수
$page = 1;
if(isset($_GET['page'])){
    $page = $_GET['page'];
}
$list = 10; //페이지당 데이터수
$block = 5;

$total_page = ceil($total/$list); //총 페이지 수
$total_block = ceil($total_page/$block); //총 블록 수
$nowBlock = ceil($page/$block); //현재블록

$s_page = ($nowBlock*$block)-($block-1); //블록에서 시작페이지
if($s_page <= 1){
    $s_page = 1;
}
$e_page=$nowBlock*$block; //블록에서 마지막페이지
if($total_page<=$e_page){
    $e_page=$total_page;
}

// 쿼리문에서 시작포인트부터 $list(페이지당 데이터수)만큼 읽어오면 한 페이지에 뿌릴 데이터만 갖고옴
$s_point = ($page-1)*$list; 

$prev_page = $page-1;//[이전] 버튼
if($prev_page<=0){
    $prev_page = 1;
}

$next_page = $page+1; // 다음페이지
if($next_page >= $total_page){
    $next_page = $total_page;
}


##유저 조회 쿼리문(ADMIN은 제외)
try
{   $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no desc limit $s_point,$list";
    $usr_cnt=0;
    if(isset($_GET['search'])){
        $key = '%'.$_GET['search'].'%';
        $usr_sql = "SELECT * from user_tb where is_admin=1 and id like '$key' order by id limit $s_point,$list";
    }
    else if(isset($_GET['sort'])){
        $sort = $_GET['sort'];
        switch($sort){
            case 'time_asc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no limit $s_point,$list";
            break;
            case 'id_asc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by id limit $s_point,$list";
            break;
            case 'id_desc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by id desc limit $s_point,$list";
            break;
            case 'email_asc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by email limit $s_point,$list";
            break;
            case 'email_desc':
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by email desc limit $s_point,$list";
            break;
            default:
            $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no desc limit $s_point,$list";
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
            print "<br>회원 정보가 없습니다.";
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
            print "<input type=submit name='del' value='일괄삭제'><br><br>";
            print "</table>\n";
            print "</form>\n<br>";
        }
    }
}


##페이징 넘버 노출

if(isset($_GET['search']))
{
    print "<a href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$prev_page."'>[이전]</a>";
    for ($p=$s_page; $p<=$e_page; $p++) 
    {
        print "<a href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$p."'> ".$p." </a>";
    }
    print " ... <a href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$next_page."'>[다음]</a>";
}
else if(isset($_GET['sort']))
{
    print "<a href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$prev_page."'>[이전]</a>";
    for ($p=$s_page; $p<=$e_page; $p++) 
    {
        print "<a href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$p."'> ".$p." </a>";
    }
    print " ... <a href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$next_page."'>[다음]</a>";
}
else
{
    print "<a href='".$_SERVER['PHP_SELF']."?page=".$prev_page."'>[이전]</a> ... ";
    for ($p=$s_page; $p<=$e_page; $p++) 
    {
        print "<a href='".$_SERVER['PHP_SELF']."?page=".$p."'> ".$p." </a>";
    }
    print " ... <a href='".$_SERVER['PHP_SELF']."?page=".$next_page."'>[다음]</a>";
}

?>

</body>
</html>