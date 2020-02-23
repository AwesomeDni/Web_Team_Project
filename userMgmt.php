<html>
<head>
    <title>유저 관리</title>
    <style>
        a {text-decoration:none; }
        td {text-align:center;}
    </style>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<div class="container">
<a href="main.php"><button class="btn btn-primary">홈으로</button></a>
<!--검색창-->
<div class = 'float-right'>
<form name="search_frm" action="userMgmt.php" method="get" autocomplete="off">
    <div class="form-group row">
    <label for="search">ID 검색&nbsp;&nbsp;&nbsp;</label>
    <div class="col-xs-2">
    <input type="text" class="form-control form-control-sm" name="search">
    </div>
    &nbsp;&nbsp;<button type="submit" class="btn btn-secondary btn-sm">찾기</button>
    </div>
</form>
</div>
<br>
<hr>
<!--정렬창-->

<form name="sort_frm" method="get" action="userMgmt.php">
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="sort" value="time_asc">
    <label class="form-check-label">오래된 가입 순</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="sort" value="time_desc">
    <label class="form-check-label">최근 가입 순</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="sort" value="id_asc">
    <label class="form-check-label">아이디 순</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="sort" value="id_desc">
    <label class="form-check-label">아이디 역순</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="sort" value="email_asc">
    <label class="form-check-label">이메일 순 </label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="sort" value="email_desc">
    <label class="form-check-label">이메일 역순 </label>
</div>
    <button type="submit" class="btn btn-secondary btn-sm">정렬</button>
    <a href="userMgmt.php"><button class="btn btn-secondary btn-sm">정렬 초기화</button></a>
</form>
<?php
session_start();
require_once("db_conn.php");

$pdo = DB_conn();
$id = '';

#로그인확인
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
        $usr_cnt_stmh=$pdo->prepare($usr_cnt_sql); 
        $usr_cnt_stmh->bindValue(':id',$key,PDO::PARAM_STR);
        $usr_cnt_stmh->execute();
    }
    else // 검색 안했을 시
    {   $usr_cnt_sql = "SELECT count(user_no) as total FROM user_tb";
        $usr_cnt_stmh=$pdo->prepare($usr_cnt_sql);
        $usr_cnt_stmh->execute();
    }
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();   }


##페이징 구현 변수
$usr_cnt_row=$usr_cnt_stmh->fetch(PDO::FETCH_ASSOC);
$total = $usr_cnt_row['total']; //전체유저수
$page = 1; //현재페이지 넣는 변수. default 1
if(isset($_GET['page'])){
    $page = $_GET['page'];
}
$list = 10; //페이지당 데이터수
$block = 5; //한 블록당 페이지 수

$total_page = ceil($total/$list); 
//총 페이지 수 = 전체유저수를 페이지당 데이터수로 나누면 나옴(소수점은 의미 없 = 반올림)
$total_block = ceil($total_page/$block); //총 블록 수 = 총 페이지수 / 한블록당 페이지수
$nowBlock = ceil($page/$block); //현재블록 =현재페이지/한 블록당 페이지수 ->반올림

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

$prev_page = $page-1;//[prev] 버튼(이전페이지)
if($prev_page<=0){ 
    $prev_page = 1;
} //이전페이지가 0보다 작다면 1 할당

$next_page = $page+1; // 다음페이지 [next]버튼
if($next_page >= $total_page){
    $next_page = $total_page;
}//다음페이지가 총 페이지수보다 크다면 총페이지수 할당


##유저 조회 쿼리문(ADMIN은 제외)
##검색키워드, 정렬 등의 조건에 따라 쿼리문 달라짐
## limit $s_point,$list 라는 sql문 구절은 한 페이지에 해당하는 데이터만 갖고오기 위해서 있음
try
{   $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no desc limit $s_point,$list";
    //기본쿼리문, 아무 조건 없음(최근가입순이 기본정렬임)
    $usr_cnt=0;
    if(isset($_GET['search'])){ //검색키워드 있음
        $key = '%'.$_GET['search'].'%';
        $usr_sql = "SELECT * from user_tb where is_admin=1 and id like '$key' order by id limit $s_point,$list";
    }
    else if(isset($_GET['sort'])){
        $sort = $_GET['sort'];
        switch($sort){
            case 'time_asc': //오래된 가입 순
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by user_no limit $s_point,$list";
            break;
            case 'id_asc': //아이디순
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by id limit $s_point,$list";
            break;
            case 'id_desc'://아이디 역순
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by id desc limit $s_point,$list";
            break;
            case 'email_asc'://이메일순
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by email limit $s_point,$list";
            break;
            case 'email_desc'://이메일 역순
                $usr_sql = "SELECT * from user_tb where is_admin=1 order by email desc limit $s_point,$list";
            break;
            default://조건 없음. 위의 기본쿼리문과 동일
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


##관리자 인증 확인(쿼리문 실행결과 유무로 확인)
if ($adm_cnt == 0)
{
    print "<script>alert('관리자 인증 결과 불러오기 실패. 홈으로 돌아갑니다.');</script>";
    print "<script>location.href='main.php';</script>";
}
else
{
    ##관리자 인증용 쿼리문 결과에서 is_admin컬럼 통해 권한 확인
    $adm_row = $adm_stmh->fetch(PDO::FETCH_ASSOC);
    if($adm_row['is_admin']==1)
    {
        print "<script>alert('관리자 권한이 없습니다. 홈으로 돌아갑니다.');</script>";
        print "<script>location.href='main.php';</script>";
    }
    else
    {
        ##유저조회 쿼리문 결과 유무 확인. 결과 있다면 fetch통해 한줄씩 display
        if($usr_cnt == 0)
        {
            print "<br>회원 정보가 없습니다.<br>\n";
            print "<button class='btn btn-light'><a href=main.php>홈으로</a></button>";
        }
        else
        {
            print "<form name='usr_del_frm' method='post' action='usrDelete.php'>";
            print "<table class='table table-striped'>\n";
            print "<tr><th class='text-center'>체크</th><th class='text-center'>아이디</th><th class='text-center'>email</th></tr>\n";
            while($usr_row=$usr_stmh->fetch(PDO::FETCH_ASSOC))
            {
                print "<tr>\n";
                print "<td><input type='checkbox' name='usr_del[]' value='".$usr_row['user_no']."'></td>";
                //관리자가 체크박스 통해 삭제 가능하도록 함
                print "<td>".htmlspecialchars($usr_row['id'])."</td>\n";
                //문자열에서 특정한 특수 문자를 HTML 엔티티로 변환한다.악성 사용자로 부터 XSS 공격 방지
                //XSS(Cross Site Scripting)
                print "<td>".htmlspecialchars($usr_row['email'])."</td>";
                print "</tr>\n";
            }
            print "</table>\n";
            print "<button type=submit class = 'btn btn-secondary float-right' name='del'>일괄삭제</button>";
            print "</form>\n<br>";
        }
    }
}


##페이징 넘버 노출
##해당페이지에서 get변수 있는 곳으로 가는 링크들 (조건 따라 가지고가는 get변수들 달라짐)
print "<div aria-label='Page navigation example'>\n";
if(isset($_GET['search'])) //서치키워드 있을때
{
    print "<ul class='pagination justify-content-center'>\n";
    print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$prev_page."'>Previous</a></li>\n";
    for ($p=$s_page; $p<=$e_page; $p++) 
    {
        if($p !=$page){
            print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$p."'> ".$p." </a></li>\n";
        }else{
            print "<li class='page-item active'><a class='page-link' href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$p."'> ".$p." </a></li>\n";
        }
        
    }
    print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?search=".$_GET['search']."&page=".$next_page."'>Next</a></li>\n";
    print "</ul>";
}
else if(isset($_GET['sort'])) //정렬조건 있을때
{
    print "<ul class='pagination justify-content-center'>\n";
    print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$prev_page."'>Previous</a></li>\n";
    for ($p=$s_page; $p<=$e_page; $p++) 
    {
        if($p != $page){
            print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$p."'> ".$p." </a></li>";
        }else{
            print "<li class='page-item active'><a class='page-link' href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$p."'> ".$p." </a></li>";
        }
        
    }
    print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?sort=".$_GET['sort']."&page=".$next_page."'>Next</a></li>\n";
    print "</ul>";
}
else //아무 조건 없을떄
{
    print "<ul class='pagination justify-content-center'>\n";
    print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?page=".$prev_page."'>Previous</a></li>\n";
    for ($p=$s_page; $p<=$e_page; $p++) 
    {
        if($p != $page){
            print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?page=".$p."'> ".$p." </a></li>\n";
        }else{
            print "<li class='page-item active'><a class='page-link' href='".$_SERVER['PHP_SELF']."?page=".$p."'> ".$p." </a></li>\n";
        }
        
    }
    print "<li class='page-item'><a class='page-link' href='".$_SERVER['PHP_SELF']."?page=".$next_page."'>Next</a></a></li>";
    print "</ul>";
}
print "</div>"

?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
</div>
</body>
</html>