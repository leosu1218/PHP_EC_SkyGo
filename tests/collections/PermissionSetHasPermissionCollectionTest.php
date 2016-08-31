<?php
require_once(dirname(__FILE__) . '/../../phpunit/DbHero_TestCase.php');
require_once( FRAMEWORK_PATH . 'collections/PermissionSetHasPermissionCollection.php' );

/**
 * Class FareCollectionTest
 */
class PermissionSetHasPermissionCollectionTest extends DbHero_TestCase {

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('permission_set_has_permission', dirname(__FILE__)."/PermissionSetHasPermissionCollection/permission_set_has_permission.csv");
        return $dataSet;
    }

    /**
     * Test search product group's sub node
     * @dataProvider getPermissionIdsByIdsProvider
     */
    public function testGetPermissionIdsByIds($describe, $params, $expected) {
        echo "$describe \n\r ";
        $collection = new PermissionSetHasPermissionCollection();
        $result = $collection->getPermissionIdsByIds($params);
        $this->assertEquals($expected, $result);
    }

    public function getPermissionIdsByIdsProvider() {
        return array(
            array(
                //Describe
                "Get permission ids records by PermissionSet ids function.",
                // params
                array(1,2),
                // expected
                json_decode('
                    ["1","5","8","1","2","3","4","5","6","7","8","9"]
                ', true)
            ),
        );
    }

}

?>