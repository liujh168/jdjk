<?php
    /*
    **根据用户操作（点击打印或者打印预览按钮）保存相应命令标志的数据
    **vba查询时再根据查询到的命令标志决定返回数据或者打印命令。
    */
    session_start();
    if (empty($_SESSION['uid'])){//判断用于存储用户名的Session会话变量是否为空
        if (! isset( $_SESSION ['uid'] )) {
            echo "<center>";
            echo "请登录后使用！";
            echo "</center>";
            Header("refresh:1;url='../index.html'");
        }
        exit;
    }else{
         $uid = $_SESSION['uid'] ;     //将会话变量赋给一个变量$myvalue
    }

    $flag = $_GET['flag'];   
    if($flag=="preview"){
        $print=133;
    }else if($flag=="print"){
        $print=135;
    }else{
        $print=5686;
    }

	$username = "京东健康电器";
	$sexuality = "男";
	$phone = "07372982123";
	$address = "香铺仑";
	$product = "格力空调";
	$unit = "台";
    //$date ="2020-11-1";
    $price=0730;
    $nums=1000;
    //$print=0;  //借用此字段标志打印状态。5686=原始输入数据，133=预览，135=打印.
    $memo="备注信息";

  	include '../model/Conn.php';
	
	$sql2 = "insert into clients(username,sexuality,phone,address,product,unit,price,nums,print,memo) values('$username', '$sexuality', '$phone', '$address', '$product', '$unit', '$price', '$nums', '$print', '$memo')";
	
	if ($mysqli->query($sql2) === TRUE) {
    	echo $sql2."<br>"."<br>".$flag."<br>"."<br>"."打印命令已发出！5秒后自动返回-->";
		$mysqli->close();
		if($flag=="print"){
			echo '<br/><a href="../sample/preview.php">《查看网页版打印预览》</a>';	
			Header("refresh:1;url='input.php'");
		}elseif($flag=="preview"){
			Header("refresh:1;url='../sample/preview.php'");
		}
	} else {
    	echo $sql2 . "错误出现 :<br/>" . $mysqli->error;
		$mysqli->close();
		exit();
	}
	
?>
