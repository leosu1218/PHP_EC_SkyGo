<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( CRONTAB_PATH . 'SkyGO/SkygoSystemReceiverList.php' );
require_once( CRONTAB_PATH . 'SkyGO/SygoOffTheShelfItemList.php' );
require_once( CRONTAB_PATH . 'SkyGO/SygoOffTheShelfContentGenerator.php' );
require_once( CRONTAB_PATH . 'SkyGO/SkygoMailSender.php' );
require_once( CRONTAB_PATH . 'SkyGO/Notify.php' );




$systemList = new SkygoSystemReceiverList();
$productList = new SygoOffTheShelfItemList();

$list = $systemList->getNotify(1,30);
$items = $productList->getProduct(7,1,30);
$generator   = new SygoOffTheShelfContentGenerator($items['records']);
$sender      = new SkygoMailSender($list['records']);
$subject = "批發商品下架前7天";
$notify = new Notify($generator);
$notify->send($sender , $subject);

?>