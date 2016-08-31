<?php
/**
*  ProductGroupController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );


class ProductGroupController extends RestController {

    public function __construct() {
        parent::__construct();                
    }

    /**
    *   Get condition for search product method from http request querysting.
    *   There will filte querystring key, values.
    *
    *   @return 
    */
    public function getCondition() {
        $condition = array();
        $this->getQueryString("keyword", $condition);
        $this->getQueryString("parentId", $condition);
        return $condition;
    }

    /**
     * GET:     /group/product/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search product's group by some params from query string.
     *
     * @param $pageNo int The search result's page number.
     * @param $pageSize int The search result's page size.
     * @param $querystring string
     */
    public function search( $channel, $type, $pageNo, $pageSize, $querystring = array())
    {
        $data = array();

        try {

            $collection = new ProductGroupCollection();

            $user = PlatformUser::instanceBySession();
            $collection->setActor($user);

            $condition = array();
            if( !empty($querystring) )
            {
                $condition = $this->getCondition();
            }

            $data = $collection->search( $channel, $type, $pageNo, $pageSize, $condition);
            $this->responser->send( $data , $this->responser->OK() );
            
        }      
        catch(AuthorizationException $e) {
          $data['message'] = $e->getMessage();
          $this->responser->send( $data, $this->responser->Forbidden() );         
        }
        catch ( Exception $e) {
          // $data['message'] = SERVER_ERROR_MSG;
          $data['message'] = $e->getMessage();
          $this->responser->send( $data, $this->responser->InternalServerError() );
        }
    }

    /**
    *   GET:  /group/product/list/<channel:\w+>/<type:\w+>/<pageNo:\d+>/<pageSize:\d+>
    *   Get product list of you wanted type.
    *
    *   @param $channel         string which channel you wanted ( ex. 'wholesale' / 'retail' )
    *   @param $channelType     string which node type you wanted ( ex. 'sub' / 'product' )
    *   @param $pageNo          number
    *   @param $pageSize        number 
    *
    *   @return  array Struct of array(
    *            "totalPage" => 1,
    *            "pageNo" => 1,
    *            "pageSize" => 10,
    *            "records" => array(
    *                    "0" => array(
    *                            "id"=>1,
    *                            "name"=>"wholesale_root",
    *                            "parent_group_id"=>0,
    *                            "type"=>1
    *                        )
    *                )
    *        )
    */
    public function getList( $channel, $groupType, $pageNo, $pageSize ) {
      
        $data = array();

        try {

            $collection = new ProductGroupCollection();

            $user = PlatformUser::instanceBySession();
            $collection->setActor($user);

            $data = $collection->getNodeRecords( $channel, $groupType, $pageNo, $pageSize );
            $this->responser->send( $data , $this->responser->OK() );          
        }      
        catch(AuthorizationException $e) {
          $data['message'] = $e->getMessage();
          $this->responser->send( $data, $this->responser->Forbidden() );         
        }
        catch ( Exception $e) {
          // $data['message'] = SERVER_ERROR_MSG;
          $data['message'] = $e->getMessage();
          $this->responser->send( $data, $this->responser->InternalServerError() );
        }

    }

    /**
    * POST: /group/product/create/<channel:\w+>
    *   create a product group.
    *   
    *   @param string $channel string which channel you wanted ( ex. 'wholesale' / 'retail' )
    *
    *   @param string $this->receiver['name']
    *   @param number $this->receiver['parent_group_id']
    *   @param string $this->receiver['type']
    *   
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    public function create( $channel ) {      
        $data = array();

        $this->vaildateFiled( array( "name", "parent_group_id", "type" ) );
        $collection = new ProductGroupCollection();

        $user = PlatformUser::instanceBySession();
        $collection->setActor($user);
        
        $options = array();
        $options["channel"]            = $channel;
        $options["name"]               = $this->receiver["name"];
        $options["parent_group_id"]    = $this->receiver["parent_group_id"];
        $options["type"]               = $this->receiver["type"];
        
        $config = $collection->getCreateConfig( $options );
        $result["effectRow"] = $collection->productGroupCreate( $config );            
        $result["id"] = $collection->lastCreated()->getId();
        
        return $result;

    }

    /**
    * DELETE: /group/product/<channel:\w+>/<id:\d+>
    *   delete a product group.
    *   
    *   @param number $id string which product group you wanted ( ex. 5 or 6 or 7... )
    *
    *   @param string $this->receiver['name']
    *   
    *   @return  number ( is success delete in db, ex. true or false )
    */
    public function remove( $channel, $id ) {
      
        $data = array();

        try {

            $collection = new ProductGroupCollection();

            $user = PlatformUser::instanceBySession();
            $collection->setActor($user);

            $options = array(
                    "id" => $id,
                    "channel" => $channel
                );

            $result = $collection->productGroupRemove( $options );

            $this->responser->send( $result , $this->responser->OK() );
          
        }      
        catch(AuthorizationException $e) {
          $data['message'] = $e->getMessage();
          $this->responser->send( $data, $this->responser->Forbidden() );         
        }
        catch ( Exception $e) {
          // $data['message'] = SERVER_ERROR_MSG;
          $data['message'] = $e->getMessage();
          $this->responser->send( $data, $this->responser->InternalServerError() );
        }

    }

    /**
    *   PUT:    /group/product/<channel:\w+>/<id:\d+>
    *   
    *   @param string $channel
    *   @param int $id
    *   @param array $data update that's you wanted.
    */
    public function update( $channel, $id ){
        $collection = new ProductGroupCollection();

        $user = PlatformUser::instanceBySession();

        $model = $collection->getById($id)->setActor($user);
        $isSuccess = $model->updateWithPermission( $this->params('data'), $channel );
        return array("isSuccess"=>$isSuccess);
    }

    /**
    *   vaildate you needed fileds
    *   
    *   @param array $vaildateFileds (ex. array( "name", "parent_group_id", "type" ) )
    */
    public function vaildateFiled( $vaildateFileds ){

        foreach ($vaildateFileds as $key => $value) {
            
            if(!array_key_exists($value, $this->receiver)) {
                $this->responser->send(array("message" => "Missing parameter [".$value."]."), $this->responser->BadRequest());
            }

        }

    }

}




?>