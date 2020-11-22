<?php
//POST传值
$username = $_POST['username'];
$password = $_POST['password'];

include "../model/Conn.php";

$sql2 = "insert into login(id, pwd) values('$username' , '$password')";
if ($mysqli->query($sql2) === TRUE) {
    session_start();
    $_SESSION['uid'] = $username;
    $mysqli->close();
    echo "<center>";
	//echo $sql2."<br>";
	echo "注册成功！";
	echo $username;
    //echo '<a href="input.php?id=' . $username . '">《欢迎使用》</a>';
    //echo '<a href="input.php?id=' . $username . '">《欢迎使用》</a>';
    header('location:../index.html');
    echo "</center>";
} else {
    $mysqli->close();
	echo $sql2 . "错误出现 :" . $mysqli->error;
    header('location:../index.html');
}

?>