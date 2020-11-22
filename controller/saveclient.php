<?php
    session_start();
    if (empty($_SESSION['uid'])){//判断用于存储用户名的Session会话变量是否为空
        if (! isset( $_SESSION ['uid'] )) {
            echo "请登录后使用！";
            Header("refresh:1;url='../index.html'");
        }
        exit;
    }else{
         $uid = $_SESSION['uid'] ;     //将会话变量赋给一个变量$myvalue
    }

	//接收表单数据，增加一个新的信息
	$username = @$_POST['username']?$_POST['username']:"刘建康";
	$sexuality = $_POST['sexuality']?"男":"女";
	$phone = @$_POST['phone']?$_POST['phone']:"0737-2982123";
	$address = @$_POST['address']?$_POST['address']:"沅江市莲子塘镇";
	$product = @$_POST['product']?$_POST['product']:"格力空调";
	$unit = @$_POST['unit']?$_POST['unit']:"台";
	$price = @$_POST['price']?$_POST['price']:"666";
	$nums = @$_POST['nums']?$_POST['nums']:"18";
	//$date = $_POST['date'];
	$print = $_POST['print']=="on"?5686:6666;   //133=预览，135=打印,5686=未开票，6666=已开票
	$memo = @$_POST['memo']?$_POST['memo']:"高富帅呢";

	$_SESSION['username'] = $username;
	//$_SESSION['sexuality']=$sexuality;
	$_SESSION['phone']=$phone;
	$_SESSION['address']=$address;
	//$_SESSION['product']=$product;
	//$_SESSION['unit']=$unit;
    //$_SESSION['date']=$date;
    //$_SESSION['price']=$price;
    //$_SESSION['nums']=$nums;
    //$_SESSION['print']=$print;
    //$_SESSION['memo']=$memo;

	//$arr = array($username,$sex,$phone,$address);

	include '../model/Conn.php';
	
	$sql2 = "insert into clients(username,sexuality,phone,address,product,unit,price,nums, print,memo)  values('$username', '$sexuality', '$phone', '$address', '$product', '$unit',  '$price',  '$nums', '$print', '$memo')";        	

	if ($mysqli->query($sql2) === TRUE) {
		echo $sql2."<br>"."新记录插入成功,5秒后自动返回！";
		$mysqli->close();
	} else {
		echo $sql2 . "错误出现 :" . $mysqli->error;
		$mysqli->close();
	}
	Header("refresh:1;url='input.php'");
	exit();

	//以下参考代码
	$sql = "select username from clients where username = '".$username."'";
	$res = $mysqli->query($sql);
	$attr = $res->fetch_all();
	
	foreach ($attr as $key) {
		if($key[0] == $username){
			echo  "注意：用户 \"$username\" 已存在!"."<br>" ;
			//exit();
			Header("refresh:1;url='input.php'");
		}
   }
   
 ?>
  