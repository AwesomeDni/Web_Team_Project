<?php
## C:\xampp\php\pear 에 저장해야 reqire()로 불러올 수 있음
    //mysql 접속 계정 정보 설정
function DB_conn()
{
    $db_type="mysql";
    $db_host="localhost";
    $db_name="web_project";
    $db_user="root";
    $db_pass="password";

    $dsn="$db_type:host=$db_host;dbname=$db_name;charset=utf8";

    try
    {   $pdo=new PDO($dsn,$db_user,$db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    }
    catch(PDOException $Exception)
    {   die('error:'.$Exception->getMessage());    } 
    
    return $pdo; 
}
    
?>