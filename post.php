<?php
session_start();
# insert.php로 부터 받은 정보 추출하여 변수에 저장
$title = $_POST['title'];
$content = $_POST['content'];
$category = $_POST['category'];

if($category=="")
{   print "<script>alert('카테고리를 선택해 주세요.');</script>";
    print "<script>history.back();</script>";
    return;
}

# 제목과 내용이 비어있는지 확인
if($title == '')
{   print "<script>alert('제목을 입력하세요.');</script>";
    print "<script>history.back();</script>";
    return;
}
if($content == '')
{   print "<script>alert('내용을 입력하세요.');</script>";
    print "<script>history.back();</script>";
    return;
}

$date=date('Y-m-d H:i:s');//글쓴 날짜를 위한 변수

# DB 연결
require('db_conn.php');
$pdo = DB_conn();

# 로그인한 id와 일치하는 user_no 추출 
try
{   $sql = "select user_no from user_tb where id= :id";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':id',$_SESSION['id']);
    $stmh->execute();
    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
        $user_no = $row['user_no'];
    }
}
catch(PDOException $e)
{   print 'err:'.$e->getMessage();  }

# DB에 form에서 입력한 정보 insert
try
{   //쿼리문 작성
    $query = "insert into contents_tb(title,content,user_no,category_no,write_dt) 
            values( :title, :content, :user_no, :category_no, :date)";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->bindValue(':title',$title);
    $stmh->bindValue(':content',$content);
    $stmh->bindValue(':user_no',$user_no);
    $stmh->bindValue(':category_no',$category);
    $stmh->bindValue(':date',$date);
    $stmh->execute();
}
catch(PDOException $e)
{   print 'err: '. $e->getMessage();
    $pdo->rollBack();
}

# 방금 작성한 글 표시
try
{   //자신이 작성한 글 중 가장 최근에 쓴 글의 번호 추출
    $query = "select content_no from contents_tb where user_no= :no order by write_dt desc limit 1";
    $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기 위한 처리
    $stmh->bindValue(':no',$user_no,PDO::PARAM_INT);
    $stmh->execute();
    $row=$stmh->fetch(PDO::FETCH_ASSOC);
    $cno=$row['content_no'];
}
catch(PDOException $e){
    print 'err: '. $e->getMessage();
    $pdo->rollBack();
}

print "<script>location.href='show.php?content_no=".$cno."';</script>";
?>
