<title>Web Project</title>
<?php session_start(); ?>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="show.css">
<style>
    .button
    {   float: right;   }
    .content, .reply
    {   width: 75%;   }
    .category
    {   position: absolute;   }
    textarea
    {   resize: none;}
    @font-face{font-family:'A타이틀고딕2'; src:url('A타이틀고딕2.woff');} 
    .category
    {   position: absolute; 
        font-family:'A타이틀고딕2'; }
    .paging
    {   position: relative;
        left: 40%;
    }
    @font-face{font-family:'A프로젝트'; src:url('A프로젝트.woff');}
    .list-group-item
    {font-family:'A프로젝트'; font-weight:400;}
    .list
    {font-family:'A타이틀고딕2';}
    @font-face{font-family:'A펜글씨B'; src:url('A펜글씨B.woff');}
    .list2
    {font-family:'A펜글씨B';}
</style>
<header>
<button id="main" onclick="location.href='main.php'">메인</button>
<?php
#로그인 체크
if(isset($_SESSION['id']))
{   $id = $_SESSION['id'];
    ?>
	<button class="header" onclick="location.href='logout.php'"> 로그아웃</button>
    <a href="mypage.php"><button class="header">마이페이지</button></a>
    <?php
    print '<div class="header">'.$id.' 님</div>';
}
else // 로그인 상태가 아니면 로그인 페이지로 이동
{   $id='';
?>
	<meta http-equiv='refresh' content='0, login.html'>
<?php
}
?>
</header>
<hr>

<!-- 카테고리 바-->
<div class='category'>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"></h3>
        </div>
        <ul class="list group">
            <a href="list.php?category=0"><li><b>전체글보기</b></li></a>
            <?php
            # DB연결
            include('db_conn.php');
            $pdo = DB_conn();
            try
            {   $query = "SELECT * from category_tb";
                $stmh = $pdo->prepare($query);
                $stmh->execute();
            }
            catch(PDOException $e){ print 'err: '.$e->getMessage(); }
            while($row=$stmh->fetch(PDO::FETCH_ASSOC))
            {   $cg_no = $row['category_no'];
                $cg_nm = $row['category_nm'];
                print "<a href='list.php?category=$cg_no'><li class='list-group-item'>$cg_nm</li></a>";
            }
            ?>
        </ul>
    </div>
</div>
<!-- 메인 글-->
<div class="list">
    <div class="container content">
    <?php
    #사용자가 요청한 문서 번호 획득
    $_SESSION['content_no'] = $content_no = $_GET['content_no'];
    $category = $_SESSION['category'];
    if(isset($_SESSION['category_nm'])){
        $category_nm =$_SESSION['category_nm'];
        print "<h3>$category_nm</h3>";
    }
    #이전,다음 게시글 보기
    try
    {   //쿼리문 작성
        if($category>0)
        {   $query = "SELECT max(content_no) max, min(content_no) min from contents_tb where category_no = $category";
            $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
            $stmh->execute();
        }
        else
        {   $query = "SELECT max(content_no) max, min(content_no) min from contents_tb";
            $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
            $stmh->execute();
        }
        
    }
    catch(PDOException $e)
    {   print 'err: '. $e->getMessage();
    $pdo->rollBack();
    }
    ##글번호의 최대,최소값을 구함
    while ($row=$stmh->fetch(PDO::FETCH_ASSOC)) {
        $max=$row['max'];
        $min=$row['min'];
    }
    ##이전 글번호를 구함
    try
    {   //쿼리문 작성
        if($category>0)
        {   $query = "SELECT max(content_no) forward from contents_tb where content_no < $content_no and category_no=$category";
            $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
            $stmh->execute();    
        }
        else
        {   $query = "SELECT max(content_no) forward from contents_tb where content_no < $content_no";
            $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
            $stmh->execute();
        }
        
    }
    catch(PDOException $e)
    {   print 'err: '. $e->getMessage();
    $pdo->rollBack();
    }
    while ($row=$stmh->fetch(PDO::FETCH_ASSOC)) {
        $forward=$row['forward'];
    }
    ##다음 글번호를 구함
    try
    {   //쿼리문 작성
        if($category>0)
        {   $query = "SELECT min(content_no) next_ from contents_tb where content_no > $content_no and category_no = $category";
            $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
            $stmh->execute();    
        }
        else
        {   $query = "SELECT min(content_no) next_ from contents_tb where content_no > $content_no";
            $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
            $stmh->execute();
        }
        
    }
    catch(PDOException $e)
    {   print 'err: '. $e->getMessage();
        $pdo->rollBack();
    }
    while ($row=$stmh->fetch(PDO::FETCH_ASSOC)) {
        $next=$row['next_'];
    }
    ##마지막 글에는 다음 페이지 표시 x
    if (!($max==($content_no))) {
        print "<button onclick='location.href=\"show.php?content_no=".($next)."\"'>다음 글 ▲</button>";
    }
    ##처음 글에는 이전 페이지 표시 x 
    if (!($min==($content_no))) {
        print "<button onclick='location.href=\"show.php?content_no=".($forward)."\"'>이전 글 ▼</button>";
    }

    #문서의 조회수
    $cnt_flag = 0;
    if(!isset($_COOKIE[$id.$content_no])) {//한 방문자가 새로고침으로 조회수를 올리는것 방지
        $cnt_flag += 1;
        setcookie($id.$content_no,$cnt_flag);
        try
        {   //조회수 증가 쿼리
            $query = "update contents_tb set view_cnt = view_cnt + 1 where content_no = :no";
            $stmh=$pdo->prepare($query);
            $stmh->bindValue(':no',$content_no);
            $stmh->execute();
        }
        catch(PDOException $e)
        {   print 'err: '. $e->getMessage();
        $pdo->rollBack();
        }
    }

    #DB에서 문서내용 가져오기
    try
    {   //쿼리문 작성
        $query = "select * from show_view where content_no = :no";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
        $stmh->bindValue(':no',$content_no);
        $stmh->execute();
    }
    catch(PDOException $e)
    {   print 'err: '. $e->getMessage();
        $pdo->rollBack();
    }
    ?>
    <TABLE class="table table-bordered">
    <TBODY>
    <?php
    #문서 내용 출력
    $writer='';//작성자 저장하기 위한 변수
    while($row=$stmh->fetch(PDO::FETCH_ASSOC))//PDO::FETCH_ASSOC 결과값을 한 행씩 읽어오는 메소드
    {?>
        <TR>
            <TD width="70%"><?=$row['title']?></TD>
            <TD><?=$row['category_nm']?></TD>
            <TD><?=$row['write_dt']?></TD>
        </TR>
        <TR>
            <TD colspan="3"><?=$writer=$row['id']?></TD>
        </TR>
        <TR>
            <TD colspan="3"><pre><?=htmlspecialchars($row['content'])?></pre></TD>
        </TR>
    <?php 
    }
    # url창에 문서번호를 없는 번호를 쳤을때
    if($writer=="") {
        print "<script>alert('잘못된 접근입니다.');</script>";
        print "<script>history.back();</script>";
        return;
    }
    ?>
    </TBODY>
    </TABLE>
    <footer>
    <button onclick="location.href='list.php'">목록 보기</button>
    <button class="button" onclick="location.href='insert.php'">글쓰기</button>
    <?php
    if($id==$writer)//글 작성자만 수정 및 삭제 가능
    {?>
        <button class="button" onclick="location.href='updateForm.php'">수정</button>
        <button class="button" onclick="location.href='delete.php'">삭제</button>
    <?php
    }
    # 관리자면 글 삭제 가능
    if(isset($_SESSION['admin']))
    {   if($writer=='admin'){}
        else{
    ?>
        <button class="button" onclick="location.href='delete.php'">삭제</button>
    <?php
        }
    }
    ?>
    </div>
</div>
<br><br>
<div class="list2">
    <div class="container reply">
        <?php require_once('reply.php'); ?>
    </div>
</div>
</footer>