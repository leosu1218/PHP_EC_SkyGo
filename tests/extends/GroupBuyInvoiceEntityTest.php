<?php
require_once(dirname(__FILE__) . "/../../phpunit/DbHero_TestCase.php");
require_once( FRAMEWORK_PATH . "extends/ExporterMan/entitys/GroupBuyInvoiceEntity.php" );


/**
 * Class FareCollectionTest
 */
class GroupBuyInvoiceEntityTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable("wholesale_product", dirname(__FILE__)."/GroupBuyInvoiceEntity/wholesale_product.csv");
        $dataSet->addTable("groupbuying_activity", dirname(__FILE__)."/GroupBuyInvoiceEntity/groupbuying_activity.csv");
        $dataSet->addTable("consumer_user", dirname(__FILE__)."/GroupBuyInvoiceEntity/consumer_user.csv");
        $dataSet->addTable("wholesale_product_spec", dirname(__FILE__)."/GroupBuyInvoiceEntity/wholesale_product_spec.csv");
        $dataSet->addTable("unified_order", dirname(__FILE__)."/GroupBuyInvoiceEntity/unified_order.csv");
        $dataSet->addTable("order_has_spec", dirname(__FILE__)."/GroupBuyInvoiceEntity/order_has_spec.csv");
        return $dataSet;
    }

    /**
     * Test UnifiedOrderCollection::preparePushDataWaitting that's excel export can get.
     * @dataProvider gernalPickupEntityProvider
     */
    public function testGernalPickupEntity($describe, $params, $expected) {
        echo "$describe \n\r ";
        $entity = new GroupBuyInvoiceEntity();
        $entity->setResource( $params );
        $records = $entity->getRecords();
        $this->assertEquals($expected, $records);
    }

    public function gernalPickupEntityProvider() {
        return array(
            array(
                //Describe
                "Test Genrnal Pickup Entity Prepare data output.",
                // params
                array(2),
                // expected
                json_decode('  [{
                     "fields": ["id", "activity_id", "activity_type", "consumer_user_id", "buyer_name", "buyer_phone_number", "buyer_email", "product_total_price", "final_total_price", "other_cost", "cost_type", "fare", "fare_type", "fare_id", "discount", "discount_type", "payment_type", "receiver_address", "receiver_name", "receiver_phone_number", "state", "create_datetime", "pay_notify_datetime", "serial", "delivery_datetime", "delivery_channel", "delivery_number", "close_datetime", "inventory_process", "master_id", "activity_name", "master_name", "master_bank_name", "master_bank_code", "master_bank_account", "master_bank_account_name", "order_id", "spec_id", "spec_amount", "spec_unit_price", "spec_total_price", "spec_fare", "spec_fare_type", "spec_other_cost", "spec_cost_type", "spec_discount", "spec_discount_type", "spec_activity_type", "spec_activity_id", "spec_name", "spec_product_id", "spec_serial", "product_id", "product_name", "weight", "user_name", "user_account", "user_email", "stateText"],
                     "records": [{
                         "id": "20",
                         "activity_id": "2",
                         "activity_type": "groupbuying",
                         "consumer_user_id": "1",
                         "buyer_name": "Rex",
                         "buyer_phone_number": "0972831678 ",
                         "buyer_email": "chen.cyr@gmail.com",
                         "product_total_price": "26400",
                         "final_total_price": "26400",
                         "other_cost": "0",
                         "cost_type": "normal",
                         "fare": "0",
                         "fare_type": "\u5b85\u914d",
                         "fare_id": "3",
                         "discount": "0",
                         "discount_type": "normal",
                         "payment_type": "0",
                         "receiver_address": "\u53f0\u5317\u5e02\u4fe1\u7fa9\u5340\u5b89\u548c\u8def\u4e00\u6bb589\u865f",
                         "receiver_name": "Rex",
                         "receiver_phone_number": "0972831678 ",
                         "state": "4",
                         "create_datetime": "2015-10-19 06:47:39",
                         "pay_notify_datetime": "2015-10-19 06:47:39",
                         "serial": "20151019OPewB",
                         "delivery_datetime": "2015-09-21 20:14:08",
                         "delivery_channel": "\u4fbf\u5229\u5e36",
                         "delivery_number": "GH11563",
                         "close_datetime": "0000-00-00 00:00:00",
                         "inventory_process": "1",
                         "master_id": "1",
                         "activity_name": "IPHONE\u71b1\u8ce3\u6703",
                         "master_name": "\u9673\u5955\u745e",
                         "master_bank_name": "\u7b2c\u4e00\u9280\u884c",
                         "master_bank_code": "006 ",
                         "master_bank_account": "0796664431222 ",
                         "master_bank_account_name": "\u9673\u5955\u745e",
                         "order_id": "20",
                         "spec_id": "1",
                         "spec_amount": "2",
                         "spec_unit_price": "8800",
                         "spec_total_price": "8800",
                         "spec_fare": "0",
                         "spec_fare_type": "\u5b85\u914d",
                         "spec_other_cost": "0",
                         "spec_cost_type": "normal",
                         "spec_discount": "0",
                         "spec_discount_type": "normal",
                         "spec_activity_type": "groupbuying",
                         "spec_activity_id": "2",
                         "spec_name": "32G \u73ab\u7470\u91d1",
                         "spec_product_id": "2",
                         "spec_serial": "RX2245",
                         "product_id": "2",
                         "product_name": "iPhone 6S",
                         "weight": "0",
                         "user_name": "groupbuying",
                         "user_account": "groupbuying@109life.com",
                         "user_email": "groupbuying@109life.com",
                         "stateText": "completed"
                     }]
                 }]',true)
            )
        );
    }


}

?>