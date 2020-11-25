<?php
	$mysql_conf = array(
    'host'    =>'127.0.0.1:3306',
    'db'      =>'jdjk',
    'db_user'=>'root',
    'db_pwd' =>'ljH5686!',
    );
	$mysqli=new mysqli($mysql_conf['host'],$mysql_conf['db_user'],$mysql_conf['db_pwd']);
	if($mysqli->connect_errno){
		die("could not connect to the database:\n" . $mysqli->connect_errno);
	}
	$mysqli->query("set names 'utf8';");
	$select_db = $mysqli->select_db($mysql_conf['db']);
	if(!$select_db){
	    die("could not connect to the db:/n" . $mysql->error);
	}
?>
