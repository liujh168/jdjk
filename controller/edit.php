<!--用于删除主页面的某个客户数据 -->

<head>
    <title>
        京东健康电器
    </title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/style.css">

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
        Header("refresh:1;url='../index.html'");
        exit;
    } else {
        $uid = $_SESSION['uid'];     //将会话变量赋给一个变量$myvalue
    }

    $id = @$_GET['id'] ? $_GET['id'] : "";      //这里处理查询页面请求的
    if (!empty($id)) {
        $olddate = $id ;
        include '../model/Conn.php';
        $sql = "select * from clients where date = '" . $id . "'";
        $res = $mysqli->query($sql);
        $attr = mysqli_fetch_assoc($res);
        $res->close();
        $mysqli->close();
    } else {
        $id = @$_POST['mydate'] ? $_POST['mydate'] : "";    //这里处理修改页面请求的
        if (!empty($id)) {
            include '../model/Conn.php';

            $username = $_POST['username'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $sexuality = $_POST['sexuality'];

            $product = $_POST['product'];
            $unit = $_POST['unit'];
            $price = $_POST['price'];
            $nums = $_POST['nums'];

            $print = $_POST['print']=="on"?5686:6666;
            $memo = $_POST['memo'];
            $mydate = $_POST['mydate'];

            $sql = "update clients set username=\"$username\",sexuality=\"$sexuality\",phone=\"$phone\",address=\"$address\",product=\"$product\",unit=\"$unit\",price=\"$price\",nums=\"$nums\", print=\"$print\",memo=\"$memo\" where date=\"$mydate\" limit 1";

            if ($mysqli->query($sql) === TRUE) {
                echo $sql."<br>"."记录修改成功,3秒后自动返回！";
                $mysqli->close();
            } else {
                echo $sql . "<br>"."错误出现 :" . $mysqli->error."<br>";
                $mysqli->close();
                die("error modify !"."<br>");
            }
            echo " <script> location.href='search.php?username=$username'</script> ";
        } else {
            die("POST id empty!");
        }
    }
    ?>

    <script src="https://upcdn.b0.upaiyun.com/libs/jquery/jquery-2.0.2.min.js"> </script>
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

        $(document).ready(function() {
            $("button").click(function() {
                $("#div_test").load("print.txt");
            });
           //$("#div_test").load("print.txt");
           //$("#id_username").load("print.php");
        });
    </script>
</head>

<body style="background-color:lightblue">
    <div id="div_test">
    </div>

    <div id="main">
        <nav>
            <a href="#" id="menuIcon">Ξ</a>
            <ul>
                <li><a href="input.php" target="_blank">客户信息输入</a></li>
                <li><a href="search.php?id=刘建康&phone=07372982123" target="_blank">按姓名或电话查询</a></li>
                <li><a href="loginOut.php" target="_blank">退出系统</a></li>
            </ul>
        </nav>
        <aside>
            <ul>
                <li><a href="input.php" target="_blank">客户信息输入</a></li>
                <li><a href="search.php?id=刘建康&phone=07372982123" target="_blank">按姓名或电话查询</a></li>
                <li><a href="loginOut.php" target="_blank">退出系统</a></li>
                <li> <p> <?php echo "当前用户：$uid" ?> </p> </li>
                <li> <p> <a href="http://www.liujh168.com">  <img width =60% src="../images/xu.jpeg" alt="打个广告哈">
</a> </p> </li>
            </ul>
        </aside>
        <section class="post">
            <article>

                <form action="edit.php" method="post" name="editForm" onsubmit="return CheckPost();">
                    <div class="title">
                        <input type="reset" name="reset" value="重置">
                        <input type="submit" name=modify value="修改">
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
                                名称： <input id=id_username type="text" name="username" value=<?php echo $attr["username"] ?> /><br />
                            </div>
                            <div>
                                电话：<input type="text" name="phone" value=<?php echo $attr["phone"] ?> /><br />
                            </div>
                            <div>
                                地址：<input type="text" name="address" value=<?php echo $attr["address"] ?> /><br />
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
                                名称： <input type="text" name="product" value=<?php echo $attr["product"] ?> /><br />
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
                                单价：<input type="number" name="price" value=<?php echo $attr["price"] ?> /><br />
                            </div>
                            <div>
                                数量：<input type="number" name="nums" value=<?php echo $attr["nums"] ?> /><br />
                            </div>

                            <div>
                                <!--销售日期：<input type="datetime-local" name="mydate" value="<?php echo $attr['date'];?>" /> <br />-->
                                销售日期：<input type="datetime" name="mydate" value="<?php echo $attr['date'];?>" /> <br />
                            </div>
                            <div>
                                <textarea rows="4" cols="25" name="memo" value=<?php echo $attr["memo"] ?>>备注</textarea><br />
                            </div>
                            <div>
                                <input type="checkbox" name="print" checked>还没开票<br><br />
                            </div>

                        </div>
                    </div>
                </form>

                <p> <a href="input.php">《返回信息查询界面》</a> </p>
                <p> <a href="input.php">《返回信息输入界面》</a> </p>

                <p id="subtitle1"><strong>主要功能</strong></p>
                <p>
                    <ul>
                        <li>简单登录及用户权限控制。</li>
                        <li>手机端实现随时随地输入客户销售信息（同时保存数据到服务器）。</li>
                        <li>实现客户信息增加、删除、修改等编辑功能。</li>
                        <li>输入客户名称或电话号码(部分输入也可以)，可以查询相关销售数据。</li>
                        <li>根据当前客户当天销售信息或者查询结果打印票据。</li>
                        <li>服务端数据备份到本地PC。</li>
                    </ul>
                </p>

                <p id="subtitle4"><strong>使用说明</strong></p>
                <p>第一步：本地PC端打开网址http://ljk.liujh168.com。</p>
                <p>第二步：登录系统后,点击右上角菜单下载《打印模板文件》，并在本地电脑上运行该文件。</p>
                <p>第三步：手机或其它远程终端上进入数据输入、查询等相关功能页面，按照提示完成操作。</p>
                <p>第四步：需要打印时，先检查打印机是否连接正常，然后选择菜单功能打印即可（也可先选打印预览功能再打印）。</p>

                <p id="subtitle5"><strong>常见问题</strong></p>
                <p>
                    <ul>
                        <li>手机上点击打印功能菜单后，远程打印机没反应</li>
                        <li>无法输入客户信息</li>
                        <li>查询结果不符合要求</li>
                        <li>其它问题</li>
                    </ul>
                </p>
                <p id="subtitle6"><strong>我的照片</strong></p>
                <!--
                    <img class="illustration" src="../images/beauty.png" title="sample pic" alt="beauty" />
                -->
            </article>
        </section>
        <footer>
            <hr>
            <ul>
                <li><small>Copyright©2020 ICP备123456789 </small></li>
                <li><small><a href="mailto:77156973@qq.com.com">邮件联系</a></small> </li>
            </ul>
        </footer>
    </div>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#menuIcon,nav ul li").click(function() {
                if ($("#menuIcon").is(":visible")) {
                    $("nav ul").toggle(300);
                };
            });
            $(window).resize(function() {
                if (!$("nav ul").is(":visible")) {
                    $('nav ul').attr('style', function(i, style) {
                        return style.replace(/display[^;]+;?/g, '');
                    });
                };
            });
        })
    </script>
</body>

</html>