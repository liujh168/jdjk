<html>

<head>
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

	$username = @$_SESSION['username']?$_SESSION['username']:"";
	//$sexuality = $_SESSION['sexuality'];
	$phone = @$_SESSION['phone']?$_SESSION['phone']:"";
	$address = @$_SESSION['address']?$_SESSION['address']:"";
	//$product = $_SESSION['product'];
	//$unit = $_SESSION['unit'];
	//$price = $_SESSION['price'];
	//$nums = $_SESSION['nums'];
	//$date = $_SESSION['date'];
	//$memo = $_SESSION['memo'];
	//$print=0;  //借用此字段标志打印状态。5686=原始输入数据，133=预览，135=打印.


	//1、以下获取所有行的json字串
	$totals = 1;  //数组下标从1开始，老是与C++混淆 :(
	$pdata = array();

	$pdata['mydata1']['product'] = "";
	$pdata['mydata1']['unit'] = "";
	$pdata['mydata1']['price'] = 0;
	$pdata['mydata1']['nums'] = 0;

	$pdata['mydata2']['product'] = "";
	$pdata['mydata2']['unit'] = "";
	$pdata['mydata2']['price'] = 0;
	$pdata['mydata2']['nums'] = 0;

	$pdata['mydata3']['product'] = "";
	$pdata['mydata3']['unit'] = "";
	$pdata['mydata3']['price'] = 0;
	$pdata['mydata3']['nums'] = 0;

	$pdata['mydata4']['product'] = "";
	$pdata['mydata4']['unit'] = "";
	$pdata['mydata4']['price'] = 0;
	$pdata['mydata4']['nums'] = 0;

	$pdata['mydata5']['product'] = "";
	$pdata['mydata5']['unit'] = "";
	$pdata['mydata5']['price'] = 0;
	$pdata['mydata5']['nums'] = 0;


	$mysql_conf = array(
		'host'    => '127.0.0.1:3306',
		'db'      => 'jdjk',
		'db_user' => 'root',
		'db_pwd' => 'ljH5686!',
	);

	$mysqli = new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
	if ($mysqli->connect_errno) {
		exit;
	}
	if (!$mysqli->select_db($mysql_conf['db'])) {
		exit;
	}
	$mysqli->query("set names 'utf8';"); //编码转换

	$sql = "select * from clients where username = \"$username\" and print = 5686 limit 5"; //还要限制是当天的
	$result = $mysqli->query($sql);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$pdata["mydata$totals"] = $row;  //key为数字时，vba存取较麻烦
		$totals = $totals + 1;
	}
	//echo "执行结果 :" . $mysqli->error;
	$result->close();

	$username = empty($username)? $pdata['mydata1']['username']:"$username";
	$phone = empty($phone)? $pdata['mydata1']['phone']:"$phone";
	$address = empty($address)? $pdata['mydata1']['address']:"$address";
	$totals_money = $pdata['mydata1']['price']*$pdata['mydata1']['nums'];
	$totals_money += $pdata['mydata2']['price']*$pdata['mydata2']['nums'];
	$totals_money += $pdata['mydata3']['price']*$pdata['mydata3']['nums'];
	$totals_money += $pdata['mydata4']['price']*$pdata['mydata4']['nums'];
	$totals_money += $pdata['mydata5']['price']*$pdata['mydata5']['nums'];
	//以上已获取全部可用于预览的数据


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
				doPrint();
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
			//window.location.reload();

			//var printHtml = document.getElementById("sample_26514").innerHTML;//这个元素的样式需要用内联方式，不然在新开打印对话框中没有样式
			//var printHtml = prnhtml;
			//var wind = window.open("",'newwindow', 'height=300, width=700, top=100, left=100, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
			//wind.document.body.innerHTML = printHtml;
			//wind.print()
		}
	</SCRIPT>
	<meta http-equiv="refresh" content="5">
	
	<meta http-equiv=Content-Type content="text/html; charset=gb2312">
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

	<style id="sample_26514_Styles">
		<!--table
		{
			mso-displayed-decimal-separator: "\.";
			mso-displayed-thousand-separator: "\,";
		}

		.font526514 {
			color: windowtext;
			font-size: 18.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
		}

		.font626514 {
			color: windowtext;
			font-size: 16.0pt;
			font-weight: 400;
			font-style: normal;
			text-decoration: none;
			font-family: 等线;
			mso-generic-font-family: auto;
			mso-font-charset: 134;
		}

		.xl6326514 {
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

		.xl6426514 {
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
			text-align: general;
			vertical-align: middle;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6526514 {
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

		.xl6626514 {
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
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6726514 {
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
			border-top: none;
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl6826514 {
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

		.xl6926514 {
			padding: 0px;
			mso-ignore: padding;
			color: windowtext;
			font-size: 24.0pt;
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

		.xl7026514 {
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

		.xl7126514 {
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

		.xl7226514 {
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

		.xl7326514 {
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
			mso-number-format: "\@";
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

		.xl7426514 {
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

		.xl7526514 {
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
			mso-number-format: "_ * \#\,\#\#0\.00_ \;_ * \\-\#\,\#\#0\.00_ \;_ * \0022-\0022??_ \;_ \@_ ";
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

		.xl7626514 {
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
			border-right: none;
			border-bottom: none;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7726514 {
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
			border-right: none;
			border-bottom: none;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7826514 {
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
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl7926514 {
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
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8026514 {
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
			white-space: nowrap;
		}

		.xl8126514 {
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
			border-bottom: .5pt solid windowtext;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8226514 {
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
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8326514 {
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
			border-right: none;
			border-bottom: .5pt solid windowtext;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8426514 {
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
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl8526514 {
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
			border-right: none;
			border-bottom: none;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl8626514 {
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
			border-right: none;
			border-bottom: none;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl8726514 {
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
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl8826514 {
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
			border-bottom: none;
			border-left: .5pt solid windowtext;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl8926514 {
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
			white-space: normal;
		}

		.xl9026514 {
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
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: normal;
		}

		.xl9126514 {
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
			background: white;
			mso-pattern: black none;
			white-space: normal;
		}

		.xl9226514 {
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
			border-left: none;
			background: white;
			mso-pattern: black none;
			white-space: normal;
		}

		.xl9326514 {
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
			border-right: none;
			border-bottom: .5pt solid black;
			border-left: none;
			background: white;
			mso-pattern: black none;
			white-space: normal;
		}

		.xl9426514 {
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
			border-bottom: .5pt solid black;
			border-left: none;
			background: white;
			mso-pattern: black none;
			white-space: normal;
		}

		.xl9526514 {
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

		.xl9626514 {
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

		.xl9726514 {
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

		.xl9826514 {
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
			border-right: .5pt solid black;
			border-bottom: .5pt solid windowtext;
			border-left: none;
			mso-background-source: auto;
			mso-pattern: auto;
			white-space: nowrap;
		}

		.xl9926514 {
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
	<title>京东健康电器打印预览</title>
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

	<!--#region-->
	<!--[if !excel]>　　<![endif]-->
	<!--下列信息由 Microsoft Excel 的发布为网页向导生成。-->
	<!--如果同一条目从 Excel 中重新发布，则所有位于 DIV 标记之间的信息均将被替换。-->
	<!----------------------------->
	<!--“从 EXCEL 发布网页”向导开始-->
	<!----------------------------->
	<!--#endregion-->
	<div id="sample_26514" align=center x:publishsource="Excel">
		<!--下列信息由 Microsoft Excel 的发布为网页向导生成。-->
		<!--如果同一条目从 Excel 中重新发布，则所有位于 DIV 标记之间的信息均将被替换。-->
		<!----------------------------->
		<!--“从 EXCEL 发布网页”向导开始-->
		<!----------------------------->

		<table border=0 cellpadding=0 cellspacing=0 width=617 style='border-collapse:
 collapse;table-layout:fixed;width:463pt'>
			<col class=xl6326514 width=43 style='mso-width-source:userset;mso-width-alt:
 1536;width:32pt'>
			<col class=xl6326514 width=54 style='mso-width-source:userset;mso-width-alt:
 1905;width:40pt'>
			<col class=xl6326514 width=43 span=2 style='mso-width-source:userset;
 mso-width-alt:1536;width:32pt'>
			<col class=xl6326514 width=26 style='mso-width-source:userset;mso-width-alt:
 938;width:20pt'>
			<col class=xl6326514 width=32 span=2 style='mso-width-source:userset;
 mso-width-alt:1137;width:24pt'>
			<col class=xl6326514 width=51 style='mso-width-source:userset;mso-width-alt:
 1820;width:38pt'>
			<col class=xl6326514 width=20 span=3 style='mso-width-source:userset;
 mso-width-alt:711;width:15pt'>
			<col class=xl6326514 width=22 style='mso-width-source:userset;mso-width-alt:
 796;width:17pt'>
			<col class=xl6326514 width=22 style='mso-width-source:userset;mso-width-alt:
 768;width:16pt'>
			<col class=xl6326514 width=5 style='mso-width-source:userset;mso-width-alt:
 170;width:4pt'>
			<col class=xl6326514 width=46 style='mso-width-source:userset;mso-width-alt:
 1649;width:35pt'>
			<col class=xl6326514 width=19 style='mso-width-source:userset;mso-width-alt:
 682;width:14pt'>
			<col class=xl6326514 width=22 style='mso-width-source:userset;mso-width-alt:
 796;width:17pt'>
			<col class=xl6326514 width=19 style='mso-width-source:userset;mso-width-alt:
 682;width:14pt'>
			<col class=xl6326514 width=25 style='mso-width-source:userset;mso-width-alt:
 881;width:19pt'>
			<col class=xl6326514 width=21 style='mso-width-source:userset;mso-width-alt:
 739;width:16pt'>
			<col class=xl6326514 width=32 style='mso-width-source:userset;mso-width-alt:
 1137;width:24pt'>
			<tr class=xl6426514 height=50 style='mso-height-source:userset;height:37.95pt'>
				<td colspan=2 height=50 width=97 style='height:37.95pt;width:72pt' align=left valign=top>
					<!--[if gte vml 1]><v:shapetype id="_x0000_t75"
   coordsize="21600,21600" o:spt="75" o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe"
   filled="f" stroked="f">
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
  </v:shapetype><v:shape id="图片_x0020_1" o:spid="_x0000_s1035" type="#_x0000_t75"
   style='position:absolute;margin-left:15.6pt;margin-top:0;width:62.4pt;
   height:44.4pt;z-index:2;visibility:visible' o:gfxdata="UEsDBBQABgAIAAAAIQBamK3CDAEAABgCAAATAAAAW0NvbnRlbnRfVHlwZXNdLnhtbJSRwU7DMAyG
70i8Q5QralM4IITW7kDhCBMaDxAlbhvROFGcle3tSdZNgokh7Rjb3+8vyWK5tSObIJBxWPPbsuIM
UDltsK/5x/qleOCMokQtR4dQ8x0QXzbXV4v1zgOxRCPVfIjRPwpBagArqXQeMHU6F6yM6Rh64aX6
lD2Iu6q6F8phBIxFzBm8WbTQyc0Y2fM2lWcTjz1nT/NcXlVzYzOf6+JPIsBIJ4j0fjRKxnQ3MaE+
8SoOTmUi9zM0GE83SfzMhtz57fRzwYF7S48ZjAa2kiG+SpvMhQ4kvFFxEyBNlf/nZFFLhes6o6Bs
A61m8ih2boF2XxhgujS9Tdg7TMd0sf/X5hsAAP//AwBQSwMEFAAGAAgAAAAhAAjDGKTUAAAAkwEA
AAsAAABfcmVscy8ucmVsc6SQwWrDMAyG74O+g9F9cdrDGKNOb4NeSwu7GltJzGLLSG7avv1M2WAZ
ve2oX+j7xL/dXeOkZmQJlAysmxYUJkc+pMHA6fj+/ApKik3eTpTQwA0Fdt3qaXvAyZZ6JGPIoiol
iYGxlPymtbgRo5WGMqa66YmjLXXkQWfrPu2AetO2L5p/M6BbMNXeG+C934A63nI1/2HH4JiE+tI4
ipr6PrhHVO3pkg44V4rlAYsBz3IPGeemPgf6sXf9T28OrpwZP6phof7Oq/nHrhdVdl8AAAD//wMA
UEsDBBQABgAIAAAAIQByhiHMAQIAANkEAAASAAAAZHJzL3BpY3R1cmV4bWwueG1srFRtjtMwEP2P
xB0s/2eTdrcljZqsqq0WIa2gQnCAWWfSWCR2ZJu2ewLEGbgLt0Fcg3GcD5Vfqy3/7Jnxe89vxl7f
npqaHdBYqVXGZ1cxZ6iELqTaZ/zL5/s3CWfWgSqg1goz/oSW3+avX61PhUlBiUobRhDKphTIeOVc
m0aRFRU2YK90i4qypTYNONqafVQYOBJ4U0fzOF5GtjUIha0Q3TZkeN5hu6O+w7reBAospNvYjJMG
H+1rSqObUC10ncfryIvyyw6BFh/LMp+tVvPVfMz5UJc2+jgc8csh5vM9EoW76g524nJ6xM8n3DHm
j7ydL66n1BnlLCj5lzKJk+VyTE20A1krRWBQh50UO9PTfTjsDJNFxuecKWioQb9//vrz4zub8Wgq
CQcgJZAHLb7avmPwgn41IBVR6bsK1B43tkXhaG48W3CfFAW6bnum9rGW7b2sqT2Q+vXFMsLgPWvs
dFlKgVstvjWoXJg9gzU4mntbydZyZlJsHpG8NO+L7kKQWmfQiepSof7CJV38E5nljRqBe9MmY/wA
29a3F9JTaZr/wUxXZyfqUfcOOHvKeOzbBSmeHBOUSW5m16sFZ4JSi2VyEy+6dgYFvrA11r1DfbEa
5oHIX7KBXjCkcHiwvSEDRe9I8KAboXHyRS2pdVtwMAzb2R/Rnwx/Uv4XAAD//wMAUEsDBBQABgAI
AAAAIQCqJg6+vAAAACEBAAAdAAAAZHJzL19yZWxzL3BpY3R1cmV4bWwueG1sLnJlbHOEj0FqwzAQ
RfeF3EHMPpadRSjFsjeh4G1IDjBIY1nEGglJLfXtI8gmgUCX8z//PaYf//wqfillF1hB17QgiHUw
jq2C6+V7/wkiF2SDa2BSsFGGcdh99GdasdRRXlzMolI4K1hKiV9SZr2Qx9yESFybOSSPpZ7Jyoj6
hpbkoW2PMj0zYHhhiskoSJPpQFy2WM3/s8M8O02noH88cXmjkM5XdwVislQUeDIOH2HXRLYgh16+
PDbcAQAA//8DAFBLAwQUAAYACAAAACEA53HrsREBAACCAQAADwAAAGRycy9kb3ducmV2LnhtbEyQ
zWrDMBCE74W+g9hCL6WR7RCjupFDCBR6KuSn0KOQ5djEklJJid0+fddJjHsSs7vf7Kzmi0435Kyc
r63hEE8iIMpIW9Rmz2G3fXtmQHwQphCNNYrDj/KwyO/v5iIrbGvW6rwJe4ImxmeCQxXCMaPUy0pp
4Sf2qAz2Suu0CCjdnhZOtGiuG5pEUUq1qA1uqMRRrSolD5uT5pAeTl5P9eyTrSq5+2q+RXJ4Epw/
PnTLVyBBdWEcvtHvBYcE+lPwDMgxX9csjaysI+Va+foXw1/rpbOaONtywGOlbS4v6o+y9CoM1UHF
LyxOIqC9Y7BXLr5xuO8fx6YMB3vHgU3ZjF1QOqbJ5yjGr8v/AAAA//8DAFBLAwQKAAAAAAAAACEA
0y0JkbkgAAC5IAAAFAAAAGRycy9tZWRpYS9pbWFnZTEucG5niVBORw0KGgoAAAANSUhEUgAAAIIA
AABbCAIAAAFHV/rbAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7E
AZUrDhsAACBOSURBVHhe7Z33eyRHmcfvX4B7+OE4Hw/PHRzGD2ecAGdv9tqsvWvANmDAAXNgnNY4
woHj3XH2Guz1Egw4gMGsc9hde7NWWaus0eRRzjnMKMyMJt2n6m2VWqORNNLIu3rAX4re6uruqjfX
W92l8T+klo6FnklOwzqfRuZnYrFENBqjhMPRM888e+0la6wLGtYzpr/x8fFwODw5OfnIQw+HQqHh
waGhgUGOcptgZpz9+/d3dnb29PT09vb29fVVV1a1tbW1Nrd0tnfwmHWThvXM6Oho3pGjjY2NPg0/
8Pq+su2qxx559Mkndtx+621ym8B6prq6uqGhgUpHR4fH43G5XE6n0+v2BHz+7s6uNDFklsHAwIDX
5W53BSKRiNVkQ+Zn6Bg6BwdnsWGQ+RmE0dra2t/fb53PxrzjIG4es85nY+aZRCqeTCYSyVQ8lZri
MVqSqiIloRosZB5nASz0gLKLRS2NOxKJ1NRUnIKl7djxi4svvMj+mHpAd6SaYrEYHKOKV3e/MhYM
YRMjQ8MDff3Ym75ZwRpheHjY4XB0d3djYGD3y3/t6uhsaWlpb23r6+mlF7kNWCPk5x3DKDArMbDG
QENZSem2K7f+7rfPfvPr35BbBeoBhi4rKxOqMC23241peVxurKulqTkeR9QzyMA0nHQG2gKOQGNX
u9VqQ/oDgGcmJiYwExkzDZkfwP5LSkqyfQBTn5qa4u6FHsCW4skYJhNPxDEexSbGNG1L6sI0MoyQ
DWR8A6t1HixzDNgkVCHOSCRKHV1YFzJhkTGgEW1jklgvNtzXN+D1+iMRawCAWF9//fW33nizqaFx
bGzMemw2ZsYQlqPRKI8lEgmC6sjICMYWDAYxco6cYvOh0SChlXuIoFhtT1c3x97uHmInj0hXaZg1
Bixjpu/ve4+j1+slXDU1NTU3N+MgxBNCcVtL64/vf2DXzmeIlbhCgz8A+TQSnwf7B9Ls22BmDO4g
6BYUFBQVFUE1rDAGI+EoAI+x4Pa4nS78xufxUqFAEPOB1UsmzNIH/kDXVIypMDDMwQQ+TP/EfIej
Dud0u1wBr6+psQnvT8TVIwtgEZ0byKiRVLLylzsLfnCr44mnYniIbjcEzYdsx5DYhm7gCZGic2nP
BtmOgQwDgQBHiS+o37qQBdLHgG+Lew4EiwQhJTWZxGBUkwQIDokkWkBWunG6JOM0qw50TzPIlo9l
YzkDaC5nYLXOg+UPYK8sgCUPQIc4CpFmclJlbcRBXEecKSOyGoDH1fwQS1DMpE4hIJ5++hnnn3ve
JRdd/PwfnuPOuQwtMgCkQaNEWcqBA4eIuRJlCXlE1tLS0r/+5eWCY/nBkVHutB6zYdYAZnwtW5VI
0ZEkF5LGrluzNjwxKUGXZIKgS8Ql9lEYYJF0UHonnaMvCFfPj4yYGK7yFh3GKaSJxDvyFInhHPt7
+0aHR1CGdGLHrAHgsaK8Ii8vj6ignteQbIceGfvKLVeQG3W0dxA5WppbiN4dbe0yAAMrrucbQC5A
7MH9B44ePkIQlRlCJgm6AwQJ4vaF519QV1NLKk7iwyTR3NjEGExEsGL6sWNmAMQC4fReWlyCNJkb
TO4FVH7v9zP5UB7/+f+tufiSW2/54a93/Yps7IH77r/s0s0MJl2lYUZESLa8vDw/P7+2tpZThG5N
Ofb5B7jcFMg3pb29Hf3PpV0wwwFkYnOkgTgRLTAEHyw4zDQnCw6Z4CiwIpMoJiCdZMQMB9gIliN1
kZgQBWcIR4Zx1Nc7HQ5qEM4ApLfjocxJhsHMAAsDt0IUXre3yeGt8zlbPY1Dw/2GiAWQ1QDSC8ep
ocHy2+8u/95toabGpDL6RXoHWQ0ApRzpjqQGq8UnUE82vYNsB6A7lIlbMAZ+lzEqZES2A6B/VI0a
MDbsmHBiXVsM2eoAW2IM4gd1pLTCImKGwYjpkdDEEfKXzwHPQ1k0lZxMTcUgMRaPKUpNUf/HDxPR
mM5O7ZeSskhJQ/oAzF0UtaZJJmLkIXp5IwsbiuqG8VisUmMSsl2ixDV5VkfTyEpEqxwnh4e5sswF
J4EHZQ02WK05YMV4MNTwrymxGKlfkiyN1bDkVyZ1o5AVTUyES0rKnn76GWZfFoZPPrHj0IGD5EHS
FciGyZx4sNGtZkJDpaEV6iXrDIejhDGBRBwAZ3fcccdHP/qPH/vYx075+D9/6l//7bOfOXXj+g1k
ONwmc0U2WD4P0A0RkEJywZFRbQwoMT/4s4dOPfW0m266ORQaR97cBiS3JRVTCaR+D6ByguFhZE+W
SB6nlvwhdUP2bGTgwa4+6rIUkEaRN70zBoAaAFn67YZKmaUihJIykwBKagtZKs2FyCH1qoKMlMyT
QlLKIpwkUQp1cmru53HJpLJBZj0IxVBPX6ReFRUV5GNMcYhNjd7bCx2cylEgxNlbSGJ9Hq+k+pDL
1MX0yMqYdJWJGPYone0dlK6OTuEBltADCkEQogS7QOfDvHrgSEcMn5937N2339m3Z29ZSSnTP1m4
5N8k35CiqOnogDKDrmm0Nrdsv+NO8mTWQ+SEsERp4RBoIAflyA1kohSuytIDHtSr1NGgeR+2HB6M
2WAwiLa6sopAgZMJHWgAtaCTwBzQKIBJgRBKUvvaK69uWLceZ9351NPlZcdJ/dGPrA24Aa5gRt7r
kgTvePwJFgnGE5apB7F4ckayL5Rw7GheUUFhWWkZ9sBV1hJqeaLXK6ThdqjFi4Z1g8criyMKCwFO
XfVOemMpc/utt121ddv6tesoay9Z89WrvnL3XT/6859egm28SOUbS0EGHnBNDJcVDkuUwsLC4uJi
KiSqZnGMbPATWmQhJCsWoBcvc6DXMPYCS/CGEmThhwYo2B5Bwoh/ScjAA/RBEH5cXV2N5eCLhAij
U+NqAKdHLTgGtiT8AItyvQ6r13A66s2CjGIMCQawItwAB6Ar6X8ZyMDDkiDMAPgkdCJOXAJzghlv
bb3DXV9VV1VbW1fnU6bFqg8F4EyNfjfez7Qg0gFWd8tCTjzMp3po4lIwFokO9R9/9je1Tz5e++xT
zh2P521/oOdYUTQR09n8jL8uz4QMVkAPpmLqgmQs2TUw6Gttamhpbuvt6xjp7hntj0ZYmbD+gOwl
RJ6FkSsPaTCUYd9YC37CZAKo41cEa4yHS7nTbcdK8gBluDiEYuhMfIRX3J3ZEB6oU2HyJrNS+lq1
PAiQNOQSl+TdHSEY0ombVVVVTJHwwD2rmgeyPWRPgKKOzRCmUAt1bIxLKAQOOT2ZPCw8MkQzP1Kx
SEymyLj4V5yEHIaZJ56Iy7uEFcRiPEBMQr+uoFAnKDITpMLQFSe8JOOJVCwVjaQS+o3G7ALhCdiZ
bkgkozwNO/qNjv40O3/hMo9T1TRY/86HRXhQn3HUCxQ1OoVkcio5xYICkY/lHwy98vLY+weikQlo
1W/71D0zRciBAvUuJhmJJ2OO2vBrLw3u/ku4pyv95tkFYfGI1YFFy7xYeZ8+8fiQh6VA2dr8sG5a
Fk6CHnInOg0nQQ9ppwJpXB5OtB4skqdhteaGE8QD0xzLfFaCrHLllRTTXzgcYdrWrwC5yNS3zFxw
xXgww/OvFKiKxQj2TIqZX1Ry7Ozs3r371YcfeuTeu+95+MGHXnjueZZKer6UrrJiaQV4MCMxB1Pk
wzjk6s/j8UhEfcymbqin0BgOR2trHZs2bT799DO+cM4XL7rgQsqmDRuvvfqaluYW6ZCes2FjZfSA
qWAJRthShG5Yoj45qV5miiEJgsFgSUnJJz/5yY985CMf/6ePf+KUf/n3T336rDPOfOC++3u7e7C6
bKgX5MQDw4ihC30iY2EAUyksLM7PLxwfx/i5ZL2ZlTeZHOVVJ4vv/fv3v/zyy2+89vp7e/fV1dQO
9PWPhULcKZliNlg+D4yBRzIY0AwgO0sDcOLx+L70pfPOOuucQ4eORMLRCcWJxQDUo4SQ3iMJGwMD
A2rXwfAIZWhgMDQahAfuRDRZqiKdB2WA+sm5RwOoh2gjUYgT86BRKrS0t7df87Wrr9xyhdNRPx4a
mxizSIdaVhfDQ0MjQ8PmRbe8kzUvW2GDnjFOxkobOiPm5UFAXeQhjXIqsgRIERhOOAJOpQWz7unq
nhyfGEPoo2qXHmtU1nTqpXef9dKbo/29N6fDg0MwyuNiSzLuwshgS+YxeoEa1vIcVWTRxiMrZggC
1BGtMgwNqYukQXBEbadFA0hX7XXR1LMcZYGqML3NUAp1eIAr4QGVZkO9YF5/oAs6Ysjjx4+73W5W
lUI9LZACQfY39bQDcwr6enuLC4tEtKogbrTS1SUv7tXbctuLe3jgTvPxAaUtack6Lw9IHYUy2KFD
hw4fPgwnfr9fXs2LHKFJAFcGfRowGfD7zz/3vKd+8UsxFWilq1aNFtDcIu+6KTAj3x/EurQerHf3
y+RBrBDQC4L3uNz79ux99+13jh3Nq6qo9Hq9rOuhAVK0NNXHBwBvBjAp4MGN6zc89LMH5VODfH+g
yPcHKvLxgSLaED1geMQE4wzZsDGvP+CjgwODpcUlkHLg/f0Vx8sZw+l0yqeGpqYm9YVB72cSroQx
0CZobYOst998i6n35pu+e/jgIXlVDAN+r496gz9AHU5amppFIViUGB5sGFHmxAM+ioCPHj4i31BQ
AuEFc8I3oF59NbFBfTvR0B9PLCB7CHXU1m2/485zzjr7lu//AH3Ki3vz7h6FyDcUGEYbDHffPfc6
6hzZkG6QmQdiPH6J1JFf3pGjGFJ9nSMyGcbWMSc4AZAuX0zskEuAq0IrInfVO996483bb71t/dp1
WNf13/7O/ffe94sdT/7ut89Sdu185rFHHv3Pm7/HZHLV1m1/evGPidjSXt5k4IHJhUCO15aVleUd
zcs/lk+EcbtcsSkVXvEB+eSjvvzMhrABFBP8o78DyUcT2ehcU1X96u5XyE+/9c3rNm+6FJbWrVlL
5cbrb3jyiR0Fx/KJymqj8hJ0oJDOA0ogHMGAx+MpKCjIz8/nWFpaiqFLfk/A5SoU6w8m1hcTIKcZ
oL+bmG8o8mlL6lKwqLbWVmIAU8qSwpFBBj0Q6RF2dXV1UVFRsUZtbS0DcEl6hxnCKNZiETkNi5vZ
sJMrRetHOYPEKwmvzC1IR4+/ZGTggaDC2OXl5eTGoKamhgGwImGAI4ANIi/KUSbj8aAWoRhYDOlv
WQBnmMuD+RgHDzDADEgYlP6XgQy2RDiq1KirqyO8kD5osmcYMBUkxxzHPcKDqMVwAgPyMc7Ohvmg
iBIkqjInmAlhecjAAzk0UxXEIRurVSNt3jGnZFNYM5zgyeInwka9E/odzjqHq74enrC5aSX4UAIM
EE+ZCqb0l6FckMGWsofhB9MiEmByzHQWJ0rqzur6mtq62nqPt87rc6INr99H1PW5W5sb8CiYN4LQ
/S0TOfEA7MNTx7rwEwJXW3Nro7eRcNTiCTTW+11Ob2tjY5vD39LS3tHfPR6ytoLnSL0gVz1Ytdmg
PZqIhacm2osLih57tHbnE45nn6786cOVO3YlgsGI7bU84OZcnAHkqgc7oAZInakklkx05ucX3/VA
yS23F37/1pp7f9K6Z18iHk3Ag56Izc2msjysjB6opMsymQpPxvztrc0d7S2dHe2D/d2jfSNjwZSi
PxFVqrCQIwNgxfQgpBiCmHEl7JLbMuFQx+MJ0yvixGlYSVuCPogT+lhnQz2ZrOTkwgO5sNwj968U
VpIHwqvkC4RaZgxZXailRGsrkYpEmJAld64sVpIHGCDXIudF5KIBGOBIneSKvB0eRFHAemYlsJL+
AFh4YDYI3uFwQDq5I9ogZSR9hB94yDGMZsRK6kHYIMMl3WC2RhvQDRvkLFRoEVviHrl/pbCSPAD0
gBKgmGkYD6bOEaJxFTQDe5rNVcwDrozRQ7fkEVCMZ1PHfgBpLN5C++rlAcrI4eBBiOYUHjAnoZgW
HMOcriyWwEPa4Omn2mBkMSmgbn9dx9VwJKy+EJ1gPagBFajoHRusLpKxKZUuJFXWzwo7rtIGcoew
yoEgzhQLcqKucW88npiKkUhJujRfUeOhSD1h6vvUcQEspgcIl0I/EJuIx1LheEr9KTM8KGZiUQQu
TE7TYA2bUPtGTHMipbbxqa0b6u94eXD6wtyixtFVC5qTBbAwDyrDtIrafBJPKAGiBLUfJKYaFUFo
gma1HcVWOBWSFPR91GUjCb3Cjv3mtMKzet+MpYdFsQgPrP7NphlKFGFiDYlUpK0p+OpL47tfnfR5
J5WMk6jG3GYVWIAIJjV4Rwbhiej7eyZ2/3GovDiltoTOuX+6qEdgV+siGzaW4NMf4oPDh2pYFfgb
VIMKZsuF1cUJx9+dN5xccc+HD4OShZOrnr/loKRXXWo/EmANYIc0ArnHeuDvUA3L5pnn5K2IVORt
29SU+tNgpBrVu9XM/iP5m9XpMiU71yiyfZAWCvfTMjw82t3V097a1tSg/qavs6NjeHBoYmxcfZ5V
KahK2T84Pa0Kb7CzR50zexFZq7XiTEn7A2dL0PZGm/RnFTShtnKGowMDQ5WV1b///XN33nnXddd9
+4ortm7aeOmGdespl126+cuXXX7V1m03fOf6H22/a9fOZ46XlgVH03+WRJOqYJ3ngJPpDQLr3AYa
jcQxVSl2EUs7MpW/j6cF4crGTepG4rqodlOQ/uRkGC2iibfffvfaa79xyimf+PSnP0M57bTPff7z
Z5x95llnnXEmhcr555639pI1X/rCF1HJzqee9nq8Er4sKjUy0r8MrBZvgEOki1yn1G86KYHai1GA
FHQgMqXI1fHxSalTMHU7Jm2QllG9xa6rq6ugoGDXrl3bt2+/5pprtmzZcvlllyHxrVdcee3V19x0
w4133bn9vx997I8vvFhSVNzR1i4bJCORCESulPQNTo4ajNy10BVgD4iY1PbSTNKX0t8/ePjw0Xvu
ue/GG79LPHn++RdbW9uJS6hB/1aB9KGkj9QE4/oXAUBIQyr2Fm4IBoNDg0Nq79rIKEe1EW9kdJy7
gqHQaFD9yMHIKHfSG8Qpf1xRTSyiBpEXRzOq1IH9qlxKg75r1iVOYQA27GLSZjqzLXY+cFHucTgc
xOszP3/maad+9vTP/cc1X7t677t7giNBRDY5jh7CIl+BSNm+xVM20yJWU2Rv7fCQ0gFlaGCQIlvx
KKIPNEEvdEWfEGNXQxqPy8Pi3qBkqWGd2waW9jTIJWDqVET6Inpjm4A6EH0Au0oE0iKQexBoc2PT
X1768693/erF51/IzzuGyEhpxHLVHmANbhPRD2sMDalNtYMDahOzEbGImyI7hE3p6+mVimx3tjQ3
qrwBalEDxiesATvLy0ZWQUlJd85gtEANUUUEJDYC7N5jbkDiIhSRDmLSZmrBrpI0yCVg3SobmEdG
MXxloaNBJfeRUUQmNm5JfVruA/onSPr6+noFPTO/PyJ7baWYTc5UZJOz3EO3KMmKUaOj0AMv8k1F
hACE0xyR7dxgJCuADmYqaILb5uZmj8cTCASY9GAbSWHFEu4RHxIRWSAU+dZrhw4TMxAlUbEuZwLX
EDfSQe4ICIOVOmZrGbWGiL5H/5wKhAG1Zbijs2v+YjaaGx3M9oYg1gZfYmcIIU0muWAJU7QZkoro
AJHBWnV1dZ5GaWlpZWWl2+1GMbCNLBAEUhBQRzQLQMQnmNuCLgWtLS15R44++NOfvfDc85XlFcgL
NXBEghz7evtE4nrTttpV3qZ3JgiomZ+Dkc2PlLS98nYd0DNqmPYGpQZcwS4HU88Ri6vBKB9IhRbs
ndgAfSw4S4tLDry/f9/eve/t3Xdw/4GiwkIWOwjI5XKZPSYAiQiQjmWbGspQNSx1aVhNsxsF7W3t
VRWVzNIXnn/B5Zsv+/n//G9tdQ2CQ5TIFyXJfni1GG5sbG5qbtGFlsZAg2x8pIgm0AFH7pd98uIN
HEUNognRAWUsNCbhyC4NgZzmgkXUYI2jIS3QgWMSJYkPUFxTVX344CFylT3vvLtvz979771fVFBY
X+fwe9XPGqIJv9/fqNGkYfbkC0RDAJs1sNQ1DbFrA6W5js4Gf+DN199AGZdu3LR+7bp7777ntVde
RT0IWt5GUOEeipxSCejfg5EWtCIFlYgaKGiFnkU9lm9pfSgdBEORyTAxAPZFDsCSywlQg4xqHwyL
QAeEI4KM1+0hUcEVkD5HXIHT6soq2GBCQ+iys9Xn84kmcA4BE4lVm/3HBQLRVhosvbW0YOBi1IgP
mRYXFu3a+cwVX95yzllno4/td9wpv9nrqneK6H0e2Zbqlp3AFNkVbPQhepIO0QGakBkCBRCU8BK0
S4dul5sEJE3oaafLxhLUADAHSRkJ3EiQ5SWucOTQYQqVo4eP0FJXUwsnkxNq5sDMUQM+gSa4H88A
6ABIfS7kqh1aWbMgls5RpIxYETpGgD5u+f4PNq7fsPaSNRy/9pWv3vbDW4lazCJvvfHmoQMH8VSi
Jc4qW7QpPE5XHJ2OeoJbxfFyTGr3y3/d+dTT991z77e+eR39fO+7N7/79jvkXkhAxLLiWDwoyRF9
oAOyIBIedIB5VlVVHTuah+jlj1LwA0yGeQJ+sKZoWCXX+A3RHMF59V9zIGWOS4WoxwAFiuAoYuAc
5e9BpBF3JEj+5le//q8f/+TG62/48mWXX3zhRUwk5597Hsd1a9Zu2rBRXlpctXXbtiu34knMMZs3
XYrE11x8CS7F1W9c+/W77tzO0kT9NVxPb+4b0BdGtnMD8wF5pGQsRAbZjVdQUHDs2LH8/PzCwkJO
OcpfzA0PDaMDnqIHlMdkTkCnHc8gTKES6shX9uNzBCJxaZHb7JB70kG0AbY/YBFlmIoUzAIHRTdY
Os4qjovrkFAwn2HmVOSXrGuqa+gN/4NBMl35q0IVD/TuCmHnA8IiahBADaJEB0iT2O1wOBA3QkcN
HAXFxcUVFRUIBwYIXPZoRp0ZhTUEl4j7iFvL1oJELf3XKU4qgJbswfNGDRmLBB+JP8aBZCY38zNT
gio6O4BI1jdQa+nghCArNYgECfQIsa6uDh0gdBG9/HFQWVkZqwdsHFWRyxodyOMGtKMhwhrcGocw
MCqZqwlpnA9i9XOlbz8VBciMYqYW0YTJkYil0M+UZlg4YVhcDUgTyvDT+vp6ZI3Jl2ugDI60IAg0
hAWlvWwB4gpAVGJOxTl4BPdK8w+Ru3UyfSoQoQNxHaD+Nmr6z6PmasIU4wRIX1zB7gfkQuRFLBFY
nKMA+wIN2OsfHLJSA+4pLy3UDzTCttOJ4CSAMmHYbYebDRY+FUj6yxKE9BcXIeLhJZZ3TCNNH0YN
Qky9Q6nBaGKuMowOpKAJyVNNeooCQqNBlgXx2Aq/vs4eWQUlAH3IWl7eAeq5U6z0oCF1yYbRK5mY
BEBmS/RtICphNqj3OqvcNZWuqmpnpdNZ5XPWeWuqvI5at9Phcjk8boejvtrtcaoJweUOuDxtPn+T
z9MQ8NAl1tOGB3R2SvyZnJyMfwCfcZaKbNXwAUFrQcE61y2oGS+R9yUkZuoFRns74hNfaXD7Wut8
jU6fx6nk7airdzvc7hq3z9ngcTY56wNut7fBE2hyNza6GwPeRl+gyafsv6GjramvuxO3pls6R+tp
49pPTzBOshrsEEEIrKZp0EJglNV7/+BgS2+3v6PF39bo9jtdjgp/fWWgvtLtLHd4Kmv9lTWB4wF3
ta+4qLHkeLfHPzw4ODAeHImMT0Yn9RZmCzKQmqlWwrNzxElTQ5acz72NND6qNmlPpXp62l5/69gt
2/dsuDJv07ajG7cc2byFY/4Fm49cvOXgld+qffiJwYKSVHginozHlMD1FutpBejOZpCx8YRhFXmD
gUgEWOc20Mg0Gk6mIolkcDzU2drYUFXeVFTYcPSw9/BB15GD7ryDjQWHO2uP9wZcw32d4fCY2uCR
0rvdE6pIt+qfaUjPJxerUQ0G9ohh5EWFsD40NNze0dHUrH5PRV7eMqvT0tXd1dffp9b6gwOjQfUD
QfRgPaiFL/XVhlWqhjR5KfnpFmTK7Mo0K9ltg/5lG/0GVv3haVdXFzk0uS9a4J5QKCQrYSA9AOlw
tWGVqgHBYciSz3BqxEcjIkboKIB0Fh2QQeEHtHDs0D/yLGpgrU5GhBp4lt7k8VWL1esN8pFVvmyT
vyJQVhXIF6s30k8Dl0QTogYeR22mQyD1VYjV6w2sHpC7LLCJNiy4EDGBiIU0S2g0QVwCSF+cg9V1
dXV1TU0NCz3uRxOyPljlChCsXm8wAR0/QKDydtbhcFRUVFRVVSF6tIIacAt0wCmip84NpaWlxCuU
x4IcRZoOpbI6sdqnaOYGVm3IFMl6vV4ETbQhXhGs5KUvgai/v18yIhwI75FXT2jlw6C0MmBKQJRY
PfbOEdHLGwiOWDqCxhvQkEQe9apL/ze8+/r66urq8A9UIuqhK62FD9WwRCAyhIvhM98ChC6pp1wS
q0cNOATxyrRzpE4g8ng8RDCmB9GQ6nF1YzWqAcHhB7LjT5JOaUfEXAJ4g3yuEP+QR+TIPdwvqwc8
SZ6Vq6sZy1cDnMXnL7FUMgL7mGk8lYyrXwegMRVTPzCg/rscqWRM/QKWuoopc0nZs3oymUqoB9Tf
+Ki/clKV9MJj3M2aWCtF06JATQrPJGPJcCI+mZyKsNJIRSbpdEr9sIFWyJwOsy3qf9NFGqjJ0NSs
+vKQmzcwtJ1OKZpQRScCVnKMRVORidTkVCqmftZBixY1xJOxuP5vj6TiXFFv3lALgT+aTLDikk4y
l5mqus80S2FIukslYuo3JBL6Pw8bS06hUzWuGn7W3UspaFD9aZENWgQKaafLQM5qmK9gsMQDZYD8
n5qSvLZ3Za2YpvolDnUjtRhzsTrqn9SYSiifSevMXrSDqaJdYm7hf9ZAyiXlZzi4VdkElDCC/eYl
FPpQz88e2oJlD8tHLmpQv2yijDqpYo0KN9od9M92xLG7aDKlX4XGpuJTsVh8Kq5/zCMWTU6MJkMD
yYmx5FgoNT4en4pE1E/QIDxlUhQltEWKYtriW2pS9NjRuJZ8LJKIhhLR4WRkODU+nIiECFHKnmc6
WXIR8qTI8Ln7gUIq9f8ubjDHxZRQYwAAAABJRU5ErkJgglBLAQItABQABgAIAAAAIQBamK3CDAEA
ABgCAAATAAAAAAAAAAAAAAAAAAAAAABbQ29udGVudF9UeXBlc10ueG1sUEsBAi0AFAAGAAgAAAAh
AAjDGKTUAAAAkwEAAAsAAAAAAAAAAAAAAAAAPQEAAF9yZWxzLy5yZWxzUEsBAi0AFAAGAAgAAAAh
AHKGIcwBAgAA2QQAABIAAAAAAAAAAAAAAAAAOgIAAGRycy9waWN0dXJleG1sLnhtbFBLAQItABQA
BgAIAAAAIQCqJg6+vAAAACEBAAAdAAAAAAAAAAAAAAAAAGsEAABkcnMvX3JlbHMvcGljdHVyZXht
bC54bWwucmVsc1BLAQItABQABgAIAAAAIQDnceuxEQEAAIIBAAAPAAAAAAAAAAAAAAAAAGIFAABk
cnMvZG93bnJldi54bWxQSwECLQAKAAAAAAAAACEA0y0JkbkgAAC5IAAAFAAAAAAAAAAAAAAAAACg
BgAAZHJzL21lZGlhL2ltYWdlMS5wbmdQSwUGAAAAAAYABgCEAQAAiycAAAAA
">
   <v:imagedata src="preview.files/sample_26514_image001.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><v:shape id="图片_x0020_5" o:spid="_x0000_s1037" type="#_x0000_t75"
   style='position:absolute;margin-left:10.8pt;margin-top:0;width:62.4pt;
   height:44.4pt;z-index:4;visibility:visible' o:gfxdata="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
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
   <v:imagedata src="preview.files/sample_26514_image002.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><![endif]-->
					<![if !vml]><span style='mso-ignore:vglayout;
  position:absolute;z-index:2;margin-left:14px;margin-top:1px;width:90px;
  height:59px'><img width=90 height=59 src="preview.files/sample_26514_image003.gif" v:shapes="图片_x0020_1 图片_x0020_5"></span>
					<![endif]><span style='mso-ignore:vglayout2'>
						<table cellpadding=0 cellspacing=0>
							<tr>
								<td colspan=2 height=50 class=xl6826514 width=97 style='height:37.95pt;
    width:72pt'></td>
							</tr>
						</table>
					</span></td>
				<td colspan=13 class=xl6926514 width=382 style='width:287pt'>京东家电专卖店<font class="font526514"><span style='mso-spacerun:yes'>&nbsp; </span></font>
					<font class="font626514">商品销售单</font>
				</td>
				<td colspan=5 class=xl7026514 width=106 style='width:80pt'>4858</td>
				<td class=xl6326514 width=32 style='width:24pt'></td>
			</tr>
			<tr class=xl6426514 height=45 style='mso-height-source:userset;height:33.6pt'>
				<td colspan=15 height=45 class=xl7126514 style='height:33.6pt'>沅江市三眼塘店
					JD29789<span style='mso-spacerun:yes'>&nbsp;&nbsp; </span>电话: 0737-2982123
					18907376948</td>
				<td colspan=5 class=xl7226514>店长 刘建康</td>
				<td class=xl6326514></td>
			</tr>
			<tr class=xl6426514 height=43 style='mso-height-source:userset;height:32.4pt'>
				<td height=43 class=xl6526514 style='height:32.4pt'>客户</td>
				<td class=xl6526514><?php echo $username ?></td>
				<td colspan=3 class=xl7326514><?php echo $address ?></td>
				<td colspan=4 class=xl7426514><?php echo $phone ?></td>
				<td colspan=4 class=xl7426514>销售日期：</td>
				<td colspan=7 class=xl7526514><span style='mso-spacerun:yes'>&nbsp;
					</span><?php  	//echo var_dump(getdate()); 
									echo getdate()["year"]."年".getdate()["month"]."月".getdate()["mday"]."日" ?><span style='mso-spacerun:yes'>&nbsp;&nbsp;</span></td>
				<td class=xl6326514></td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 rowspan=2 height=65 class=xl7626514 style='border-right:.5pt solid black;
  border-bottom:.5pt solid black;height:48.6pt'>商<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp; </span>品<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp; </span>名<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp; </span>称</td>
				<td rowspan=2 class=xl8026514 style='border-bottom:.5pt solid black;
  border-top:none'>数量</td>
				<td rowspan=2 class=xl8026514 style='border-bottom:.5pt solid black;
  border-top:none'>单位</td>
				<td rowspan=2 class=xl8026514 style='border-bottom:.5pt solid black;
  border-top:none'>单价</td>
				<td colspan=5 class=xl8326514 style='border-right:.5pt solid black;
  border-left:none'>金额</td>
				<td colspan=7 rowspan=2 class=xl7626514 style='border-right:.5pt solid black;
  border-bottom:.5pt solid black'>备注</td>
				<td rowspan=8 height=275 class=xl6326514 width=32 style='mso-ignore:colspan-rowspan;
  height:207.0pt;width:24pt'>
					<!--[if gte vml 1]><v:shape id="图片_x0020_3"
   o:spid="_x0000_s1034" type="#_x0000_t75" style='position:absolute;
   margin-left:.6pt;margin-top:4.8pt;width:16.8pt;height:195.6pt;z-index:1;
   visibility:visible' o:gfxdata="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
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
   <v:imagedata src="preview.files/sample_26514_image004.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><v:shape id="图片_x0020_4" o:spid="_x0000_s1036" type="#_x0000_t75"
   style='position:absolute;margin-left:.6pt;margin-top:4.8pt;width:16.8pt;
   height:195.6pt;z-index:3;visibility:visible' o:gfxdata="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
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
   <v:imagedata src="preview.files/sample_26514_image004.png" o:title=""/>
   <x:ClientData ObjectType="Pict">
    <x:SizeWithCells/>
    <x:CF>Bitmap</x:CF>
    <x:AutoPict/>
   </x:ClientData>
  </v:shape><![endif]-->
					<![if !vml]><span style='mso-ignore:vglayout'>
						<table cellpadding=0 cellspacing=0>
							<tr>
								<td width=1 height=6></td>
							</tr>
							<tr>
								<td></td>
								<td><img width=22 height=261 src="preview.files/sample_26514_image005.gif" v:shapes="图片_x0020_3 图片_x0020_4"></td>
								<td width=17></td>
							</tr>
							<tr>
								<td height=78></td>
							</tr>
						</table>
					</span>
					<![endif]>
					<!--[if !mso & vml]><span style='width:24.0pt;height:207.0pt'></span><![endif]-->
				</td>
			</tr>
			<tr class=xl6426514 height=30 style='mso-height-source:userset;height:22.2pt'>
				<td height=30 class=xl6626514 style='height:22.2pt'>万</td>
				<td class=xl6626514>千</td>
				<td class=xl6626514>百</td>
				<td class=xl6626514>十</td>
				<td class=xl6626514>元</td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl8326514 style='border-right:.5pt solid black;
  height:26.4pt'> <?php echo $pdata['mydata1']['product'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata1']['nums'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata1']['unit'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata1']['price'] ?> </td>
				<td class=xl6626514>3　</td>
				<td class=xl6626514>4　</td>
				<td class=xl6626514>5　</td>
				<td class=xl6626514>6　</td>
				<td class=xl6626514>7　</td>
				<td colspan=7 rowspan=3 class=xl8526514 width=157 style='border-right:.5pt solid black;
  width:119pt'> <?php echo $pdata['mydata1']['memo'] ?> </td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl8326514 style='border-right:.5pt solid black;
  height:26.4pt'><?php echo $pdata['mydata2']['product'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata2']['nums'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata2']['unit'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata2']['price'] ?> </td>
				<td class=xl6626514>3　</td>
				<td class=xl6626514>4　</td>
				<td class=xl6626514>5　</td>
				<td class=xl6626514>6　</td>
				<td class=xl6626514>7　</td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl8326514 style='border-right:.5pt solid black;
  height:26.4pt'><?php echo $pdata['mydata3']['product'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata3']['nums'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata3']['unit'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata3']['price'] ?> </td>
				<td class=xl6626514>3　</td>
				<td class=xl6626514>4　</td>
				<td class=xl6626514>5　</td>
				<td class=xl6626514>6　</td>
				<td class=xl6626514>7　</td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl8326514 style='border-right:.5pt solid black;
  height:26.4pt'><?php echo $pdata['mydata4']['product'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata4']['nums'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata4']['unit'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata4']['price'] ?> </td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td colspan=7 rowspan=3 class=xl9126514 width=157 style='border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:119pt'>京东官方授权、正品低价、售后无忧。全品类、全品牌应有尽有，全屋家电一站式购齐。</td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=5 height=35 class=xl8326514 style='border-right:.5pt solid black;
  height:26.4pt'><?php echo $pdata['mydata5']['product'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata5']['nums'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata5']['unit'] ?> </td>
				<td class=xl6626514><?php echo $pdata['mydata5']['price'] ?> </td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
				<td class=xl6626514>　</td>
			</tr>
			<tr class=xl6426514 height=35 style='mso-height-source:userset;height:26.4pt'>
				<td colspan=8 height=35 class=xl9526514 style='height:26.4pt'>合计金额<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>万<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>仟<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>佰<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>十<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</span>元<span style='mso-spacerun:yes'>&nbsp;&nbsp;</span></td>
				<td class=xl6726514>&yen;</td>
				<td colspan=4 class=xl9726514 style='border-right:.5pt solid black'><?php echo $totals_money ?></td>
			</tr>
			<tr class=xl6426514 height=32 style='mso-height-source:userset;height:24.0pt'>
				<td colspan=20 height=32 class=xl9926514 style='height:24.0pt'>专业、专注、专营：京东所有品牌大小家电，网购代下单、样机体验、实体店购物，价格全网最低</td>
				<td class=xl6326514></td>
			</tr>
			<tr class=xl6426514 height=21 style='mso-height-source:userset;height:15.6pt'>
				<td colspan=20 height=21 class=xl9926514 style='height:15.6pt'>注：此单为保修凭证，敬请保存。自然灾害，人为损坏，雷击鼠咬，非正常使用之商品不在保修范围</td>
				<td class=xl6326514>
					<!----------------------------->
					<!--“从 EXCEL 发布网页”向导结束-->
					<!----------------------------->
				</td>
			</tr>
			<![if supportMisalignedColumns]>
			<tr height=0 style='display:none'>
				<td width=43 style='width:32pt'></td>
				<td width=54 style='width:40pt'></td>
				<td width=43 style='width:32pt'></td>
				<td width=43 style='width:32pt'></td>
				<td width=26 style='width:20pt'></td>
				<td width=32 style='width:24pt'></td>
				<td width=32 style='width:24pt'></td>
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
				<td width=25 style='width:19pt'></td>
				<td width=21 style='width:16pt'></td>
				<td width=32 style='width:24pt'></td>
			</tr>
			<![endif]>
		</table>

		<!--endprint-->
		<br /><a href="../controller/action.php?flag=print">《远程打印》</a>
		<a href="../controller/input.php">《返 回》</a>
		<INPUT onclick=javascript:doPrint() type=button value=本地打印 name=button_print />

	</div>
	<!----------------------------->
	<!--“从 EXCEL 发布网页”向导结束-->
	<!----------------------------->
</body>

</html>