<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>게시글</h1>
    <?php //DB conn 소스 불러오기
        require('db_conn.php') ?>
    <?php 
    //쿼리문 작성
    $query = "select * from show_view";
    //쿼리보내고 결과를 변수에 저장
    $result = mysqli_query($conn,$query);
    ?>
    <table border=1>
        <tr>
            <td>글번호</td><td>제목</td><td>작성자</td><td>작성일</td><td>조회수</td>
        </tr>
    <?php
    
    while($row = mysqli_fetch_array($result)){
        print "<tr><td>" . $row['content_no'] . "</td>
            <td><a href='show'>" . $row['title'] . "</a></td>
            <td>" . $row['id'] . "</td>
            <td>" . $row['write_dt'] . "</td>
            <td>" . $row['view_cnt'] . "</td></tr>";
    }
    ?>
    </table>
    <button onclick="location.href='insert'">글쓰기</button>
</body>
</html>