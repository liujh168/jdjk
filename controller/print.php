<?php
/*
	session_start();
	if (empty($_SESSION['uid'])){//判断用于存储用户名的Session会话变量是否为空
		if (! isset( $_SESSION ['uid'] )) {
			echo "请登录后使用！";
			Header("refresh:3;url='../index.html'");
		}
		exit;
	}else{
		 $uid = $_SESSION['uid'] ;     //将会话变量赋给一个变量$myvalue
	}
*/
   	$mysql_conf = array(
    'host'    =>'127.0.0.1:3306',
    'db'      =>'jdjk',
    'db_user'=>'root',
    'db_pwd' =>'ljH5686!',
    );
	
	$mysqli=new mysqli($mysql_conf['host'],$mysql_conf['db_user'],$mysql_conf['db_pwd']);
	if($mysqli->connect_errno){
	    exit;
	}
	if(!$mysqli->select_db($mysql_conf['db'])){
	    exit;
	}
	$mysqli->query("set names 'utf8';");//编码转换
    
    //查询是否存在打印命令，命令标志...
    $sql = "select * from clients where print = 133";
    $result = $mysqli->query($sql);
    $nums = mysqli_num_rows($result) ;
    if ($result && $nums > 0) {
        $myjson = '{"flag":"preview"}';
        //$sql = "UPDATE clients SET print=5686 WHERE print=133";
        $sql = "UPDATE clients SET print = 133133 WHERE print = 133";
        $mysqli->query($sql);
        $sql = "DELETE FROM `clients` WHERE `clients`.`PRINT` = '133133'";
        $mysqli->query($sql);
        $result->close();
        $mysqli->close();
        echo $myjson;   //返回命令或者数据 json串
        exit;
    }

    $sql = "select * from clients where print = 135";
    $result = $mysqli->query($sql);
    $nums = mysqli_num_rows($result) ;
    if ($result && $nums > 0) {
        $myjson = '{"flag":"print"}';
        //$sql = "UPDATE clients SET print=5686 WHERE print=135";			                //怎么不起作用？
        $sql = "UPDATE `clients` SET `print` = '135135' WHERE `print` = '135'";
        $mysqli->query($sql);
        $sql = "DELETE FROM `clients` WHERE `clients`.`PRINT` = '135135'";
        $mysqli->query($sql);
        $result->close();
        $mysqli->close();
        echo $myjson;   //返回命令或者数据 json串
        exit;
    }
    
    $sql = "select * from clients where print = 5686";
    $result = $mysqli->query($sql);

    //1、以下获取所有行的json字串
    $totals=1;  //数组下标从1开始，老是与C++混淆 :(
    $mya=array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
        $mya["mydata$totals"]=$row;  //key为数字时，vba存取较麻烦
        $totals=$totals+1;
    } 
    $myjson =json_encode($mya, JSON_UNESCAPED_UNICODE);
    echo $myjson;   //返回命令或者数据 json串

    //$sql = "UPDATE `clients` SET `print` = '6666' WHERE `print` = '5686'";
    //$mysqli->query($sql);
    
    $result->close();
    $mysqli->close();
    exit;

    //2、以下获取所有行的json字串
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $myjson =json_encode($row, JSON_UNESCAPED_UNICODE);
    echo $myjson;   //返回命令或者数据 json串
    $result->close();
    $mysqli->close();
    exit;
?>
