<?php
/**
*  Test Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/DbHero/Db.php' );

class TestController extends RestController {

   	public function __construct() {
      	parent::__construct();      
   	}

   	public function get($args) {      
      	try {
      		$dao = new Db(DB_NAME);

      		$id = $_GET["id"];
      		$dao->select(array('id','name'));
      		$dao->from('permission');
      		$dao->where(array('and', "id='$id'"));
      		print_r($dao->queryAll());

    	}
      	catch ( Exception $e) {         	
         	$data['message'] = $e->getMessage();
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}
}




?>