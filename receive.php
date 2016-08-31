<?php 

     function getParameter($pname){
          return isset($_POST[$pname])?$_POST[$pname]:"";
     }
	$final_result = getParameter('final_result');
	$P_MerchantNumber = getParameter('P_MerchantNumber');
	$P_OrderNumber = getParameter('P_OrderNumber');
	$P_Amount = getParameter('P_Amount');
	$P_CheckSum = getParameter('P_CheckSum');
	$final_return_PRC = getParameter('final_return_PRC');
	$final_return_SRC = getParameter('final_return_SRC');
	$final_return_ApproveCode = getParameter('final_return_ApproveCode');
	$final_return_BankRC = getParameter('final_return_BankRC');
	$final_return_BatchNumber = getParameter('final_return_BatchNumber');
	
	$Code = "z9wwp2fm";

	function forward($url) {
		// header("HTTP/1.1 301 Moved Permanently");
    	// header("Location: $url");
    	echo "window.location.href='$url';";
	}

	function success() {
		forward("/gb.html#!/payment/success");
	}

	function error() {
		forward("/gb.html#!/payment/error");
	}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">

function go() {
<?php
     if($final_result=="1"){
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
          //-- 交易失敗，有可能是交易失敗；有可能是交易成功，但通知商家失敗而做了取消，視為交易失敗
          if($final_return_PRC=="8" && $final_return_SRC=="204") {
               // 交易失敗-->訂單編號重複!
          		error();
          }
          else if($final_return_PRC=="34" && $final_return_SRC=="171") {
               // print "交易失敗-->金融上的失敗!";
               // print "  銀行回傳碼=[".$final_return_BankRC."]<br>";
          		error();
          }
          else {
               	// "交易失敗-->請與商家聯絡!";
          		error();
          }
     }
?>
}

</script>
</head>
<body onload="go()">
</body>
</html>
