    <?php

    include "sessionid.php";
    include '../model/Conn.php';

    $username = @$_GET['username'] ? $_GET['username'] : "";      //这里处理查询页面请求的

    $sql = "update clients set print=5686 where username= \"$username\" ";

    if ($mysqli->query($sql) === TRUE) {
        echo $sql . "<br>" . "OK, 可以重新开票了 ";
        $_SESSION['username'] = $username;
        echo $username;
    } else {
        echo $sql . "<br>" . "错误出现 :" . $mysqli->error . "<br>";
        echo "好像没办法重新开票额？";
    }
    $mysqli->close();
    ?>
