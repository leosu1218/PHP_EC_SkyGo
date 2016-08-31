<?php

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'models/Reimburse.php' );

/**
*   OrderHasSpecCollection Access Product entity collection.
*
*   PHP version 5.3
*
*   @category Collection 
*   @package Product
*   @category Collection
*   @package Product
*   @author Ares
*/
class ReimburseCollection extends PermissionDbCollection {


    public function searchRecords($pageNo, $pageSize, $search=array()) {

        $result = $this->getDefaultRecords($pageNo, $pageSize);
        $table = $this->getTable();
        $conditions = array('and','1=1');
        $params = array();

        $this->dao->fresh();
        $this->dao->select(array(
            're.*',
            'cu.email email',
            'cu.name name'
        ));

        $this->dao->from("$table re");
        $this->dao->group('re.id');
        $this->dao->order('re.id DESC');

        $this->dao->leftJoin(
            'consumer_user cu',
            'cu.id=re.consumer_user_id');


        if(array_key_exists('id', $search)) {
            $id = $search['id'];
            array_push($conditions, 're.id=:id');
            $params[':id'] = $id;
        }

        if(array_key_exists('keyword', $search)) {
            $keyword = $search['keyword'];
            array_push($conditions, '(re.order_serial like :word or re.buy_name like :word or re.reimburse_name like :word)');
            $params[':word'] = "%$keyword%";
        }

        $this->dao->where($conditions,$params);
        $result['recordCount'] = intval($this->dao->queryCount());
        $result['totalRecord'] = $result['recordCount'];
        $result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();

        return $result;
    }
    
    /* PermissionDbCollection abstract methods. */

    /**
    *   Get the entity table name.
    *
    *   @return string 
    */
    public function getTable() {
        return "reimburse";
    }

    public function getModelName() {
        return "Reimburse";
    }

    /**
    *   Check attributes is valid.
    *
    *   @param $attributes  array Attributes want to checked.
    *   @return bool        If valid return true.
    */
    public function validAttributes($attributes) {
        $this->attributes = $attributes;

        return true;

    }

    /**
    *   Get Primary key attribute name
    *
    *   @return string
    */
    public function getPrimaryAttribute() {
        return "id";
    }


}



?>
