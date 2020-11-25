<!--用于编辑主页面的某个客户数据 -->
<?php 
	include "sessionid.php";

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