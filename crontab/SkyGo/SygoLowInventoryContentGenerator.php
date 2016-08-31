<?php
require_once( dirname(dirname(__FILE__)) . '/config.php' );
require_once( CRONTAB_PATH . 'NotifyContentGenerator.php' );



class SygoLowInventoryContentGenerator implements NotifyContentGenerator {

    public function __construct($items) {
        $this->items = $items;
    }

    public function create() {
        $productItems = $this->items;
        $text = "";

        foreach($productItems as $productItem ){
            $text .= "你的" . $productItem['name'] ."(" .  $productItem['serial'] . ")(產品ID:" . $productItem['product_id'] . ")商品低於安全庫存" ;
        }
        return $text ;

    }

}
?>