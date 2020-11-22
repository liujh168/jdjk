<!--用来显示查询结果-->
<!DOCTYPE html>
<html>
<head>
	<title>客户查询详情页</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/login.css"/>
</head>
<body style="background-color:lightblue">
    <center>
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
    
		$username = @$_GET['id']?$_GET['id']:"";
		$phone = @$_GET['phone']?$_GET['phone']:"";

		include "../model/Conn.php";

        $sql = "select * from clients where username like '%$username%' and phone like '%$phone%'";
		$res = $mysqli->query($sql);
		$attr = $res->fetch_all();
		$res->close();
		$mysqli->close();
	 ?>
    
	<h2 align="center">您找到以下客户</h2>
	<table border="1px" align="center" cellpadding="0" cellspacing="0" style="border-color: red ;align-content: center;">
    		<?php
    			echo "<tr><th>"."客户名称"."</th><th>"."性别"."</th><th>"."电话号码"."</th><th>"."家庭住址"."</th><th>"."日期时间"."</th><th>"."商品名称"."</th><th>"."单位"."</th><th>"."单价"."</th><th>"."数量"."</th><th>"."是否开票"."</th><th>"."备注"."</th>";

    			foreach ($attr as $key ) {
        	       echo '<tr>';
        	       echo '<td>' . $key[0] . '</td>';
        	       echo '<td>' . $key[1] . '</td>';
        	       echo '<td>' . $key[2] . '</td>';
        	       echo '<td>' . $key[3] . '</td>';
				   //echo '<td>' . date('Y-m-d H:i:s', $key[4]) . '</td>';
				   echo '<td>' . $key[4] . '</td>';
				   echo '<td>' . $key[5] . '</td>';
        	       echo '<td>' . $key[6] . '</td>';
        	       echo '<td>' . $key[7] . '</td>';
        	       echo '<td>' . $key[8] . '</td>';
        	       echo '<td>' . $key[9] . '</td>';
        	       echo '<td>' . $key[10] . '</td>';
        	       
        	       echo '<td><a href="edit.php?id='.$key[4].'">编辑用户</a></td>';
        	       echo '<td><a href="Delete.php?id='.$key[4].'">删除用户</a></td>';
        	       echo '</tr>';
        	   }
		  ?>
	</table>

    </br>
	<form method="get">
			寻找:<input name="id" type="text" placeholder="输入名称查找客户">&nbsp;
			寻找:<input name="phone" type="text" placeholder="输入电话号码查找客户">&nbsp;
				<input type="submit" name="" value="开始查询">
	</form>
   
	<a href="input.php">《返回客户信息输入界面》</a>	
	</center>
</body>
</html>