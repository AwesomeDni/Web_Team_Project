<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>게시글</h1>
    <?php 
    require_once('db_conn.php');
    $pdo = DB_conn();
    
    try
    {   //쿼리문 작성
        $query = "select content_no,title,id from show_view order by content_no desc";
        $stmh=$pdo->prepare($query); //sql문을 인잭션으로 부터 보호하기위한 처리
        $stmh->execute();
    }
    catch(PDOException $e)
    {   print 'err: '. $e->getMessage();   }
    ?>

    <table border=1>
        <tr align="center">
            <td>글번호</td><td>제목</td><td>작성자</td>
        </tr>
    <?php
    
    while($row=$stmh->fetch(PDO::FETCH_ASSOC))
    {   print "<tr>";
        print "<td align=center>" . $row['content_no'] . "</td>";
        print "<td width=500px><a href='show.php?id=". $row['content_no'] ."'>" . $row['title'] . "</a></td>";
        print "<td align=center>".$row['id']."</td>";
        print "</tr>";
    }
    ?>
    </table>
    <button onclick="location.href='insert.php'">글쓰기</button>
</body>
</html>