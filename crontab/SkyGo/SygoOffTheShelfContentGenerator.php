<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( CRONTAB_PATH . 'NotifyContentGenerator.php' );



class SygoOffTheShelfContentGenerator implements NotifyContentGenerator {

    public function __construct($items) {
        $this->items = $items;
    }

    public function create() {
        $productItems = $this->items;
        $text = "";
        //print_r($productItems);
        foreach($productItems as $productItem ){
            $text .= "你的" . $productItem['name'] ."(" .  $productItem['id'] . ")" . "商品即將在" . $productItem['ready_time'] . "下架<br>";
        }
        return $text ;

    }

}
?>