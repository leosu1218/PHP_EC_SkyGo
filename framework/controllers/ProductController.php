<?php
/**
*  ProductController code.
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
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductCollection.php' );

require_once( FRAMEWORK_PATH . 'collections/WholesaleMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleExplainMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductSpecCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/FareCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/DeliveryProgramCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductHasFareCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/ProductHasDeliveryCollection.php' );


require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );


class ProductController extends RestController {

    public function __construct() {
        parent::__construct();                
    }

    /**
     * Get condition for search product method from http request querysting.
     * There will filte querystring key, values.
     *
     * @return array
     */
    public function getCondition() {
    	$condition = array();
    	$this->getQueryString("keyword", $condition);
    	return $condition;
    }

    /**
     * GET: /product/<category:\w+>/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search records.
     *
     * @param $category string Product's category.(wholesale only now)
     * @param $pageNo int
     * @param $pageSize int
     * @param $querystring string Params of search.
     */
    public function searchByAdmin($category, $pageNo, $pageSize, $querystring) {
    $data = array();

    try {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $collection = new WholesaleProductCollection();
        $records 	= $collection
            ->setActor($actor)
            ->searchRecords($pageNo, $pageSize, $condition);

        $this->responser->send($records , $this->responser->OK());
    }
    catch(DataAccessResultException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->Conflict());
    }
    catch(DbOperationException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->InternalServerError());
    }
    catch(InvalidAccessParamsException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->BadRequest());
    }
    catch(AuthorizationException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->Forbidden() );
    }
    catch(Exception $e) {
        $data['message'] = SERVER_ERROR_MSG;
        $this->responser->send( $data, $this->responser->InternalServerError() );
    }
}

    /**
     *
     * @param $productId
     * @param $pageNo
     * @param $pageSize
     * @return mixed
     */
    public function searchSpec($productId, $pageNo, $pageSize) {
            $conditions = array('and','1=1');
            array_push($conditions, "product_id = :product_id");
            $paramters['product_id'] = $productId;
            $collection = new WholesaleProductSpecCollection();
            $records 	= $collection
                ->getRecords($paramters,$pageNo ,$pageSize);

            return $records ;
    }

    /**
    *	GET: 	/user/groupbuyingmaster/self/product/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
    *	Search product by keyword, date, group, 
    *
    */
    public function searchByMasterSelf($pageNo, $pageSize, $querystring) {
    	$data = array();

        try {
          
          	$master 			 	    = GroupBuyingMasterUser::getRecordBySession();
        	$condition 			 	    = $this->getCondition();
            $condition["masterId"] 	    = $master["id"];
            $condition["groupbuying"] 	= 1;

            $collection             = new WholesaleProductCollection();
        	$records 			 	= $collection->searchRecords($pageNo, $pageSize, $condition);

            $this->responser->send($records , $this->responser->OK());
        }      
        catch(AuthorizationException $e) {
          	$data['message'] = $e->getMessage();
          	$this->responser->send( $data, $this->responser->Forbidden() );         
        }
        catch ( Exception $e) {
          	$data['message'] = SERVER_ERROR_MSG;          
          	$this->responser->send( $data, $this->responser->InternalServerError() );
        }
    }

    /**
     * Get product info by id.
     * @param $category
     * @param $id
     * @param PermissionDbActor $actor
     * @return mixed
     * @throws DataAccessResultException
     * @throws Exception
     */
    private function getById($category, $id, PermissionDbActor $actor=null) {

        $collection = $this->getCollection( $category );

        if(!is_null($actor)) {
            $collection->setActor($actor);
        }

        $model = $collection->getById( $id );
        $data['record'] = $model->toRecord();

        if(count($data["record"]) == 0) {
            throw new DataAccessResultException("Product not found.");
        }

        $data['product_images'] = $model->getRecordWithImage();
        $data['explain_images'] = $model->getExplainImages();
        $data['spec'] = $model->getSpec();
        $data['fares'] = $model->getFares();

        return $data;
    }

    /**
    * GET:  /product/<category:\w+>/<id:\d+>
    *   Get product info of you wanted type.
    *
    *   @param $category    string which category you wanted ( ex. 'wholesale' / 'retail' )
    *   @param $id          number
    *
    *   @return  array Struct of array()
    */
    public function get($category, $id) {
        return $this->getById($category, $id, PlatformUser::instanceBySession());
    }

    /**
    * POST: /product/<category:\w+>
    *   create a product.
    *   
    *   @param string $category string which channel you wanted ( ex. 'wholesale' / 'retail' )
    *
    *   @param string $this->receiver['name']
    *   @param number $this->receiver['parent_group_id']
    *   @param string $this->receiver['type']
    *   
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    public function create( $category ) {      
        $result = array();
        $collection = $this->getCollection( $category );
        $dao = $collection->dao;
        if($dao->transaction()) {
            try {
                $collection->validAttributes( $this->receiver );
                $user = PlatformUser::instanceBySession();
                $collection->setActor($user);

                $options = $collection->getAttributes();
                $options['master_id'] = $user->getId();
                $options['modify_time'] = date("Y-m-d H:i:s");
                $result["product_effectRow"] = $collection->create( $options );
                $result["id"] = $collection->lastCreated()->id;

                if( $this->params("media_type") == "0" ){
                    $product_images = $this->params("product_images");
                    $result["materials"] = $this->materialsAppend( $result["id"], $category, $product_images, $dao );
                }

                $deliverys = $this->params("deliverys");
                $result["delivery"]     = $this->deliveryAppend( $result["id"], $deliverys, $dao );

                $explain_images = $this->params("explain_images");
                $result["explain"]  = $this->explainAppend( $result["id"], $category, $explain_images, $dao );

                $specs = $this->params("spec");
                $result["spec"]     = $this->specAppend( $result["id"], $category, $specs, $dao );

                $dao->commit();
                return $result;
            }
            catch(Exception $e) {
                $dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Begin transaction fail.");
        }

    }

    /**
    *   add materials.
    *   
    *   @param array $this->params("product_images")
    *
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    private function deliveryDelete( $id, $category, $deliverys, &$dao=null )
    {
        $attributes = array();
        foreach ($deliverys as $key => $delivery) {
            array_push( $attributes, $delivery['productHasDeliveryId'] );
        }
        $collection = new ProductHasDeliveryCollection( $dao );
        $effectRow = $collection->multipleDestroyById( $attributes );
        if( count($deliverys)!=$effectRow ){
            throw new Exception("deliverys delete fail.", 1);
        }
        return $effectRow;
    }

    /**
    *   add fares.
    *   
    *   @param array $this->params("product_images")
    *
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    private function deliveryAppend( $product_id, $deliverys=array(), &$dao=null )
    {

        $values = array();
        foreach ($deliverys as $key => $delivery_id) {
            array_push( $values, array( $product_id, $delivery_id ) );
        }
        if( !empty($values) ){
            $collection = new ProductHasDeliveryCollection( $dao );
            $attributes = array( "product_id", "delivery_id" );

            $effectRow = $collection->multipleCreate( $attributes, $values );
            if( $effectRow != count($deliverys) ){
                throw new Exception("delivery list create ".json_encode($deliverys)." fail.", 1);
            }
            return $effectRow;

        }else{
            return false;
        }
    }

    /**
    *   add materials.
    *   
    *   @param array $this->params("product_images")
    *
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    private function materialsAppend( $id, $category, $product_images, &$dao=null )
    {
        $data = array();
        $data["product_id"] = $id;
        $data["product_images"] = $product_images;

        if( !empty($data["product_images"]) ){
            $collection = $this->getMaterialsCollection( $category, $dao );
            $collection->validAttributes( $data );

            $options = $collection->getAttributes();
            $result = $collection->addMaterialsUrl( $options );
            
            if( $result["effectRow"] != count($data["product_images"]) ){
                throw new Exception("Product media create fail.", 1);
            }
            
            return $result;
        }else{
            return false;
        }
    }

    /**
    *   add explain materials.
    *   
    *   @param array $this->params("explain_images")
    *
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    private function explainAppend( $id, $category, $explain_images, &$dao=null )
    {
        $data = array();
        $data["product_id"] = $id;
        $data["explain_images"] = $explain_images;

        if( !empty($data["explain_images"]) ){
            $collection = $this->getExplainMaterialsCollection( $category, $dao );
            $collection->validAttributes( $data );

            $options = $collection->getAttributes();
            $result = $collection->addMaterialsUrl( $options );
            
            if( $result["effectRow"] != count($data["explain_images"]) ){
                throw new Exception("Product explain update fail.", 1);
            }
            
            return $result["effectRow"];

        }else{
            return false;
        }
    }

    /**
    *   add spec materials.
    *   
    *   @param array $this->params("spec")
    *
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    private function specAppend( $id, $category, $specs, &$dao=null )
    {
        $data = array();
        $data["product_id"] = $id;
        $data["spec"] = $specs;

        if( !empty($data["spec"]) ){
            $collection = $this->getSpecCollection( $category, $dao );
            $collection->validAttributes( $data );

            $options = $collection->getAttributes();
            $result = $collection->addSpec( $options );

            if( $result["effectRow"] != count($data["spec"]) ){
                throw new Exception("Product spec create fail.", 1);
            }

            return $result;
        }else{
            return false;
        }
        
    }

    /**
    *   delete specs.
    *   
    *   @param array $this->params("deleteSpec")
    *
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    private function specDelete( $product_id, $category, $specs, &$dao=null )
    {
        $spec_data = array();
        if( !empty($specs) ){
            $spec_collection = $this->getSpecCollection( $category, $dao );
            $effectRow = $spec_collection->multipleDestroyById($specs);

            if( $effectRow*1 != count($specs) ){
                throw new Exception("Product spec delete fail.", 1);
            }

            return $effectRow;
        }else{
            return false;
        }
    }

    /**
    * 	PUT: /product/<category:\w+>
    *   create a product.
    *   
    *   @param string $category string which channel you wanted ( ex. 'wholesale' / 'retail' )
    *
    *   
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    public function update( $category, $id ) {
      
        $data = array();

        $collection = $this->getCollection( $category );
        $dao = $collection->dao;

        if($dao->transaction()) {
            try {
                $user = PlatformUser::instanceBySession();
                $collection->setActor($user);

                $collection->validAttributes($this->receiver);
                $result['isSuccess'] = $collection->productUpdate($id ,$user);

                if( $this->params("media_type") == "0" ){
                    $product_images = $this->params("product_images");
                    $result["materials"] = $this->materialsAppend( $id, $category, $product_images, $dao );
                }

                $explain_images = $this->params("explain_images");
                $result["explain"] = $this->explainAppend( $id, $category, $explain_images, $dao );
                
                $specs = $this->params("spec");
                $result["spec_create"] = $this->specAppend( $id, $category, $specs, $dao );

//                $deleteSpecs = $this->params("deleteSpec");
//                $result["spec_delete"] = $this->specDelete( $id, $category, $deleteSpecs, $dao );

                $deliverys = $this->params("deliverys");
                $result["delivery_create"] = $this->deliveryAppend( $id, $deliverys, $dao );

                $deleteDeliverys = $this->params("deleteDeliverys");
                $result["delivery_delete"] = $this->deliveryDelete( $id, $category, $deleteDeliverys, $dao );

                $dao->commit();
                return $result;
            }
            catch(Exception $e) {
                $dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Begin transaction fail.");
        }

    }

    public function modifySpec( $category ) {
        $actor    = PlatformUser::instanceBySession();
        $collection = new WholesaleProductSpecCollection();
        $attributes = array(
            'name' => $this->params("name"),
            'can_sale_inventory' => $this->params("can_sale_inventory"),
            'safe_inventory' => $this->params("safe_inventory")
        );
        $count = $collection->getById($this->params("id"))->setActor($actor)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update updateGroupName record to DB fail.");
        }
        return array();
    }


    /**
    * 	DELETE: /product/<category:\w+>/<id:\d+>
    *   delete a product group.
    *   
    *   @param number $id       string which product you wanted ( ex. 5 or 6 or 7... )
    *   @param number $category  string which channel you wanted ( ex. wholesale or retail )
    *   
    *   @return  number ( is success delete in db, ex. true or false )
    */
    public function remove( $category, $id ) {
      
        $data = array();

        try {

            $collection = $this->getCollection( $category );

            $user = PlatformUser::instanceBySession();
            $collection->setActor($user);

            $options = array(
                    "id" => $id
                );
            $data = $collection->productRemove($options);

            // $model = $collection->getById( $id );
            // $data['effectRow'] = $model->productRemove();
            // $data['effectRow'] = $model->destroy();
            if( $data['effectRow'] == 1 )
            {
                $this->responser->send( $data, $this->responser->OK());
            }
            else
            {
                throw new DataAccessResultException("can't remove the model [$id]", 1);
                
            }
          
        }
        catch(DataAccessResultException $e) {
            $data['message'] = $e->getMessage();
            $this->responser->send( $data, $this->responser->Conflict());
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
    * POST:     /product/upload/<category:\w+>
    *   upload file which you wanted.
    *
    *   @param $category     string which coategory you wanted upload ( ex. 'wholesale' or 'retail' )
    */
    public function materialsUpload( $category ) {
      
        $data = array();

        try {

            $collection = $this->getMaterialsCollection( $category );

            $user = PlatformUser::instanceBySession();
            $collection->setActor($user);

            $data = $collection->uploadMeterials();

            if($data){
                $this->responser->send( $data , $this->responser->OK() );    
            }else{
                $this->responser->send( $data , $this->responser->BadRequest() );
            }

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
    *   POST: /product/materials/<materialsType:\w+>/<category:\w+>
    *   create product materials.
    *   
    *   @param string $materialsType string which materials type you wanted ( ex. 'explain' / 'product' )
    *   @param string $category string which channel you wanted ( ex. 'wholesale' / 'retail' )
    *   
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    public function materialsCreate( $materialsType, $category ) {
      
        $data = array();
        try {

            $options = array();
            if( $materialsType == "product" ){
                $collection = $this->getMaterialsCollection( $category );
                $options['product_images'] = $this->receiver['product_images'];
            }else if( $materialsType == "explain" ){
                $collection = $this->getExplainMaterialsCollection( $category );
                $options['explain_images'] = $this->receiver['explain_images'];
            }

            $options['product_id'] = $this->receiver['product_id'];
            
            $data = $collection->addMaterialsUrl( $options );
            $this->responser->send( $data , $this->responser->OK() );
        }
        catch(AuthorizationException $e) {
            $data['message'] = $e->getMessage();
            $this->responser->send( $data, $this->responser->Forbidden() );         
        }
        catch ( Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
    }

    /**
    *   PUT: /product/materials/<category:\w+>
    *   update product materials.
    *   
    *   @param string $category string which channel you wanted ( ex. 'wholesale' / 'retail' )
    *
    *   
    *   @return  number ( effect row in db, ex. 0 or 1 or 2... )
    */
    public function materialsUpdate( $category ) {
      
        $data = array();
        try {  

            $collection = $this->getMaterialsCollection( $category );

            $user = PlatformUser::instanceBySession();
            $collection->setActor($user);
            
            if( !array_key_exists("product_id", $this->receiver) ){
                throw new Exception("Error missing product_id param.", 1);
            }

            if( !array_key_exists("url", $this->receiver) ){
                throw new Exception("Error missing url param.", 1);
            }

            if( !array_key_exists("updateValue", $this->receiver) ){
                throw new Exception("Error missing updateValue param.", 1);
            }

            $options = array( 
                "product_id" => $this->receiver['product_id'],
                "url"        => $this->receiver["url"]
                );
            $model = $collection->get( $options );
            $data['id'] = $model->getId();

            $options = array(
                "url"=>$this->receiver["updateValue"]
                );
            $data["isSuccess"] = $model->update( $options );
            $this->responser->send( $data , $this->responser->OK() );
            
            
        }
        catch(AuthorizationException $e) {
            $data['message'] = $e->getMessage();
            $this->responser->send( $data, $this->responser->Forbidden() );         
        }
        catch ( Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
    }

    private function removeMaterials( $source, $category, $fileName, $type )
    {
        $result = array();
        $collection = null;
        if( $source == 'product' ){
            $collection = $this->getMaterialsCollection($category);
        }else if( $source == 'explain' ){
            $collection = $this->getExplainMaterialsCollection($category);
        }

        if(isset($collection)){
            $model = $collection->get( array( "url"=>$fileName.".".$type ) );
            $result[ $fileName ] = $model->destroy();
        }

        return $result;
    }

    /**
    *   "DELETE: /product/materials/<channel:\w+>/<source:\w+>/<category:\w+>/<filename:\w+>/<fileType:\w+>"
    *   remove material that you don't needed.
    *   
    */
    public function materialsRemove( $channel, $source, $category, $fileName, $type ) {
      
        $data = array();

        try {

            $data[ "isSuccess" ] = $this->removeMaterials( $source, $channel, $fileName, $type );

            $file = UPLOAD . $category . "/" . $fileName . "." . $type;
            if( unlink($file) )
            {
                $this->responser->send( $data , $this->responser->OK() );
            }
            else
            {
                $this->responser->send( $data , $this->responser->BadRequest() ); 
            }


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
    *   get collection list entity
    *   
    *   @return array
    */
    public function getCollectionsList(){

        return array(
            "wholesale"     => "WholesaleProductCollection",
            // "retail"        => "RetailProductCollection"
		);
    }    

    /**
    *   get really category collection entity
    *   
    *   @param string category (ex. 'wholesale' or 'retail')
    *   @return collection object
    */
    public function getCollection( $category, &$dao=null ) {
        $collections = $this->getCollectionsList();
        if( array_key_exists($category, $collections) ) {
            $collectionName = $collections[ $category ];
            return new $collectionName($dao);
        }
        else {
            throw new Exception("Undefined $category collection.", 1);
        }
    }

    /**
    *   get collection list entity
    *   
    *   @return array
    */
    public function getSpecCollectionsList(){

        return array(
            "wholesale"     => "WholesaleProductSpecCollection",
            // "retail"        => "RetailProductSpecCollection"
        );
    }    

    /**
    *   get really category collection entity
    *   
    *   @param string category (ex. 'wholesale' or 'retail')
    *   @return collection object
    */
    public function getSpecCollection( $category, &$dao=null ) {
        $collections = $this->getSpecCollectionsList();
        if( array_key_exists($category, $collections) ) {
            $collectionName = $collections[ $category ];
            return new $collectionName($dao);
        }
        else {
            throw new Exception("Undefined $category collection.", 1);
        }
    }

    /**
    *   get collection list entity
    *   
    *   @return array
    */
    public function getExplainMaterialsCollectionsList(){

        return array(
            "wholesale"     => "WholesaleExplainMaterialsCollection",
            // "retail"        => "RetailMaterialsCollection"
        );
    }

    /**
    *   get really category collection entity
    *   
    *   @param string category (ex. 'wholesale' or 'retail')
    *   @return collection object
    */
    public function getExplainMaterialsCollection( $category, &$dao=null ) {
        $collections = $this->getExplainMaterialsCollectionsList();
        if( array_key_exists($category, $collections) ) {
            $collectionName = $collections[ $category ];
            return new $collectionName($dao);
        }
        else {
            throw new Exception("Undefined $category collection.", 1);
        }
    }

    /**
    *   get collection list entity
    *   
    *   @return array
    */
    public function getMaterialsCollectionsList(){

        return array(
            "wholesale"     => "WholesaleMaterialsCollection",
            // "retail"        => "RetailMaterialsCollection"
        );
    }

    /**
    *   get really category collection entity
    *   
    *   @param string category (ex. 'wholesale' or 'retail')
    *   @return collection object
    */
    public function getMaterialsCollection( $category, &$dao = null ) {
        $collections = $this->getMaterialsCollectionsList();
        if( array_key_exists($category, $collections) ) {
            $collectionName = $collections[ $category ];
            return new $collectionName( $dao );
        }
        else {
            throw new Exception("Undefined $category collection.", 1);
        }
    }

    public function vaildateFiled(){
        return true;
    }

    public function updateRemark( $category, $id ) {
        $collection = new WholesaleProductCollection();
        $attributes = array(
            'remark' => $this->params("remark")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }
        return $attributes;
    }

    public function searchByAdminSpec($category, $pageNo, $pageSize, $querystring) {
    $data = array();

    try {
        $actor      = PlatformUser::instanceBySession();
        $condition  = $this->getCondition();
        $collection = new WholesaleProductCollection();
        $variable   = $collection
            ->setActor($actor)
            ->searchRecords($pageNo, $pageSize, $condition);

        // $this->responser->send($records , $this->responser->OK());
        foreach ($variable['records'] as $key => $value) {

            $conditions = array('and','1=1');
            array_push($conditions, "product_id = :product_id");
            

            $paramters['product_id'] = $value['id'];
            $collection = new WholesaleProductSpecCollection();
            $records    = $collection
                ->getRecords($paramters,1,10);
            $variable['records'][$key]['spec'] = $records;
            
        }
        return $variable;
    }
    catch(DataAccessResultException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->Conflict());
    }
    catch(DbOperationException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->InternalServerError());
    }
    catch(InvalidAccessParamsException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->BadRequest());
    }
    catch(AuthorizationException $e) {
        $data['message'] = $e->getMessage();
        $this->responser->send( $data, $this->responser->Forbidden() );
    }
    catch(Exception $e) {
        $data['message'] = SERVER_ERROR_MSG;
        $this->responser->send( $data, $this->responser->InternalServerError() );
    }
    }
}




?>