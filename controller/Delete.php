<!--用于编辑主页面的某个客户数据 -->
<?php 
    session_start();
    if (empty($_SESSION['uid'])){//判断用于存储用户名的Session会话变量是否为空
        if (! isset( $_SESSION ['uid'] )) {
            echo "<center>";
            echo "请登录后使用！<br/>";
            echo "</center>";
                Header("refresh:1;url='../index.html'");
        }
        exit;
    }else{
         $uid = $_SESSION['uid'] ;     //将会话变量赋给一个变量$myvalue
    }

	include '../model/Conn.php';
	$id = $_GET['id'];
    $sql = "delete from clients where date = '".$id."'";
	$res = $mysqli->query($sql);
    if($res){
        echo "deleted ok!<br>";
    }else{
        echo $sql.'<br>' . "错误 :<br>" . $mysqli->error.'<br>';
    }
	//mysqli_close($result);
	$mysqli->close();
    Header("refresh:1;url='search.php'");
 ?>