<?php
session_start();

require_once("db_conn.php");

$pdo = DB_conn();
$id=$_POST['id'];
$pw=$_POST['pw'];

#공백입력시 오류표시
if ($id=="" || $pw == "")
{
    print "<script>alert('빈칸을 모두 채워주세요.');</script>";
    print "<script>location.href='login.html';</script>";
}
else
{
    try{
        $sql="SELECT * FROM user_tb where id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_STR);
        $stmh->execute();
        $count=$stmh->rowCount();
    }catch(PDOException $Exception){
       print 'error:'.$Exception->getMessage();
    } 
    if($count<0){
        print "로그인 실패.";
    }else{
        $row=$stmh->fetch(PDO::FETCH_ASSOC);
        if (is_null($row['pw']) || is_null($row['pw']))
        {
            print "<script>alert('패스워드나 아이디가 틀렸습니다.');</script>";
            print "<script>location.href='login.html';</script>";
        }
        else
        {
            if($row['pw']==$pw && $row['id']==$id){
                $_SESSION['id']=$id;
                if(isset($_SESSION['id'])){
                    header('location:./main.php');
                }else{
                    print "세션 저장 실패";
                }
            }else{
                print "로그인 실패";
            }
        }
    }
}
