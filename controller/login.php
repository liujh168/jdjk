<?php
//验证登录
session_start();
//POST传值
$uid = $_POST['id'];
$pwd = $_POST['pwd'];
$flag = $_POST['flag'];
$login="登录";
$regist="注册";

if ($flag==$regist) {
    echo "注册";
    header('location:../regist.html');
}else if ($flag==$login){
    include "../model/Conn.php";
    
    //查询登录名和密码匹配
    $sql = "select * from login where id='".$uid."' and pwd = '".$pwd."'";
    $result = $mysqli->query($sql);
    $nums = mysqli_num_rows($result) ;
    $result->close();
    $mysqli->close();
    
    if ($result && $nums > 0) {
        $_SESSION['uid'] = $uid;
        header('location:input.php');
    } else {
        header('location:../index.html');
    }
} 
?>