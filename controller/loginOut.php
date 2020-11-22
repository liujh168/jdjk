<?php 
	//登出
    session_start();
    unset($_SESSION['uid']);
    session_destroy();
    
    $url = "../index.html";  
    echo "<script type='text/javascript'>";  
    echo "window.location.href='$url'";  
    echo "</script>"; 
 ?>