<?php session_start(); //세션 사용 ?> 
<html>
<head>
    <title>글 수정</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        #button{    float:right;    }
    </style>
</head>
<body>
<?php
# DB연결 
require_once("db_conn.php"); //db_conn.php파일을 한번만 불러옴; 파일이 없으면 아래 코등 실행 x
$pdo = DB_conn();//db에 연결된 pdo객체 생성하여 변수 $pdo에 할당

# 변수 설정
## content_no
if(isset($_SESSION['content_no']))
{   $cno = $_SESSION['content_no'];  }
else
{   print "<script>alert('잘못된 접근입니다.');</script>";
    print "<script>history.back();</script>";
}

## id
if(isset($_SESSION['id']))
{   $id = $_SESSION['id'];  
    
    # 접속한 아이디와 글쓴 아이디 비교
    try
    {   $sql = "SELECT id from user_tb u, contents_tb c where c.content_no=$cno and c.user_no=u.user_no";
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
    }
    catch(PDOException $e)
    {   print "err: ".$e->getMessage();   }
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $DB_id = $row['id'];

    if($DB_id!=$id)
    {   print "<script>alert('잘못된 접근입니다.');</script>";
        print "<script>history.back();</script>";
    }
}
else
{   print "<script>alert('로그인 후에 이용 가능합니다.');</script>"; 
    print "<script>location.href='login.html';</script>";
}
    

# 문서번호에 해당하는 글 내용 가져오기
try
{   $sql = "SELECT * FROM contents_tb WHERE content_no= :cno"; 
    $stmh = $pdo->prepare($sql); //sql문을 인잭션으로 부터 보호하기위한 처리
    $stmh->bindValue(':cno',$cno,PDO::PARAM_INT);
    $stmh->execute();//sql문 실행
    $count = $stmh->rowCount();//sql문 실행 결과의 레코드 수 반환
} 
catch(PDOException $Exception)//에러 발생시 $Exception이라는 이름으로 PDO예외 처리 객체 생성
{   print "error:".$Exception->getMessage();   }//객체에 내장된 오류메세지 정보를 얻어오는 함수를 실행후 출력

# 수정 폼
if($count<1)
{   print "no have update data!<br>";   } //반환된 레코드가 없으면 출력
else //있으면 실행
{   $row=$stmh->fetch(PDO::FETCH_ASSOC); //객체가 실행한 쿼리의 결과값 반환
?>
<div class="container">
<table class="table table-bordered">
    <thead>
        글수정
    </thead>
    <tbody>
    <FORM name="form1" method="post" action="update.php">
        <tr>
            <th>제목: </th>
            <td><INPUT class="form-control" type="text" name="title" value="<?=htmlspecialchars($row['title'])?>"></td>
        </tr>
        <tr>
            <th>내용: </th>
            <td><textarea class="form-control" name="content" cols="50" rows="25"><?=htmlspecialchars($row['content'])?></textarea></td>
        </tr>
        <tr>
            <td colspan="2">
                <INPUT class="pull-right" type="submit" value="수정" id="button">
                </FORM>
                <button onclick="location.href='list.php'">목록으로</button>
            </td>
            
        </tr>
    </tbody>
</table>
<?php 
} 
?>
</body>
</html>