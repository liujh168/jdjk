<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
	<!--以下添加，首尾共2处大块，其它零星值的显示在中间表格内-->
	<?php
	include "../controller/sessionid.php";

	//echo $_SESSION['username'];

	//初始化表单所有数据
	$username = @$_SESSION['username'] ? $_SESSION['username'] : "";
	$phone = @$_SESSION['phone'] ? $_SESSION['phone'] : "";
	$address = @$_SESSION['address'] ? $_SESSION['address'] : "";

	$totals = 1;  //数组下标从1开始，老是与C++混淆 :(
	$pdata = array();
	while (true) {
		$pdata["mydata$totals"]["username"] = "";
		$pdata["mydata$totals"]["sexuality"] = "";
		$pdata["mydata$totals"]["phone"] = "";
		$pdata["mydata$totals"]["address"] = "";
		$pdata["mydata$totals"]["product"] = "";
		$pdata["mydata$totals"]["memo"] = "";
		$pdata["mydata$totals"]["unit"] = "";
		//$pdata["mydata$totals"]["date"]="";
		$pdata["mydata$totals"]["print"] = 6666;
		$pdata["mydata$totals"]["price"] = 0;
		$pdata["mydata$totals"]["nums"] = 0;
		$pdata["mydata$totals"]["wan"] = 0;
		$pdata["mydata$totals"]["qian"] = 0;
		$pdata["mydata$totals"]["bai"] = 0;
		$pdata["mydata$totals"]["shi"] = 0;
		$pdata["mydata$totals"]["yuan"] = 0;
		$totals = $totals + 1;
		if ($totals == 6) break;			//只需要初始化5组数据
	}
	//echo var_dump($pdata);

	//include "../model/conn.php";			//包含不行？
	$mysql_conf = array(
		'host'    => '127.0.0.1:3306',
		'db'      => 'jdjk',
		'db_user' => 'root',
		'db_pwd' => 'ljH5686!',
	);
	$mysqli = new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
	if ($mysqli->connect_errno) {
		die("could not connect to the database:\n" . $mysqli->connect_errno);
	}
	$mysqli->query("set names 'utf8';");
	$select_db = $mysqli->select_db($mysql_conf['db']);
	if (!$select_db) {
		die("could not connect to the db:/n" . $mysql->error);
	}



	$sql = "select * from clients where username = \"$username\" and print = 5686 limit 5"; //还要限制是当天的
	$result = $mysqli->query($sql);
	echo $mysqli->error;

		$totals = 1;  //数组下标从1开始，老是与C++混淆 :(
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$pdata["mydata$totals"] = $row;  //key为数字时，vba存取较麻烦
			$totals = $totals + 1;
		}
		//echo $mysqli->error;
		//echo var_dump($pdata);

	
		if($totals==1){		//没有数据
			$username ="空着呢";
			$phone=="";
			$address ="";
			$totals_money="";
		}else{
			$username = $username==""? $pdata['mydata1']['username']:$username;
			$phone = $phone==""? $pdata['mydata1']['phone']:$phone;
			$address = $address==""? $pdata['mydata1']['address']:$address;	

			$totals_money = $pdata['mydata1']['price']*$pdata['mydata1']['nums'];
			$totals_money += $pdata['mydata2']['price']*$pdata['mydata2']['nums'];
			$totals_money += $pdata['mydata3']['price']*$pdata['mydata3']['nums'];
			$totals_money += $pdata['mydata4']['price']*$pdata['mydata4']['nums'];
			$totals_money += $pdata['mydata5']['price']*$pdata['mydata5']['nums'];	
		}
		
		//以上已获取全部可用于预览的数据, 还有个编号要求递增 7位数字。

		//查询是否存在打印命令，命令标志...
		$sql = "select * from clients where print = 135";
		$result = $mysqli->query($sql);
		$nums = mysqli_num_rows($result);
		$printnow = false;
		if ($result && $nums > 0) {
			$printnow = "true";		//控制onload事件中是否立即打印
			$sql = "DELETE FROM `clients` WHERE `clients`.`PRINT` = '135'";
			$mysqli->query($sql);
		}
		$result->close();
		$mysqli->close();
	?>



	<SCRIPT language=javascript>
		//关于打印的参考资料：http://edutechwiki.unige.ch/en/CSS_for_print_tutorial
		//http://lon.im/post/css-print.html
		//https://cdc.tencent.com/2014/08/19/print-被埋没的media-type/
		//https://stackoverflow.com/questions/21908/silent-printing-in-a-web-application

		window.onload = function() {
			var arr = "<?php echo $printnow; ?>"
			if (arr == "true") {
				alert("请准备好打印机，点击确定开始远程打印！");
				//doPrint();
			}
		}

		function doPrint() {
			//https://www.jb51.net/article/91476.htm //这是一个套打发票的示例  http://hiprint.io/demo/list/bill
			//https://www.jb51.net/article/33118.htm
			//https://www.kancloud.cn/yiqiaokeji/httpprinter
			bdhtml = window.document.body.innerHTML;
			sprnstr = "<!--startprint-->";
			eprnstr = "<!--endprint-->";
			prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
			prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
			window.document.body.innerHTML = prnhtml;
			window.print();
			window.document.body.innerHTML = bdhtml;
			window.location.reload();

			//var printHtml = document.getElementById("sample_26514").innerHTML;//这个元素的样式需要用内联方式，不然在新开打印对话框中没有样式
			//var printHtml = prnhtml;
			//var wind = window.open("",'newwindow', 'height=300, width=700, top=100, left=100, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
			//wind.document.body.innerHTML = printHtml;
			//wind.print()
		}

		function showResult(str) {
			if (str.length == 0) {
				document.getElementById("livesearch").innerHTML = "";
				document.getElementById("livesearch").style.border = "0px";
				return;
			}
			if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
				xmlhttp = new XMLHttpRequest();
			} else { // IE6, IE5 浏览器执行
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("livesearch").innerHTML = xmlhttp.responseText;
					document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
				}
			}
			xmlhttp.open("GET", "../controller/livesearch.php?q=" + str, true);
			xmlhttp.send();
		}

		function doPrintAgain(str) {
			var username = prompt("请输入要开票的客户名称！");
			if (username.length == 0) {
				alert("长度为0，没输入？");
				word="刘建康";
			}
			if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
				xmlhttp = new XMLHttpRequest();
			} else { // IE6, IE5 浏览器执行
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("livesearch").innerHTML = xmlhttp.responseText;
					document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
				}
			}
			xmlhttp.open("GET", "../controller/printagain.php?username=" + username, true);
			xmlhttp.send();
		}
	</SCRIPT>
	<meta http-equiv="refresh" content="8">
	<!--以上添加-->



	<meta http-equiv=Content-Type content="text/html; charset=utf8">
	<meta name=ProgId content=Excel.Sheet>
	<meta name=Generator content="Microsoft Excel 15">
	<link rel=File-List href="preview.files/filelist.xml">
	<!--[if !mso]>
<style>
v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
x\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style>
<![endif]-->
	<style id="preview_25367_Styles">
		<!--table
		{
			mso-displayed-decimal-separator: "\.";
			mso-displayed-thousand-separator: "\,";
		}

		.font525367 {
			color: windowtext;
			font-size: 9.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
		}

		.font625367 {
			color: windowtext;
			font-size: 18.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
		}

		.font725367 {
			color: windowtext;
			font-size: 24.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
		}

		.font825367 {
			color: windowtext;
			font-size: 16.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
		}

		.xl6325367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: right;
			vertical-align: middle;
			border-top: .5pt solid windowtext;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6425367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6525367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6625367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 10.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6725367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 10.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6825367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: "yyyy\0022年\0022m\0022月\0022d\0022日\0022\;\@";
			text-align: center;
			vertical-align: middle;
			border-top: none;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6925367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 11.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: left;
			vertical-align: bottom;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7025367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border-top: none;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7125367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border-top: .5pt solid windowtext;
			border-right: .5pt solid windowtext;
			border-bottom: none;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl7225367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border-top: none;
			border-right: .5pt solid windowtext;
			border-bottom: none;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl7325367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 12.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: left;
			vertical-align: middle;
			border-top: none;
			border-right: .5pt solid windowtext;
			border-bottom: none;
			border-left: .5pt solid windowtext;
			background: white;
			mso-pattern: black none;
			white-space: normal;
		}

		.xl7425367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 12.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: left;
			vertical-align: middle;
			border-top: none;
			border-right: .5pt solid windowtext;
			border-bottom: .5pt solid windowtext;
			border-left: .5pt solid windowtext;
			background: white;
			mso-pattern: black none;
			white-space: normal;
		}

		.xl7525367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 16.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: underline;
			text-underline-style: single;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border-top: .5pt solid windowtext;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7625367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 16.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: underline;
			text-underline-style: single;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			border-top: .5pt solid windowtext;
			border-right: .5pt solid windowtext;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7725367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: left;
			vertical-align: middle;
			border-top: .5pt solid windowtext;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7825367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: left;
			vertical-align: middle;
			border-top: .5pt solid windowtext;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7925367 {
			padding: 0px;
			mso-ignore: padding;
			color: red;
			font-size: 20.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: right;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8025367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: right;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8125367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 14.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: left;
			vertical-align: bottom;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8225367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 10.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: "General Date";
			text-align: center;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8325367 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 18.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-number-format: General;
			text-align: center;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		ruby {
			ruby-align: left;
		}

		rt {
			color: windowtext;
			font-size: 9.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
			mso-char-type: none;
		}
		-->
	</style>
</head>

<body>


	<!--startprint-->
	<style media="print">
		@page {
			size: portrait; // portrait纵向打印   landscape横向打印
			/* 去掉页眉页脚 */
			size: 210mm 290mm​;
			/* auto is the initial value */
			margin: 0mm auto;
		}
	</style>



	<!--[if !excel]>　　<![endif]-->
	<!--下列信息由 Microsoft Excel 的发布为网页向导生成。-->
	<!--如果同一条目从 Excel 中重新发布，则所有位于 DIV 标记之间的信息均将被替换。-->
	<!----------------------------->
	<!--“从 EXCEL 发布网页”向导开始-->
	<!----------------------------->

	<div id="preview_25367" align=center x:publishsource="Excel">

		<table border=0 cellpadding=0 cellspacing=0 width=620 class=xl6625367 style='border-collapse:collapse;table-layout:fixed;width:466pt'>
			<col class=xl6625367 width=43 style='mso-width-source:userset;mso-width-alt:
 1536;width:32pt'>
			<col class=xl6625367 width=54 style='mso-width-source:userset;mso-width-alt:
 1934;width:41pt'>
			<col class=xl6625367 width=43 span=2 style='mso-width-source:userset;
 mso-width-alt:1536;width:32pt'>
			<col class=xl6625367 width=26 style='mso-width-source:userset;mso-width-alt:
 938;width:20pt'>
			<col class=xl6625367 width=32 style='mso-width-source:userset;mso-width-alt:
 1137;width:24pt'>
			<col class=xl6625367 width=33 style='mso-width-source:userset;mso-width-alt:
 1166;width:25pt'>
			<col class=xl6625367 width=51 style='mso-width-source:userset;mso-width-alt:
 1820;width:38pt'>
			<col class=xl6625367 width=20 span=3 style='mso-width-source:userset;
 mso-width-alt:711;width:15pt'>
			<col class=xl6625367 width=22 style='mso-width-source:userset;mso-width-alt:
 796;width:17pt'>
			<col class=xl6625367 width=22 style='mso-width-source:userset;mso-width-alt:
 768;width:16pt'>
			<col class=xl6625367 width=5 style='mso-width-source:userset;mso-width-alt:
 170;width:4pt'>
			<col class=xl6625367 width=46 style='mso-width-source:userset;mso-width-alt:
 1649;width:35pt'>
			<col class=xl6625367 width=19 style='mso-width-source:userset;mso-width-alt:
 682;width:14pt'>
			<col class=xl6625367 width=22 style='mso-width-source:userset;mso-width-alt:
 796;width:17pt'>
			<col class=xl6625367 width=19 style='mso-width-source:userset;mso-width-alt:
 682;width:14pt'>
			<col class=xl6625367 width=26 style='mso-width-source:userset;mso-width-alt:
 910;width:19pt'>
			<col class=xl6625367 width=21 style='mso-width-source:userset;mso-width-alt:
 739;width:16pt'>
			<col class=xl6625367 width=33 style='mso-width-source:userset;mso-width-alt:
 1166;width:25pt'>
			<tr height=50 style='mso-height-source:userset;height:37.8pt'>
				<td colspan=2 height=50 width=97 style='height:37.8pt;width:73pt' align=left valign=top>
					<!--[if gte vml 1]><v:shapetype id="_x0000_t75" coordsize="21600,21600"
   o:spt="75" o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe" filled="f"
   stroked="f">
   <v:stroke joinstyle="miter"/>
   <v:formulas>
    <v:f eqn="if lineDrawn pixelLineWidth 0"/>
    <v:f eqn="sum @0 1 0"/>
    <v:f eqn="sum 0 0 @1"/>
    <v:f eqn="prod @2 1 2"/>
    <v:f eqn="prod @3 21600 pixelWidth"/>
    <v:f eqn="prod @3 21600 pixelHeight"/>
    <v:f eqn="sum @0 0 1"/>
    <v:f eqn="prod @6 1 2"/>
    <v:f eqn="prod @7 21600 pixelWidth"/>
    <v:f eqn="sum @8 21600 0"/>
    <v:f eqn="prod @7 21600 pixelHeight"/>
    <v:f eqn="sum @10 21600 0"/>
   </v:formulas>
   <v:path o:extrusionok="f" gradientshapeok="t" o:connecttype="rect"/>
   <o:lock v:ext="edit" aspectratio="t"/>
  </v:shapetype><v:shape id="图片_x0020_1" o:spid="_x0000_s3074" type="#_x0000_t75"
   style='position:absolute;margin-left:15.6pt;margin-top:0;width:63pt;
   height:45pt;z-index:2;visibility:visible' o:gfxdata="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
">
   <v:imagedata src="preview.files/preview_25367_image001.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><v:shape id="图片_x0020_5" o:spid="_x0000_s3076" type="#_x0000_t75"
   style='position:absolute;margin-left:15.6pt;margin-top:0;width:63pt;
   height:45pt;z-index:4;visibility:visible' o:gfxdata="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
DZtQ+0ZMcyKltvGprRvq73h5cPrC3KLG0VULmpMFsDAPKsO0itp8Ek8oAaIEtR8kphoVQWiCZrUd
xVY4FZIU9H3UZSMJvcKO/ea0wrN634ylh0WxCA+s/s2mGUoUYWINiVSkrSn46kvju1+d9HknlYyT
qMbcZhVYgAgmNXhHBuGJ6Pt7Jnb/cai8OKW2hM65f7qoR2BX6yIbNpbg0x/ig8OHalgV+BtUgwpm
y4XVxQnH3503nFxxz4cPg5KFk6uev+WgpFddaj8SYA1ghzQCucd64O9QDcvmmefkrYhU5G3b1JT6
02CkGtW71cz+I/mb1ekyJTvXKLJ9kBYK99MyPDza3dXT3trW1KD+pq+zo2N4cGhibFx9nlUpqErZ
Pzg9rQpvsLNHnTN7EVmrteJMSfsDZ0vQ9kab9GcVNKG2coajAwNDlZXVv//9c3feedd11337iiu2
btp46YZ16ymXXbr5y5ddftXWbTd85/ofbb9r185njpeWBUfTf5ZEk6pgneeAk+kNAuvcBhqNxDFV
KXYRSzsylb+PpwXhysZN6kbiuqh2U5D+5GQYLaKJt99+99prv3HKKZ/49Kc/QznttM99/vNnnH3m
WWedcSaFyvnnnrf2kjVf+sIXUcnOp572erwSviwqNTLSvwysFm+AQ6SLXKfUbzopgdqLUYAUdCAy
pcjV8fFJqVMwdTsmbZCWUb3Frqurq6CgYNeuXdu3b7/mmmu2bNly+WWXIfGtV1x57dXX3HTDjXfd
uf2/H33sjy+8WFJU3NHWLhskI5EIRK6U9A1OjhqM3LXQFWAPiJjU9tJM0pfS3z94+PDRe+6578Yb
v0s8ef75F1tb24lLqEH/VoH0oaSP1ATj+hcBQEhDKvYWbggGg0ODQ2rv2sgoR7URb2R0nLuCodBo
UP3Iwcgod9IbxCl/XFFNLKIGkRdHM6rUgf2qXEqDvmvWJU5hADbsYtJmOrMtdj5wUe5xOBzE6zM/
f+Zpp3729M/9xzVfu3rvu3uCI0FENjmOHsIiX4FI2b7FUzbTIlZTZG/t8JDSAWVoYJAiW/Eoog80
QS90RZ8QY1dDGo/Lw+LeoGSpYZ3bBpb2NMglYOpURPoiemObgDoQfQC7SgTSIpB7EGhzY9NfXvrz
r3f96sXnX8jPO4bISGnEctUeYA1uE9EPawwNqU21gwNqE7MRsYibIjuETenr6ZWKbHe2NDeqvAFq
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
">
   <v:imagedata src="preview.files/preview_25367_image001.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><![endif]-->
					<![if !vml]><span style='mso-ignore:vglayout;
  position:absolute;z-index:2;margin-left:21px;margin-top:0px;width:84px;
  height:60px'><img width=84 height=60 src="preview.files/preview_25367_image002.png" v:shapes="图片_x0020_1 图片_x0020_5"></span>
					<![endif]><span style='mso-ignore:vglayout2'>
						<table cellpadding=0 cellspacing=0>
							<tr>
								<td colspan=2 height=50 class=xl8225367 width=97 style='height:37.8pt;
    width:73pt'><a name="RANGE!A1:U13"></a></td>
							</tr>
						</table>
					</span></td>
				<td colspan=13 class=xl8325367 width=383 style='width:288pt'>
					<font class="font725367">京东家电专卖店</font>
					<font class="font625367"><span style='mso-spacerun:yes'>&nbsp; </span></font>
					<font class="font825367">商品销售单</font>
				</td>
				<td colspan=5 class=xl7925367 width=107 style='width:80pt'>0004858</td>
				<td class=xl6625367 width=33 style='width:25pt'></td>
			</tr>
			<tr height=45 style='mso-height-source:userset;height:33.6pt'>
				<td colspan=15 height=45 class=xl8125367 style='height:33.6pt'>沅江市三眼塘店
					JD29789<span style='mso-spacerun:yes'>&nbsp;&nbsp; </span>电话: 0737-2982123
					18907376948</td>
				<td colspan=5 class=xl8025367>店长 刘建康</td>
				<td class=xl6625367></td>
			</tr>
			<tr height=43 style='mso-height-source:userset;height:32.4pt'>
				<td height=43 class=xl6425367 style='height:32.4pt'>客户</td>
				<td class=xl6425367><?php echo $username ?></td>
				<td colspan=3 class=xl7025367><?php echo $address ?></td>
				<td colspan=4 class=xl7025367><?php echo $phone ?></td>
				<td colspan=4 class=xl7025367>销售日期：</td>
				<td class=xl6625367></td>
				<td colspan=6 class=xl6825367><?php  	echo date('Y年m月d日');
									//setlocale(LC_ALL,”chs”);
									//echo getdate()["year"]."年".getdate()["month"]."月".getdate()["mday"]."日" ;
									?></td>
				<td class=xl6625367></td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 rowspan=2 height=65 class=xl6525367 style='height:48.6pt'>商<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp; </span>品<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp; </span>名<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp; </span>称</td>
				<td rowspan=2 class=xl6525367 style='border-top:none'>数量</td>
				<td rowspan=2 class=xl6525367 style='border-top:none'>单位</td>
				<td rowspan=2 class=xl6525367 style='border-top:none'>单价</td>
				<td colspan=5 class=xl6525367 style='border-left:none'>金额</td>
				<td colspan=7 rowspan=2 class=xl6525367>备注</td>
				<td rowspan=8 height=275 class=xl6625367 width=33 style='mso-ignore:colspan-rowspan;
  height:207.0pt;width:25pt'>
					<!--[if gte vml 1]><v:shape id="图片_x0020_3"
   o:spid="_x0000_s3073" type="#_x0000_t75" style='position:absolute;
   margin-left:1.2pt;margin-top:5.4pt;width:16.8pt;height:195pt;z-index:1;
   visibility:visible' o:gfxdata="UEsDBBQABgAIAAAAIQBamK3CDAEAABgCAAATAAAAW0NvbnRlbnRfVHlwZXNdLnhtbJSRwU7DMAyG
70i8Q5QralM4IITW7kDhCBMaDxAlbhvROFGcle3tSdZNgokh7Rjb3+8vyWK5tSObIJBxWPPbsuIM
UDltsK/5x/qleOCMokQtR4dQ8x0QXzbXV4v1zgOxRCPVfIjRPwpBagArqXQeMHU6F6yM6Rh64aX6
lD2Iu6q6F8phBIxFzBm8WbTQyc0Y2fM2lWcTjz1nT/NcXlVzYzOf6+JPIsBIJ4j0fjRKxnQ3MaE+
8SoOTmUi9zM0GE83SfzMhtz57fRzwYF7S48ZjAa2kiG+SpvMhQ4kvFFxEyBNlf/nZFFLhes6o6Bs
A61m8ih2boF2XxhgujS9Tdg7TMd0sf/X5hsAAP//AwBQSwMEFAAGAAgAAAAhAAjDGKTUAAAAkwEA
AAsAAABfcmVscy8ucmVsc6SQwWrDMAyG74O+g9F9cdrDGKNOb4NeSwu7GltJzGLLSG7avv1M2WAZ
ve2oX+j7xL/dXeOkZmQJlAysmxYUJkc+pMHA6fj+/ApKik3eTpTQwA0Fdt3qaXvAyZZ6JGPIoiol
iYGxlPymtbgRo5WGMqa66YmjLXXkQWfrPu2AetO2L5p/M6BbMNXeG+C934A63nI1/2HH4JiE+tI4
ipr6PrhHVO3pkg44V4rlAYsBz3IPGeemPgf6sXf9T28OrpwZP6phof7Oq/nHrhdVdl8AAAD//wMA
UEsDBBQABgAIAAAAIQCsR3PICgIAAOgEAAASAAAAZHJzL3BpY3R1cmV4bWwueG1srFRRjtMwEP1H
4g6W/9mkaZtuoyaraqtFSCuoEBxg1pk0Fokd2abtngBxBu7CbRDXYBynqfqxEqL8OZ7xe8/vTby6
O7YN26OxUqucT25izlAJXUq1y/nnTw9vbjmzDlQJjVaY82e0/K54/Wp1LE0GStTaMIJQNqONnNfO
dVkUWVFjC/ZGd6ioWmnTgqNPs4tKAwcCb5soieM0sp1BKG2N6Dahwose2x30PTbNOlBgKd3a5pw0
+N2hpzK6Dd1CN0USryKvyq97CFp8qKpikSaTseI3+qLRh2Iatv3ytOfrabxMBywq9Sd64DOd0yPD
i7RJEi9vXyCenOEvmJNFHM/SUdWZ+kTYSREOqP1Wiq0ZZLzfbw2TZc5nnCloKadfP37+/v6NTXl0
bgkHICOQRy2+2CE4+IfYWpCKqPR9DWqHa9uhcDQ+ni1kQIoCXf95ofapkd2DbCgkyPz6ahlh/v5q
+nRVSYEbLb62qFwYQYMNOBp/W8vOcmYybJ+QvDTvyv5CkFln0In6WqH+whVd/COZ5Y0agQfTzsb4
MbadjxeyY2Xa/8FMV2fHnM/T5XKRTjh7przmi8V0FvvUIMOjY4Iaksl0mtIzIKghmcc0kmkfa1Di
Oztj3VvUV6tiHoh8Jjvoh4YM9o92MOZEMTgTvOhHafwDRCMpwg04OA3dxZMxnAxPVPEHAAD//wMA
UEsDBBQABgAIAAAAIQCqJg6+vAAAACEBAAAdAAAAZHJzL19yZWxzL3BpY3R1cmV4bWwueG1sLnJl
bHOEj0FqwzAQRfeF3EHMPpadRSjFsjeh4G1IDjBIY1nEGglJLfXtI8gmgUCX8z//PaYf//wqfill
F1hB17QgiHUwjq2C6+V7/wkiF2SDa2BSsFGGcdh99GdasdRRXlzMolI4K1hKiV9SZr2Qx9yESFyb
OSSPpZ7Jyoj6hpbkoW2PMj0zYHhhiskoSJPpQFy2WM3/s8M8O02noH88cXmjkM5XdwVislQUeDIO
H2HXRLYgh16+PDbcAQAA//8DAFBLAwQUAAYACAAAACEAhtJUaBUBAACKAQAADwAAAGRycy9kb3du
cmV2LnhtbGRQXUvDQBB8F/wPxwq+2UtiG0LtpRRBFJTSNoKvR7L5oLm7cHdtUn+9W6sE8XFnZ2Zn
drEcVMuOaF1jtIBwEgBDnZui0ZWA9+zpLgHmvNSFbI1GASd0sEyvrxZyXpheb/G48xUjE+3mUkDt
fTfn3OU1KukmpkNNu9JYJT2NtuKFlT2Zq5ZHQRBzJRtNF2rZ4WON+X53UHT3dbM+ve2zos8OH89Y
dQlmGyfE7c2wegDmcfAj+Uf9UgiYwrkK1YCU8g3tSue1sazcoms+KfwFL61RzJpewD2w3LQCImpN
wLosHXoBcTJLCKHVLxLOomkA/OzqzUUbXhj/xNEsnMZ/1VGUxMG3nI+p0gUN4wvTLwAAAP//AwBQ
SwMECgAAAAAAAAAhAOTVqrGBDQAAgQ0AABQAAABkcnMvbWVkaWEvaW1hZ2UxLnBuZ4lQTkcNChoK
AAAADUlIRFIAAAAmAAABhggDAAABV48zUAAAAAFzUkdCAK7OHOkAAAAEZ0FNQQAAsY8L/GEFAAAC
91BMVEX////8/PyamprHx8f09PTf39+1tbXKysrX19cnJyeTk5Px8fH7+/ufn59PT0+Kior+/v7v
7++BgYEAAAB7e3vJycnc3Ny5ubmMjIzFxcX39/dRUVEcHBx2dnbW1tbT09OSkpKnp6fi4uI6OjpJ
SUl/f3+GhoYvLy+rq6s5OTlYWFiPj495eXmRkZGZmZlOTk7U1NSAgICwsLB+fn6+vr79/f1TU1Os
rKz4+Piurq5UVFTV1dVBQUFZWVn5+fnAwMD19fXR0dFmZmZnZ2fu7u7MzMy3t7fGxsagoKA4ODhI
SEiHh4dgYGCcnJyJiYlSUlIgICDr6+vt7e2qqqrNzc29vb3Q0NCysrJLS0vExMR6enomJibOzs7l
5eVHR0e6uro8PDzY2Nj6+vrk5OTj4+Po6Og+Pj6enp7a2tqmpqZFRUUxMTGQkJAkJCRiYmKOjo5p
aWnd3d3n5+eEhIRjY2Ovr6/w8PDz8/Pq6urb29udnZ1KSkqjo6MJCQlycnK4uLgsLCyioqKpqamI
iIg9PT2/v78yMjJWVlbm5ubCwsJoaGgaGhq7u7teXl53d3cZGRn29vbBwcGXl5d4eHgKCgpcXFxE
RESxsbFhYWG2trbS0tLy8vKbm5uLi4vp6emzs7MEBATZ2dnIyMjh4eGUlJSVlZWkpKR1dXU0NDRN
TU1VVVXs7Oxra2sdHR1DQ0NsbGytra3Ly8tAQEBtbW2NjY0QEBBkZGRCQkJqampMTEzPz8/e3t4j
IyNQUFAuLi5wcHCYmJgrKyslJSUYGBi0tLQpKSlaWlpXV1c7Oztvb29lZWWWlpY/Pz+8vLyCgoIT
ExMGBgbg4OCoqKgWFhZbW1ulpaVdXV0RERE3NzchISFubm4VFRUwMDBGRkZ8fHxxcXGFhYVzc3M1
NTULCwsCAgKhoaEBAQEFBQVfX1/Dw8N9fX0oKCgeHh42NjYXFxeDg4MfHx8HBwczMzMbGxsDAwMS
EhItLS10dHQODg4NDQ0qKioUFBQAAAChAw9RAAAA/XRSTlP/////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
//8A9k80AwAAAAlwSFlzAAAOxAAADsQBlSsOGwAACQpJREFUaEPtW9154yoQVQ/qgyL4oAIqmRp4
nY9HiqAG9aE+VME9MyAbZMnrZG9yb3Z9nCgyQiCG+TkDyrRjMXJ0EYfgipy/jIDPNHHUFjzJsUPc
bDs7RZ5xoJTl3C5y7JG8HvWqk5pXCKy9dzBe7mKvLUzaQgcK+pzHm3Y4PRp9Nj2tSCqYevE4zl9C
RDuFUu9jPd4R9eqm7W+bHHuQdkn6tGRq/3ekVY969WpAFf7Y7eS0hGxt89hynUynxdrJCcpFl86z
v83nxxTqI2iPzBOpBIHEUxvOCwiB94Hl6amiDnBpatrIlNKrvbGJZldE3rhq5gvgED4hQPRWtQqn
TC+PzUzGNN2mTH57dXBp2kWi7uAl5MjVtqfgTdPNCP9TzzyztArAw7wsrOd4WTE+BG+5iczbydc+
UjZJDdnlcnMWlm9C+iiijbE5hshc+4DsasuDdHZD+DhgRdXYnRh17WMre2+YoDZHDO/64EpeRCLn
q8swOTa1ZgyunuUQuRbyFNIuts/DBN7V6QQMVA37ONjuXsamWOrMmOJDbY+225zY7dMzQmSpNTOb
5mftTYPY+uXmF44B7gPYw40Mpw7JBdOizF12NwdyL7qCuuLxgVCSMaWjYyYib4j6mm42IaQQwiCy
aFxwLtxVrtrY76vHvwK4WcX2WdP+WlA0MRUIee7kBVlaR+ASneeAD8Mk786/An4XPssOMR46Zye4
62XUBPqO6VARj/0+hkYnIR0hukN0mZjhykp/s2jeMTCKQD7r214E4prIqpMXcQwmgqzO/eOtRT1H
X2Y8gaNjRu6jI5k4fB0NDj47yn1fOhRpfAz2PmFw5fAs7TkGWotZq9yzh7NSVnmugiSuStkQ4L4R
L1OZMzyM7+sh9pw7fQHJtBDoID6xaVjE0c5hGTtfVDgkbqiXNCfsUPO83xLMr3GMyviuYwC/6pCh
5WUYG4bOJQxl8EtK8UednBaw6kY1vhAPPtEZE7YQBlrdXNVg+00C/VzKDB1xmJvfhzj1MfhOPseU
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
">
   <v:imagedata src="preview.files/preview_25367_image003.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><v:shape id="图片_x0020_4" o:spid="_x0000_s3075" type="#_x0000_t75"
   style='position:absolute;margin-left:1.2pt;margin-top:5.4pt;width:16.8pt;
   height:195pt;z-index:3;visibility:visible' o:gfxdata="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
AvdQTFRF/////Pz8mpqax8fH9PT039/ftbW1ysrK19fXJycnk5OT8fHx+/v7n5+fT09PioqK/v7+
7+/vgYGBAAAAe3t7ycnJ3Nzcubm5jIyMxcXF9/f3UVFRHBwcdnZ21tbW09PTkpKSp6en4uLiOjo6
SUlJf39/hoaGLy8vq6urOTk5WFhYj4+PeXl5kZGRmZmZTk5O1NTUgICAsLCwfn5+vr6+/f39U1NT
rKys+Pj4rq6uVFRU1dXVQUFBWVlZ+fn5wMDA9fX10dHRZmZmZ2dn7u7uzMzMt7e3xsbGoKCgODg4
SEhIh4eHYGBgnJyciYmJUlJSICAg6+vr7e3tqqqqzc3Nvb290NDQsrKyS0tLxMTEenp6JiYmzs7O
5eXlR0dHurq6PDw82NjY+vr65OTk4+Pj6OjoPj4+np6e2trapqamRUVFMTExkJCQJCQkYmJijo6O
aWlp3d3d5+fnhISEY2Njr6+v8PDw8/Pz6urq29vbnZ2dSkpKo6OjCQkJcnJyuLi4LCwsoqKiqamp
iIiIPT09v7+/MjIyVlZW5ubmwsLCaGhoGhoau7u7Xl5ed3d3GRkZ9vb2wcHBl5eXeHh4CgoKXFxc
REREsbGxYWFhtra20tLS8vLym5ubi4uL6enps7OzBAQE2dnZyMjI4eHhlJSUlZWVpKSkdXV1NDQ0
TU1NVVVV7Ozsa2trHR0dQ0NDbGxsra2ty8vLQEBAbW1tjY2NEBAQZGRkQkJCampqTExMz8/P3t7e
IyMjUFBQLi4ucHBwmJiYKysrJSUlGBgYtLS0KSkpWlpaV1dXOzs7b29vZWVllpaWPz8/vLy8goKC
ExMTBgYG4ODgqKioFhYWW1tbpaWlXV1dERERNzc3ISEhbm5uFRUVMDAwRkZGfHx8cXFxhYWFc3Nz
NTU1CwsLAgICoaGhAQEBBQUFX19fw8PDfX19KCgoHh4eNjY2FxcXg4ODHx8fBwcHMzMzGxsbAwMD
EhISLS0tdHR0Dg4ODQ0NKioqFBQUAAAAoQMPUQAAAP10Uk5T////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////APZPNAMAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAkKSURBVGhD7VvdeeMqEFUP6oMi+KACKpka
eJ2PR4qgBvWhPlTBPTMgG2TJ62Rvcm92fZwoMkIghvk5A8q0YzFydBGH4Iqcv4yAzzRx1BY8ybFD
3Gw7O0WecaCU5dwucuyRvB71qpOaVwisvXcwXu5iry1M2kIHCvqcx5t2OD0afTY9rUgqmHrxOM5f
QkQ7hVLvYz3eEfXqpu1vmxx7kHZJ+rRkav93pFWPevVqQBX+2O3ktIRsbfPYcp1Mp8XayQnKRZfO
s7/N58cU6iNoj8wTqQSBxFMbzgsIgfeB5empog5waWrayJTSq72xiWZXRN64auYL4BA+IUD0VrUK
p0wvj81MxjTdpkx+e3VwadpFou7gJeTI1ban4E3TzQj/U888s7QKwMO8LKzneFkxPgRvuYnM28nX
PlI2SQ3Z5XJzFpZvQvoooo2xOYbIXPuA7GrLg3R2Q/g4YEXV2J0Yde1jK3tvmKA2Rwzv+uBKXkQi
56vLMDk2tWYMrp7lELkW8hTSLrbPwwTe1ekEDFQN+zjY7l7GpljqzJjiQ22Pttuc2O3TM0JkqTUz
m+Zn7U2D2Prl5heOAe4D2MONDKcOyQXTosxddjcHci+6grri8YFQkjGlo2MmIm+I+ppuNiGkEMIg
smhccC7cVa7a2O+rx78CuFnF9lnT/lpQNDEVCHnu5AVZWkfgEp3ngA/DJO/OvwJ+Fz7LDjEeOmcn
uOtl1AT6julQEY/9PoZGJyEdIbpDdJmY4cpKf7No3jEwikA+69teBOKayKqTF3EMJoKszv3jrUU9
R19mPIGjY0buoyOZOHwdDQ4+O8p9XzoUaXwM9j5hcOXwLO05BlqLWavcs4ezUlZ5roIkrkrZEOC+
ES9TmTM8jO/rIfacO30BybQQ6CA+sWlYxNHOYRk7X1Q4JG6olzQn7FDzvN8SzK9xjMr4rmMAv+qQ
oeVlGBuGziUMZfBLSvFHnZwWsOpGNb4QDz7RGRO2EAZa3VzVYPtNAv1cygwdcZib34c49TH4Tj7H
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
">
   <v:imagedata src="preview.files/preview_25367_image003.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><![endif]-->
					<![if !vml]><span style='mso-ignore:vglayout'>
						<table cellpadding=0 cellspacing=0>
							<tr>
								<td width=2 height=8></td>
							</tr>
							<tr>
								<td></td>
								<td><img width=22 height=260 src="preview.files/preview_25367_image004.png" v:shapes="图片_x0020_3 图片_x0020_4"></td>
								<td width=20></td>
							</tr>
							<tr>
								<td height=77></td>
							</tr>
						</table>
					</span>
					<![endif]>
					<!--[if !mso & vml]><span style='width:26.4pt;height:207.0pt'></span><![endif]-->
				</td>
			</tr>
			<tr height=30 style='mso-height-source:userset;height:22.2pt'>
				<td height=30 class=xl6525367 style='height:22.2pt;border-top:none;
  border-left:none'>万</td>
				<td class=xl6525367 style='border-top:none;border-left:none'>千</td>
				<td class=xl6525367 style='border-top:none;border-left:none'>百</td>
				<td class=xl6525367 style='border-top:none;border-left:none'>十</td>
				<td class=xl6525367 style='border-top:none;border-left:none'>元</td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl6525367 style='height:26.4pt'><?php echo $pdata['mydata1']['product'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata1']['nums']==0?"":$pdata['mydata1']['nums']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata1']['unit'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata1']['price']==0?"":$pdata['mydata1']['price']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td colspan=7 rowspan=3 class=xl7125367 width=158 style='width:119pt'>备注</td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl6525367 style='height:26.4pt'><?php echo $pdata['mydata2']['product'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata2']['nums']==0?"":$pdata['mydata2']['nums']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata2']['unit'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata2']['price']==0?"":$pdata['mydata2']['price']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl6525367 style='height:26.4pt'><?php echo $pdata['mydata3']['product'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata3']['nums']==0?"":$pdata['mydata3']['nums']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata3']['unit'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata3']['price']==0?"":$pdata['mydata3']['price']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
				<td class=xl6725367 style='border-top:none;border-left:none'> </td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl6525367 style='height:26.4pt'><?php echo $pdata['mydata4']['product'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata4']['nums']==0?"":$pdata['mydata4']['nums']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata4']['unit'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata4']['price']==0?"":$pdata['mydata4']['price']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td colspan=7 rowspan=3 class=xl7325367 width=158 style='border-bottom:.5pt solid black;
  width:119pt'>京东官方授权、正品低价、售后无忧。全品类、全品牌应有尽有，全屋家电一站式购齐。</td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl6525367 style='height:26.4pt'><?php echo $pdata['mydata5']['product'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata5']['nums']==0?"":$pdata['mydata5']['nums']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata5']['unit'] ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'><?php echo $pdata['mydata5']['price']==0?"":$pdata['mydata5']['price']  ?></td>
				<td class=xl6725367 style='border-top:none;border-left:none'>  </td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
				<td class=xl6725367 style='border-top:none;border-left:none'>　</td>
			</tr>
			<tr height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=8 height=35 class=xl7725367 style='height:26.4pt'>合计金额<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>万<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>仟<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>佰<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>十<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>元<span style='mso-spacerun:yes'>&nbsp;&nbsp;</span></td>
				<td class=xl6325367 style='border-top:none'>&yen;</td>
				<td colspan=4 class=xl7525367 style='border-right:.5pt solid black'><?php echo $totals_money ?></td>
			</tr>
			<tr height=32 style='mso-height-source:userset;height:24.0pt'>
				<td colspan=20 height=32 class=xl6925367 style='height:24.0pt'>专业、专注、专营：京东所有品牌大小家电，网购代下单、样机体验、实体店购物，价格全网最低</td>
				<td class=xl6625367></td>
			</tr>
			<tr height=21 style='mso-height-source:userset;height:15.6pt'>
				<td colspan=20 height=21 class=xl6925367 style='height:15.6pt'>注：此单为保修凭证，敬请保存。自然灾害，人为损坏，雷击鼠咬，非正常使用之商品不在保修范围</td>
				<td class=xl6625367></td>
			</tr>
			<![if supportMisalignedColumns]>
			<tr height=0 style='display:none'>
				<td width=43 style='width:32pt'></td>
				<td width=54 style='width:41pt'></td>
				<td width=43 style='width:32pt'></td>
				<td width=43 style='width:32pt'></td>
				<td width=26 style='width:20pt'></td>
				<td width=32 style='width:24pt'></td>
				<td width=33 style='width:25pt'></td>
				<td width=51 style='width:38pt'></td>
				<td width=20 style='width:15pt'></td>
				<td width=20 style='width:15pt'></td>
				<td width=20 style='width:15pt'></td>
				<td width=22 style='width:17pt'></td>
				<td width=22 style='width:16pt'></td>
				<td width=5 style='width:4pt'></td>
				<td width=46 style='width:35pt'></td>
				<td width=19 style='width:14pt'></td>
				<td width=22 style='width:17pt'></td>
				<td width=19 style='width:14pt'></td>
				<td width=26 style='width:19pt'></td>
				<td width=21 style='width:16pt'></td>
				<td width=33 style='width:25pt'></td>
			</tr>
			<![endif]>
		</table>



		<!--endprint-->
		<br /><a href="../controller/action.php?flag=print">《远程打印》</a>
		<a href="../controller/input.php">《返 回》</a>&nbsp&nbsp&nbsp
		<INPUT type=button value=本地打印 name=button_print onclick=javascript:doPrint() />&nbsp&nbsp&nbsp
		<INPUT type=button value=重新开票 name=button_again onclick=javascript:doPrintAgain("刘建康") />&nbsp&nbsp&nbsp
		<input type="text" size="30" value=输入要开票的客户名称 onkeyup="showResult(this.value)">
		<div id="livesearch"></div>
		<!--以上添加-->





	</div>


	<!----------------------------->
	<!--“从 EXCEL 发布网页”向导结束-->
	<!----------------------------->
</body>

</html>