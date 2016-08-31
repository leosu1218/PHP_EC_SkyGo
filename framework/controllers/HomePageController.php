<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/HomePageImageCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/AdvertisementImageCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/HomePageUploadHelper.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/AdvertisementUploadHelper.php' );
require_once( FRAMEWORK_PATH . 'collections/AdvertisementImageCollection.php' );



/**
 * Class OrderController
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class HomePageController extends RestController {

    public function getBypromotion( $position, $pageNo, $pageSize ){
        $collection = $this->createCollection( $position );
        $records = $collection->getRecords(array(),$pageNo,$pageSize);
        return $records;
    }

    /**
     *  GET:   /homepage/<position:\w+>/image
     * Get home page images with position.
     * @param $position
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    public function getByBanner( $position, $pageNo, $pageSize ){
        // $collection = $this->createCollection( $position );
        // $records = $collection->getRecords(array(),$pageNo,$pageSize);
        // return $records;
        $collection = $this->createCollection( $position );
        $condition = array();
        $condition["property"] = $collection->getRecords( array("property"=> 0 ) );
        $records = $condition["property"];
       
        return $records;
    }

    /**
     *  GET:   /website/<position:\w+>/image/group
     * Get home page images with position.
     * @param $position
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    public function getByGroup( $position, $pageNo, $pageSize ){
        $collection = $this->createCollection( $position );
        $condition = array();
        $condition["property"] = $collection->getRecords( array("property"=> 1 ) );
        $records = $condition["property"];
       
        return $records;
    }

    /**
     * POST: 	/website/<position:\w+>/upload
     * @return array
     */
    public function upLoadHomePage($position){
        $upload = $this->createUpload($position);
        $ImageCollection =  $this->createCollection($position);
        $result = $upload->receive();

        $upLoadData = array();
        foreach($result  as  $item){
            $upLoadData['filename'] = $item['fileName'] ;
        }

        $count = $ImageCollection->create($upLoadData);
        if($count != 1) {
            throw new DbOperationException("insert returned record to DB fail.");
        }
        return  array();
    }

    /**
     * POST:    /website/<position:\w+>/upload/group
     * @return array
     */
    public function upLoadGroup($position){
        $upload = $this->createUpload($position);
        $ImageCollection =  $this->createCollection($position);
        $result = $upload->receive();

        $upLoadData = array();
        foreach($result  as  $item){
            $upLoadData['filename'] = $item['fileName'] ;
            $upLoadData['property'] = 1 ;
        }

        $count = $ImageCollection->create($upLoadData);
        if($count != 1) {
            throw new DbOperationException("insert returned record to DB fail.");
        }
        return  array();
    }

    /**
     * DELETE: /website/<position:\w+>/<id:\d+>
     * @return array
     * @throws AuthorizationException
     * @throws UploadException
     */
    public function removeHomePage($position,$id){
        $ImageCollection = $this->createCollection($position);
        $upload = $this->createUpload($position);

        $homePageModel = $ImageCollection -> getById($id) ;
        $homePageData = $homePageModel ->toRecord() ;

        $count = $homePageModel->destroy();
        if($count != 1) {
            throw new DbOperationException("Delete returned record to DB fail.");
        }
        $uploadData =  $upload ->removeByName($homePageData['filename']);

        return  array($uploadData);
    }

    /**
     * @param $type
     * @return AdvertisementImageCollection or HomePageImageCollection
     * @throws Exception
     */
    private function createCollection( $type ){
        if( $type=="banner" ){
            return new HomePageImageCollection();

        }
        else if( $type=="promotion" ){
            return new AdvertisementImageCollection();
        }
        else{
            throw new Exception("Unsuport this $type type to get collection.", 1);
        }
    }

    private function createUpload( $type ){
        if( $type=="banner" ){
            return new HomePageUploadHelper();
        }
        else if( $type=="promotion" ){
            return new AdvertisementUploadHelper();
        }
        else{
            throw new Exception("Unsuport this $type type to get collection.", 1);
        }
    }

    /**
     * PUT: /website/<position:\w+>/modify/<id:\d+>
     * @return array
     */
    public function updateUrl( $position, $id ) {
        $collection = $this->createCollection($position);
        $attributes = array(
            'imglink' => $this->params("bannerUrl")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update returned record to DB fail.");
        }
        return $attributes;
    }

}




?>
