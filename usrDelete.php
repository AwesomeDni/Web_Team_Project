
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


##관리자 확인
if($adm_cnt==0)
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
        ##userMgmt.php에서 받아온 삭제정보 확인
        if(isset($_POST['usr_del']))
        {
            $cnt = count($_POST['usr_del']);
            $del_cnt =0;
            ##유저 정보 삭제 쿼리문
            for($i=0; $i<$cnt; $i++)
            {
                try
                {
                    $pdo->beginTransaction();
                    $del_usr_no = $_POST['usr_del'][$i];
                    $sql = 'DELETE from user_tb where user_no=:user_no';
                    $stmh=$pdo->prepare($sql);
                    $stmh->bindValue(':user_no',$del_usr_no,PDO::PARAM_INT);
                    $stmh->execute();
                    $pdo->commit();
                    $del_cnt += $stmh->rowCount();
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();
                    print "error : ".$e->getMessage();
                }
            }
        }
        ##삭제정보 정상적으로 오지 않았을때
        else
        {
            print "<script>alert('잘못된 접근입니다. 홈으로 돌아갑니다.');</script>";
            print "<script>location.href='main.php';</script>";
        }

        ##유저 정보 삭제 결과 처리
        if($del_cnt == 0)
        {
            print "<script>alert('유저 정보 삭제에 실패하였습니다.');</script>";
            //print "<script>location.href='userMgmt.php';</script>";
        }
        else
        {
            print "<script>alert('$del_cnt 건의 유저정보를 삭제하였습니다');</script>";
            print "<script>location.href='userMgmt.php';</script>";
            
        }
    }
}

?>