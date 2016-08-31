<?php

function getParameter($pname){
    return isset($_POST[$pname])?$_POST[$pname]:"";
}
$sn_id = iconv("Big5","UTF-8",$_GET['sn_id']);
$order_id = iconv("Big5","UTF-8",$_GET['order_id']);
$st_cate = iconv("Big5","UTF-8",$_GET['st_cate']);
$st_code = iconv("Big5","UTF-8",$_GET['st_code']);
$st_name = iconv("Big5","UTF-8",$_GET['st_name']);
$st_addr = iconv("Big5","UTF-8",$_GET['st_addr']);
$st_tel = iconv("Big5","UTF-8",$_GET['st_tel']);
$webtemp = iconv("Big5","UTF-8",$_GET['webtemp']);

$Code = "z9wwp2fm";

function forward($url) {
    // header("HTTP/1.1 301 Moved Permanently");
    // header("Location: $url");
    echo "window.location.href='$url';";
}

function success() {
    forward("/#!/payment/success");
}

function error() {
    forward("/#!/payment/error");
}
?>

<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript">

        function go() {
            <?php
                 if($sn_id!="00000000"){
                      //-- 交易成功
                      if(strlen($P_CheckSum) > 0) {

                           $checkstr = md5($P_MerchantNumber.$P_OrderNumber.$final_result.$final_return_PRC.$Code.$final_return_SRC.$P_Amount);

                           if(strtolower($checkstr) != strtolower($P_CheckSum)) {
                                error();
                           }
                           else {
                                success();
                           }
                      }
                      else {
                              success();
                      }
                 }
                 else {
                    error();
                 }
            ?>
        }

    </script>
</head>
<body onload="go()">
</body>
</html>
