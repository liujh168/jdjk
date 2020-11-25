<?php
	include "sessionid.php";

    $flag = $_GET['flag'];   
    $print=($flag=="preview")?133:(($flag=="print")?135:5686);
	$text=($flag=="preview")?"打印预览":"打印";

  	include '../model/Conn.php';
	
	//	$sql2 = "insert into clients(username,sexuality,phone,address,product,unit,price,nums,print,memo) values('$username', '$sexuality', '$phone', '$address', '$product', '$unit', '$price', '$nums', '$print', '$memo')";
	
	$sql2 = "insert into clients(print) values('$print')";
	
	if ($mysqli->query($sql2) === false) {
    	echo $sql2 . "错误出现 :<br/>" . $mysqli->error;
		$mysqli->close();
		exit();
	}
	echo "<center>";
	echo $sql2."<br>"."<br>".$flag."<br>"."<br>".$text."命令已发出  1秒后自动返回 -->";
	echo "</center>";
	$mysqli->close();
	Header("refresh:1;url='../sample/preview.php'");
?>
