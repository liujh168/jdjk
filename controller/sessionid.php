<?php
	session_start();
	if (empty($_SESSION['uid'])) { //判断用于存储用户名的Session会话变量是否为空
		//echo "session_uid为空！<br/>";
		if (!isset($_SESSION['uid'])) {
			//echo "session_uid not set!<br/>";
		} else {
			//echo "session_uid set!<br/>";
		}
		echo "<center>";
		echo "请登录后使用！<br/>";
		echo "</center>";
		Header("refresh:1; url='../index.html'");
		exit;
	} else {
		$uid = $_SESSION['uid'];     //将会话变量赋给一个变量$myvalue
		//echo "当前客户：$uid";
	}
?>