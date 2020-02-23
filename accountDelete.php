<?php
session_start();

require_once("db_conn.php");

$pdo = DB_conn();
$pw=crypt($_POST['pw'],crypt($_POST['pw'],'abc'));
//비밀번호를 'abc'라는 문자열을 salt값으로 암호화하고, 그 값을 salt값으로 다시 암호화
//회원가입시 이렇게 암호화해서 패스워드를 db에 넣었으므로 같은 방식으로 확인해야함
$email=$_POST['email'];
$check=0; //회원탈퇴(회원정보삭제) 쿼리문 실행 결과 확인하는 변수

##accountDelete.html에서 post로 가져온 delete변수. confirm()창에 대한 사용자의 응답(yes,no)에 따라 그 값이 달라짐 
if(isset($_POST['delete']) && $_POST['delete']=='NO'){
    print "<script>alert('회원탈퇴 취소')</script>";
    header('location: ./accountDelete.html');
}else if (isset($_POST['delete']) && $_POST['delete']=='default'){
    //confirm()에서 yes/no중 하나를 눌러야 여기에 올 수 있는데 그에 대한 정보가 오지 않았다는 뜻.
    print "<script>alert('로그인해주세요. 탈퇴 진행상황에 대한 정보 전달에 실패하였습니다. 관리자에게 문의 바랍니다.');</script>";
    header('location: ./accountDelete.html');
}else if (isset($_POST['delete']) && $_POST['delete']=='YES'){

#로그인상태확인
if(!isset($_SESSION['id'])){
    print "<script>alert('로그인해주세요.');</script>";
    header('location: ./main.php');
}else{
    $id=$_POST['id'];
}


#post로 가져온 아이디와 로그인아이디가 일치하는가?
if($id!=$_SESSION['id']){
    print "<script>alert('id does not match');</script>";
}else{
    //일치한다면 유저조회 쿼리문
    try{
        $sql="SELECT * FROM user_tb where id= :id";
        $stmh=$pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_STR);
        $stmh->execute();
        $count=$stmh->rowCount();
    }catch(PDOException $Exception){
        print 'error:'.$Exception->getMessage();
    } 
}

#id 맞을때 갖고온 정보로 email과 pw맞는지 확인. 일치하면 회원정보 삭제 진행
## admin 삭제를 시도하면 돌아감
## 유저조회결과가 없을때. 즉 해당하는 id와 같은 id의 유저정보 없다면 돌아감
if(!$count){
    print "<script>alert('해당 계정의 정보가 없습니다.');</script>";
    print "<script>location.href='accountDelete.html';</script>";
}else if($id == 'admin'){
    print "<script>alert('관리자 계정은 삭제할 수 없습니다.');</script>";
    print "<script>location.href='accountDelete.html';</script>";
}else{
    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
        if($row['pw']!=$pw || $row['email']!=$email){
            print "입력정보가 일치하지 않습니다.";
        }else{
            try{
                $pdo->beginTransaction();
                $sql="DELETE FROM user_tb WHERE id=:id";
                $stmh=$pdo->prepare($sql);
                $stmh->bindValue(':id',$id,PDO::PARAM_STR);
                $stmh->execute();
                $pdo->commit();
                $check=$stmh->rowCount();
            }catch(PDOException $Exception){
                $pdo->rollBack();
                print 'error : '.$Exception->getMessage();
            }
        }
    }
}

#회원탈퇴 성공하면 logout.php이용해 쿠키와 세션 지우고 메인으로
if(!$check){
    print "<script>alert('회원탈퇴에 실패하였습니다. 다시 시도해주세요.');</script>";
    print "<script>location.href='accountDelete.html';</script>";
}else{
    header('location: ./logout.php');
}

##$_POST['delete']가 세팅되어있지 않거나 그 값이 default,NO,YES중 그 무엇도 아닐때
}else{
    print "<script>alert('잘못된 접근입니다.');</script>";
    print "<script>location.href='accountDelete.html';</script>";
}