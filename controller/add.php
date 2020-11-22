<?php
	//接收表单数据，增加一个新的信息
	session_start();

	$username = $_POST['username'];
	$sex = $_POST['sexuality'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];

	$spname = $_POST['spname'];
	$unit = $_POST['unit'];
	$price = $_POST['price'];
	$nums = $_POST['nums'];

	$date = $_POST['date'];
	$print = $_POST['print'];
	$memo = $_POST['memo'];

	//$arr = array($username,$sex,$phone,$address);

	include '../model/Conn.php';
	
	$sql = "select username from clients where username = '".$username."'";
	$res = $mysqli->query($sql);
	$attr = $res->fetch_all();
	
	$exist = false;
	foreach ($attr as $key) {
		if($key[0] == $username){
			$exist=true;
			$res->close();
			$mysqli->close();
			echo $username;
			echo  "<br>" . "以上数据已存在 跳转到404";
			//header('location:../error.htm');//数据已存在，跳转到404
		}
	}
	
	if(!$exist){
		//$sql2 = "insert into clients values('再来一个', 'man', '5686', 'adress','2020-11-1', 'spname', '台', 33,5,77,'memo')";
		$sql2 = "insert into clients(username, sexuality, phone, address) values('$username' , '$sexuality'  ,  '$phone','$address')";
		//$sql2 = "insert into clients(username, sexuality) values('$username', '$sexuality')";
		
		//$sql2 = "insert into values('".$username."' , '.$sexuality.'  ,  '".$phone."','".$address."','".$date."','".$product."','".$unit."','.$price.','.$nums.','.$print.','".$memo."')";
		
		if ($mysqli->query($sql2) === TRUE) {
	    	echo $sql2."<br>"."新记录插入成功";
			$mysqli->close();
			header('location:../addclient.htm');
		} else {
	    	echo $sql2 . "错误出现 :" . $mysqli->error;
			$mysqli->close();
			//header('location:../fail.htm');
		}
	}
 ?>
  