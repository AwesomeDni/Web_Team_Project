<?php
session_start();

require_once("db_conn.php");
$flag=0;
if(isset($_SESSION['flag'])) 
{   $flag=($_SESSION['flag']);}
$pdo = DB_conn();
$id=$_POST['id'];
$pw=$_POST['pw'];
$pw2=$_POST['pw2'];
#공백입력시 오류표시
if ($id=="" || $pw == "")
{
    print "<script>alert('빈칸을 모두 채워주세요.');</script>";
    print "<script>location.href='login_admin.html';</script>";
}
else
{
    try
    {   $sql="SELECT * FROM user_tb where id = :id";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':id',$id,PDO::PARAM_STR);
        $stmh->execute();
        $cnt=$stmh->rowCount();
    }
    catch(PDOException $Exception){
       print 'error:'.$Exception->getMessage();
    }
    # 입력된 id가 없을 시
    if($cnt==0)
    {   $flag += 1;
        $_SESSION['flag'] = $flag;
        ?>
        <script>
            alert('입력 정보가 올바르지 않습니다.\n5회 오류시 로그인 불가');
            location.href='main.php';
        </script>
        <?php
    }
    while($row = $stmh->fetch(PDO::FETCH_ASSOC))
    {   $id_db = $row['id'];
        $pw_db = $row['pw'];
    }# 입력된 id나 pw, pw2가 올바르지 않을 시
    if($id!=$id_db || $pw!=$pw_db || $pw2!="webproject")
    {   $flag += 1;
        $_SESSION['flag'] = $flag;
        ?>
        <script>
            alert('입력 정보가 올바르지 않습니다.\n5회 오류시 로그인 불가');
            location.href='main.php';
        </script>
        <?php
    }# 모든 입력이 올바를 시
    elseif($id==$id_db && $pw==$pw_db && $pw2=="webproject")
    {   $_SESSION['id']=$id;
        if(isset($_SESSION['id']))
        {   $_SESSION['flag']=0;
            $_SESSION['admin']=1;
            print "<script>location.href='main.php';</script>";   
        }
        else
        {   ?>
            <script>
                alert('세션 저장 실패');
                location.href='main.php';
            </script>
            <?php
        }
    }
    else
    {   ?>
        <script>
            alert('알 수 없는 오류 발생');
            location.href='main.php';
        </script>
        <?php
    }    
}
?>
