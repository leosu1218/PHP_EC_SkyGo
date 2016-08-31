<?php
require_once(dirname(__FILE__) . "/../../phpunit/DbHero_TestCase.php");
require_once( FRAMEWORK_PATH . "extends/ExporterMan/entitys/GernalReturnedSalesEntity.php" );


/**
 * Class GernalReturnedSalesEntityTest
 */
class GernalReturnedSalesEntityTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable("wholesale_product", dirname(__FILE__)."/GernalReturnedSalesEntity/wholesale_product.csv");
        $dataSet->addTable("consumer_user", dirname(__FILE__)."/GernalReturnedSalesEntity/consumer_user.csv");
        $dataSet->addTable("general_activity", dirname(__FILE__)."/GernalReturnedSalesEntity/general_activity.csv");
        $dataSet->addTable("unified_order", dirname(__FILE__)."/GernalReturnedSalesEntity/unified_order.csv");
        $dataSet->addTable("unified_returned", dirname(__FILE__)."/GernalReturnedSalesEntity/unified_returned.csv");
        return $dataSet;
    }

    /**
     * Test UnifiedOrderCollection::preparePushDataWaitting that's excel export can get.
     * @dataProvider gernalReturnedSalesEntityProvider
     */
    public function testGernalReturnedSalesEntity($describe, $params, $expected) {
        echo "$describe \n\r ";
        $entity = new GernalReturnedSalesEntity();
        $entity->setResource( $params );
        $records = $entity->getRecords();
        $this->assertEquals($expected, $records);
    }

    public function gernalReturnedSalesEntityProvider() {
        return array(
            array(
                //Describe
                "Test Genrnal Returned Sales Entity Prepare data output.",
                // params
                array(20),
                // expected
                json_decode('[{
                     "fields": ["id", "activity_id", "activity_type", "consumer_user_id", "buyer_name", "buyer_phone_number", "buyer_email", "product_total_price", "final_total_price", "other_cost", "cost_type", "fare", "fare_id", "fare_type", "discount", "discount_type", "payment_type", "receiver_address", "receiver_name", "receiver_phone_number", "state", "create_datetime", "pay_notify_datetime", "serial", "delivery_datetime", "delivery_channel", "delivery_number", "close_datetime", "ur_id", "return_state", "ur_receiver_name", "ur_receiver_phone_number", "ur_receiver_address", "ur_create_datetime", "ur_close_datetime", "ur_delivery_datetime", "ur_delivery_channel", "ur_delivery_number", "user_name", "user_account", "user_email", "stateText"],
                     "records": [{
                         "id": "20",
                         "activity_id": "3",
                         "activity_type": "reorder_groupbuying",
                         "consumer_user_id": "1",
                         "buyer_name": "Rex",
                         "buyer_phone_number": "0972831678 ",
                         "buyer_email": "chen.cyr@gmail.com",
                         "product_total_price": "26400",
                         "final_total_price": "26400",
                         "other_cost": "0",
                         "cost_type": "normal",
                         "fare": "0",
                         "fare_id": "3",
                         "fare_type": "\u5b85\u914d",
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
                         "ur_id": "2",
                         "return_state": "16",
                         "ur_receiver_name": "Rex",
                         "ur_receiver_phone_number": "0972831678 ",
                         "ur_receiver_address": "\u53f0\u5317\u5e02\u4fe1\u7fa9\u5340\u5b89\u548c\u8def\u4e00\u6bb589\u865f",
                         "ur_create_datetime": "2015-10-15 03:31:15",
                         "ur_close_datetime": "2015-10-19 06:47:39",
                         "ur_delivery_datetime": "2015-10-15 03:31:15",
                         "ur_delivery_channel": "\u4fbf\u5229\u5e36",
                         "ur_delivery_number": "RE00981",
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