<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( CRONTAB_PATH . 'SkyGO/SkygoSystemReceiverList.php' );
require_once( CRONTAB_PATH . 'SkyGO/SygoLowInventoryItemList.php' );
require_once( CRONTAB_PATH . 'SkyGO/SygoLowInventoryContentGenerator.php' );
require_once( CRONTAB_PATH . 'SkyGO/SkygoMailSender.php' );
require_once( CRONTAB_PATH . 'SkyGO/Notify.php' );




$systemList = new SkygoSystemReceiverList();
$productSpecList = new SygoLowInventoryItemList();

$list = $systemList->getNotify(1,30);
$items = $productSpecList->getProductSpec(1,30);
$generator   = new SygoLowInventoryContentGenerator($items['records']);
$sender      = new SkygoMailSender($list['records']);

$subject = "批發商品庫存低於安全庫存";
$notify = new Notify($generator);
$notify->send($sender , $subject);
?>