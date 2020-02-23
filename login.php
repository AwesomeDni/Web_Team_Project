<?php
session_start();

require_once("db_conn.php");

$pdo = DB_conn();
$id=$_POST['id'];
$pw=crypt($_POST['pw'],crypt($_POST['pw'],'abc'));
//회원가입시 암호화해서 넣은 패스워드 확인 위해 같은 방식으로 암호화해서 일치여부 확인

#공백입력시 오류표시
if ($id=="" || $pw == "")
{
    print "<script>alert('빈칸을 모두 채워주세요.');</script>";
    print "<script>location.href='login.html';</script>";
}
else if($id=='admin')
{
    $_SESSION['adm_act'] = 1;
    print "<script>alert('관리자 로그인 버튼이 활성화되었습니다.');</script>";
    print "<script>location.href='main.php';</script>";
}
else
{##공백 아니라면 해당 아이디로 유저 조회
    try{
        $sql="SELECT * FROM user_tb where id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_STR);
        $stmh->execute();
        $count=$stmh->rowCount();
    }catch(PDOException $Exception){
       print 'error:'.$Exception->getMessage();
    } 
    if($count<0){ ##유저조회 쿼리문 실행값을 담은 변수가 0,혹은 음수 = 쿼리문 실행 실패
        print "<script>alert('회원정보를 불러오는데 실패하였습니다.');</script>";
        print "<script>location.href='login.html';</script>";
    }else{
        $row=$stmh->fetch(PDO::FETCH_ASSOC);
        if (is_null($row['id'])) ##불러온 결과에서 id가 비어있음
        {
            print "<script>alert('입력한 아이디에 해당하는 회원 정보가 없습니다.');</script>";
            print "<script>location.href='login.html';</script>";
        }
        else
        {
            ##비밀번호와 id 모두 불러온 정보와 일치하면 session에 아이디 저장. 저장됐다면 홈으로
            if($row['pw']==$pw && $row['id']==$id){
                $_SESSION['id']=$id;
                if(isset($_SESSION['id'])){
                    header('location:./main.php');
                }else{
                    print "<script>alert('로그인 정보를 세션에 저장하는데에 실패하였습니다.');</script>";
                    print "<script>location.href='main.php';</script>";
                }
            ##id나 비밀번호가 맞지 않을때. 하지만 id일치여부는 is_null로 확인했으므로 
            ##여기에 내려올때는 비밀번호가 맞지 않을때 뿐이다.
            }else{
                print "<script>alert('잘못된 비밀번호 입니다.');</script>";
                print "<script>location.href='login.html';</script>";
            }
        }
    }
}
