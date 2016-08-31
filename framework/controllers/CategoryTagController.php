<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/TagImageUploadHelper.php' );

require_once( FRAMEWORK_PATH . 'collections/MainCategoryTagCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/SubOneCategoryTagCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/SubTwoCategoryTagCollection.php' );


/**
 * Class CategoryTagController
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class CategoryTagController extends RestController {


    /**
    *   GET:   /tag/<categoryId:\w+>
    *
    */
    public function get( $category_id ){

        $data = array();

        $mainCollcetion = new MainCategoryTagCollection();
        $data['main'] = $mainCollcetion->getRecords( array("id"=>$category_id) );
        $subOneCollcetion = new SubOneCategoryTagCollection();
        $data['subOne'] = $subOneCollcetion->getRecords( array("mct_id"=>$category_id) );
        $subTwoCollcetion = new SubTwoCategoryTagCollection();
        $data['subTwo'] = $subTwoCollcetion->getRecords( array("mct_id"=>$category_id) );

        return $data;
    }

    /**
    *   PUT:   /tag/<category:\w+>/<id:\w+>
    *
    */
    public function update( $category, $id ){
        $collection = $this->getCollection( $category );
        $model = $collection->getById( $id );
        $attribute = $this->params("update");
        $result = $model->update( $attribute );
        return array( "isSuccess"=>$result );
    }

    private function getCollection( $type ){
        if( $type=="main" ){
            return new MainCategoryTagCollection();
        }else if( $type=="subOne" ){
            return new SubOneCategoryTagCollection();
        }else if( $type=="subTwo" ){
            return new SubTwoCategoryTagCollection();
        }else{
            throw new Exception("Unsuport this $type type to get collection.", 1);
            
        }
    }

    /**
    *   POST:   /tag/upload/<category_id:\w+>
    *
    */
    public function uploadImage( $category_id ){
        
        $upload = new TagImageUploadHelper();
        $collection = new MainCategoryTagCollection();
        $result = $upload->receive();

        $model = $collection->getById($category_id);
        $attribute = array( 'image_filename'=>$result['file']['fileName'] );
        $data = array();
        $data['isSuccess'] = $model->update($attribute);
        $data['fileName'] = $result['file']['fileName'];

        return $data;

    }

    /**
     * POST: 	/tag/insert/<category:\w+>
     * @param $category
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     * @throws InvalidAccessParamsException
     */
    public function insertTag($category){
        $collection = $this->getCollection($category);
        $attribute = $this->params("insert");
        $result = $collection->create($attribute);
        return array( "isSuccess"=>$result );

    }

    /**
     * DELETE: /tag/delete/<category:\w+>/<id:\w+>
     * @param $category
     * @param $id
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    public function deleteTag($category,$id){
        $collection = $this->getCollection($category);
        $model = $collection->getById($id);
        $result = $model->destroy();
        return array( "isSuccess"=>$result );

    }

}




?>