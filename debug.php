<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>京东健康电器</title>
<style>
    * {
        box-sizing: border-box;
    }
    .row:after {
        content: "";
        clear: both;
        display: block;
    }
    [class*="col-"] {
        float: left;
        padding: 15px;
    }
    html {
        font-family: "Lucida Sans", sans-serif;
    }
    .header {
		margin-top:0px;
		margin-left:5px;
		margin-right:25px;
		margin-bottom:0px;
		border:0px;
        background-color: blue;
        color: yellow;
        padding: 10px;
    }
    .menu {
        text-align: center;
        margin-top: 30px;
        margin-left: 5px;
        margin-right: 0px;
        padding: 5px;
    }
    .menu ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .menu li {
        padding: 8px;
        margin-bottom: 7px;
        background-color :#33b5e5;
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
    .menu li:hover {
        background-color: #0099cc;
    }

	.header ul{
		list-style-type:none;
		margin:0;
		padding:0;
		overflow:hidden;
	}
	.header li{
		display:inline;
		float:left;
        padding: 8px;
        margin-bottom: 7px;
        background-color :#33b5e5;
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
	}	
    .header li:hover {
        background-color: #0099cc;
    }    
	
	.aside {
		margin-top:0px;
		margin-left:0px;
		margin-right:0px;
		margin-bottom:0px;
		border:0px;
        padding: 0px;
        background-color: #33b5e5;
        color: #ffffff;
        text-align: left;
        font-size: 80%;
        /*box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);*/
    }
    .footer {
		margin-top:0px;
		margin-left:5px;
		margin-right:25px;
		margin-bottom:0px;
        background-color: #0099cc;
        color: #ffffff;
        text-align: center;
        font-size: 80%;
        padding: 15px;
    }
	
    img {
        max-width: 100%;
        height: auto;
    }
	video { 
		/*width: 100%;*/
		max-width: 100%; 
		height: auto;
	}
    a:link,a:visited{
		display:block;
		width:120px;
		font-weight:bold;
		color:#FFFFFF;
		background-color:#98bf21;
		text-align:center;
		padding:4px;
		text-decoration:none;
		text-transform:uppercase;
    }
    a:hover,a:active
    {
		background-color:#7A991A;
    }

    /* For mobile phones: */
    [class*="col-"] {
        width: 100%;
    }
    @media only screen and (min-width: 600px) {
        /* For tablets: */
        .col-m-1 {width: 8.33%;}
        .col-m-2 {width: 16.66%;}
        .col-m-3 {width: 25%;}
        .col-m-4 {width: 33.33%;}
        .col-m-5 {width: 41.66%;}
        .col-m-6 {width: 49%;}
        .col-m-7 {width: 58.33%;}
        .col-m-8 {width: 66.66%;}
        .col-m-9 {width: 75%;}
        .col-m-10 {width: 83.33%;}
        .col-m-11 {width: 91.66%;}
        .col-m-12 {width: 100%;}
    }
    @media only screen and (min-width: 768px) {
        /* For desktop: */
        .col-1 {width: 8.33%;}
        .col-2 {width: 16.66%;}
        .col-3 {width: 25%;}
        .col-4 {width: 33.33%;}
        .col-5 {width: 41.66%;}
        .col-6 {width: 49%;}
        .col-7 {width: 58.33%;}
        .col-8 {width: 66.66%;}
        .col-9 {width: 75%;}
        .col-10 {width: 83.33%;}
        .col-11 {width: 91.66%;}
        .col-12 {width: 100%;}
    }
    @media only screen and (orientation: landscape) {
        body {
            background-color: lightblue;
        }
    }	
</style>
</head>
<body>
    <div class="header">
		<form action="controller/login.php" method="post">
			账号：<input type="text" id="id" name="id" placeholder="默认ljk" />
			密码：<input type="password" id="pwd" name="pwd" placeholder="默认133" />
			<input type="submit" name="flag" value="登录"> 
		</form>
    </div>
    <div class="row">
        <div class="col-3 col-m-3 menu">
            <ul>
                <li>首    页</li>
                <li>数据输入</li>
                <li>信息查询</li>
                <li>单据打印</li>
            </ul>
			<video width=100% controls>
				  <source src="images/video1.mp4" type="video/mp4">
				  您的浏览器不支持HTML5视频.
			</video>
		</div>
        
        <div class="col-6 col-m-9">
			<h3>主要功能</h3>
			<p>
				<ul>
					<li>在手机端实现随时随地输入客户销售信息，同时保存数据。</li>
					<li>实现销售信息增加、删除、修改等编辑功能。</li>
					<li>输入客户名称或电话号码，可以查询相关销售数据。</li>
					<li>输入客户名称或电话号码，可以查询相关销售数据。</li>
					<li>根据手机客户端输入或者查询数据打印票据。</li>
				</ul>
			</p>
			<h3>使用说明</h3>
			<p>终端打开网址，登录系统后,点击右上角下载《打印模板文件》，在电脑(需事先连接好打印机)上运行该文件。再在终端上操作，进入数据输入界面，选择菜单功能打印或者打印预览即可。</p>
			<img src="images/jxau.jpeg" width=100% height="345">
        </div>
        
        <div class="col-3 col-m-12">
            <div class="aside">
				<h3>常见问题</h3>
				<p>	<ul>
					<li>打印模板文件没有反应</li>
					<li>无法输入客户信息</li>
					<li>查询出现错误</li>
				</ul></p>				
				<video width=origin controls>
					  <source src="images/video.mp4" type="video/mp4">
					  您的浏览器不支持HTML5视频.
				</video>
				<!--<img src="images/jxau.jpeg" width=100% height="345">-->
            </div>
        </div>
    
    </div>
    <div class="footer">
        <p>Copyright©2020 ICP备123456789 </p>
    </div>
</body>
</html>