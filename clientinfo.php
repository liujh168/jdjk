<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>京东健康电器</title>
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
        Header("refresh:1;url='index.html'");
        exit;
    } else {
        $uid = $_SESSION['uid'];     //将会话变量赋给一个变量$myvalue
    }
    ?>
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
            margin-top: 0px;
            margin-left: 5px;
            margin-right: 25px;
            margin-bottom: 0px;
            border: 0px;
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
            background-color: #33b5e5;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }

        .menu li:hover {
            background-color: #0099cc;
        }

        .header ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .header li {
            display: inline;
            float: left;
            padding: 8px;
            margin-bottom: 7px;
            background-color: #33b5e5;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }

        .header li:hover {
            background-color: #0099cc;
        }

        .aside {
            margin-top: 0px;
            margin-left: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            border: 0px;
            padding: 0px;
            background-color: #33b5e5;
            color: #ffffff;
            text-align: left;
            font-size: 80%;
            /*box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);*/
        }

        .footer {
            margin-top: 0px;
            margin-left: 5px;
            margin-right: 25px;
            margin-bottom: 0px;
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

        a:link,
        a:visited {
            display: block;
            width: 120px;
            font-weight: bold;
            color: #FFFFFF;
            background-color: #98bf21;
            text-align: center;
            padding: 4px;
            text-decoration: none;
            text-transform: uppercase;
        }

        a:hover,
        a:active {
            background-color: #7A991A;
        }

        /* For mobile phones: */
        [class*="col-"] {
            width: 100%;
        }

        @media only screen and (min-width: 600px) {

            /* For tablets: */
            .col-m-1 {
                width: 8.33%;
            }

            .col-m-2 {
                width: 16.66%;
            }

            .col-m-3 {
                width: 25%;
            }

            .col-m-4 {
                width: 33.33%;
            }

            .col-m-5 {
                width: 41.66%;
            }

            .col-m-6 {
                width: 49%;
            }

            .col-m-7 {
                width: 58.33%;
            }

            .col-m-8 {
                width: 66.66%;
            }

            .col-m-9 {
                width: 75%;
            }

            .col-m-10 {
                width: 83.33%;
            }

            .col-m-11 {
                width: 91.66%;
            }

            .col-m-12 {
                width: 100%;
            }
        }

        @media only screen and (min-width: 768px) {

            /* For desktop: */
            .col-1 {
                width: 8.33%;
            }

            .col-2 {
                width: 16.66%;
            }

            .col-3 {
                width: 25%;
            }

            .col-4 {
                width: 33.33%;
            }

            .col-5 {
                width: 41.66%;
            }

            .col-6 {
                width: 49%;
            }

            .col-7 {
                width: 58.33%;
            }

            .col-8 {
                width: 66.66%;
            }

            .col-9 {
                width: 75%;
            }

            .col-10 {
                width: 83.33%;
            }

            .col-11 {
                width: 91.66%;
            }

            .col-12 {
                width: 100%;
            }
        }

        @media only screen and (orientation: landscape) {
            body {
                background-color: lightblue;
            }
        }
    </style>
    <script>
        function CheckPost() {
            if (addForm.username.value == "") {
                alert("请填写用户名！");
                addForm.username.focus();
                return false;
            }
            if (addForm.phone.value.length < 6) {
                alert("电话号码不能少于6个字符！");
                addForm.phone.focus();
                return false;
            }
            //if (isNaN(addForm.price.value)||addForm.price.value == 0)
            if (!IsNum(addForm.price.value) || addForm.price.value == null) {
                alert("单价必须输入数字！");
                addForm.price.focus();
                return false;
            }
            //if (isNaN(addForm.nums.value)||addForm.nums.value == 0)
            if (!IsNum(addForm.nums.value) || addForm.nums.value == null) {
                alert("数量必须输入数字！");
                addForm.nums.focus();
                return false;
            }

            return true;
        }

        //判断是否是正整数 
        function IsNum(s) {
            if (s != null) {
                var r, re;
                re = /\d*/i; //\d表示数字,*表示匹配多个数字
                r = s.match(re);
                return (r == s) ? true : false;
            }
            return false;
        }

        var sleep = function(time) {
            var startTime = new Date().getTime() + parseInt(time, 10);
            while (new Date().getTime() < startTime) {}
            //sleep(10); // 延时函数，单位ms
            //document.getElementById("id_info").innerHTML="查询？遵命！";
        };

        function id_print() {
            alert("请准备好打印机，点击确定按钮开始打印!");
            window.location.href = "controller/action.php?flag=print";
        }

        function id_search() {
            window.location.href = "controller/search.php?id=刘建康&phone=07372982123";
        }

        function id_print_preview() {
            window.location.href = "controller/action.php?flag=preview";
        }

        function id_return() {
            window.location.href = "controller/loginOut.php";
            //window.history.back(-1);
        }

        // 身份证号 校验
        function b_idCard() {
            var reg = /\d{17}\w{1}|\d{15}/;
            var c_idCard = document.getElementById("idCard").value;
            var c_span_idCard = document.getElementById("span_idCard");
            if (reg.test(c_idCard)) {
                c_span_idCard.innerHTML = "√";
                document.getElementById("year").value = c_idCard.substr(6, 4); // 自动 获取 年份
                document.getElementById("month").value = c_idCard.substr(10, 2); // 自动 获取 月份
                document.getElementById("day").value = c_idCard.substr(12, 2);
                return true;
            } else {
                c_span_idCard.innerHTML = "身份证格式错误，必须是18位数或者是15位数";
                document.getElementById("year").value = ""; // 自动 获取 年份
                document.getElementById("month").value = ""; // 自动 获取 月份
                document.getElementById("day").value = "";
                return false;
            }
        }
    </script>
</head>

<body>
    <div class="header">
        <button onclick="id_search()">按姓名或电话查询</button>
        <button onclick="id_print()">打印</button>
        <button onclick="id_print_preview()">打印预览</button>
        <button onclick="id_return()">退出系统</button>
    </div>

    <div class="row">
        <div class="col-3 col-m-3 menu">
            <ul>
                <li><a href="controller/search.php?id=刘建康&phone=07372982123" target="_blank">按姓名或电话查询</a></li>
                <li><a href="controller/action.php?flag=print" target="_blank">打印单据</a></li>
                <li><a href="controller/action.php?flag=preview" target="_blank">打印预览</a></li>
                <li><a href="controller/loginOut.php" target="_blank">退出系统</a></li>
                <li><a href="打印模板.xlsm" target="_blank">下载打印模板文件</a></li>
            </ul>
        </div>

        <div class="col-6 col-m-9">

            <form action="controller/saveclient.php" method="post" name="addForm" onsubmit="return CheckPost();">
                <div class="title">
                    <input type="reset" name="button" value="重置">
                    <input type="submit" value="保存">
                </div>

                <div class="main">
                    <!--个人信息-->
                    <div class="BasicInformation">
                        <div class="title">
                            客户个人信息
                        </div>
                    </div>
                    <div class="content clearfix">

                        <div>
                            名称： <input type="text" name="username" placeholder="客户名称" /><br />
                        </div>
                        <div>
                            电话：<input type="text" name="phone" placeholder="0737-2982123" /><br />
                        </div>
                        <div>
                            地址：<input type="text" name="address" placeholder="地址" /><br />
                        </div>

                        <div>
                            性别：<br />
                        </div>
                        <div>
                            <input type="radio" name="sexuality" value="男" checked>男
                        </div>
                        <div>
                            <input type="radio" name="sexuality" value="女">女<br />
                        </div>

                    </div>
                    <!--销售与商品信息-->
                    <div class="BasicInformation">
                        <div class="title">商品信息</div>
                    </div>
                    <div class="content clearfix">

                        <div>
                            名称： <input type="text" name="product" placeholder="客户名称" /><br />
                        </div>
                        <div>
                            单位： <select name="unit">
                                <option value="个">个</option>
                                <option value="台">台</option>
                                <option value="张">张</option>
                                <option value="辆">辆</option>
                                <option value="本">本</option>
                            </select><br />
                        </div>
                        <div>
                            单价：<input type="number" name="price" placeholder="168" /><br />
                        </div>
                        <div>
                            数量：<input type="number" name="nums" placeholder="666" /><br />
                        </div>


                        <div>
                            销售日期：<input name="date" type="date" placeholder="2020-11-8">
                        </div>
                        <div>
                            <textarea name="memo" rows="4" cols="25">备注</textarea><br />
                        </div>
                        <div>
                            <input type="checkbox" name="print" checked>还没开票<br><br />
                        </div>

                    </div>
                </div>
            </form>
            <audio controls>
                <source src="audio/高山流水.mp3" type="audio/mpeg">
                您的浏览器不支持 audio 元素。
            </audio>
        </div>


    </div>
    <div class="footer">
        <p>Copyright©2020 ICP备123456789 </p>
    </div>
</body>

</html>