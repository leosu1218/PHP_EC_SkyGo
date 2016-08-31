<?php
require_once( FRAMEWORK_PATH . 'collections/GeneralActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/GeneralActivityHasRelationProductDiscountCollection.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );

/**
 *  GeneralActivityController code.
 *
 *  PHP version 5.3
 *
 *  @category Controller
 *  @package Controller
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
class GeneralActivityController extends RestController {

    /**
     * GET: /activity/general/<id:\d+>/buyinfo
     * Get activity buying page info.
     * (Used for buyer)
     * @param $id
     * @return array
     */
    public function getBuyInfo($id) {
        $activity  = new GeneralActivityCollection();
        $record    = $activity->getUnifiedById($id);
        if(count($record) == 0) {
            throw new DataAccessResultException("User request not exists general activity[$id]");
        }
        $record["relationProducts"] = $this->getRelationProductById($id, 1, 1000);
        return $record;
    }

    /**
     * PUT: 	/activity/general/<id:\d+>
     * Update a general activity record by id.
     * @param $id
     */
    public function update($id) {

        if( (new DateTime($this->params("startDate"))) == (new DateTime($this->params("endDate"))) ) {
            throw new Exception("Error [startDate and endDate] is equal.", 1);
        }

        $attributes = array(
            "name" => $this->params("name"),
            "price" => $this->params("price"),
            "end_date" => $this->params("endDate"),
            "start_date" => $this->params("startDate"),
        );

        $actor = PlatformUser::instanceBySession();
        $rowCount = (new GeneralActivityCollection())
                        ->getById($id)
                        ->update($attributes);

        if($rowCount != 1) {
            throw new DataAccessResultException("Update record fail.");
        }

        $attributes["id"] = $id;
        return array($attributes);
    }

    /**
     *	Get condition for search product method from http request querysting.
     *	There will filter querystring key, values.
     *
     *	@return
     */
    public function getCondition() {

        $condition = array();
        $this->getQueryString("keyword", $condition);
        $this->getQueryString("id", $condition);
        $this->getQueryString("state", $condition);
        $this->getQueryString("order", $condition);
        $this->getQueryString("startDateOpen", $condition);
        $this->getQueryString("startDateClose", $condition);
        $this->getQueryString("endDateOpen", $condition);
        $this->getQueryString("endDateClose", $condition);
        $this->getQueryString("tag1", $condition);
        $this->getQueryString("tag2", $condition);
        $this->getQueryString("tag3", $condition);

        return $condition;
    }

    /**
     * GET: 	/activity/general/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search activity by keyword, date, state
     * @return array
     */
    public function searchByAdmin($pageNo, $pageSize, $querystring) {
        $actor 		= PlatformUser::instanceBySession();
        $condition 	= $this->getCondition();
        $condition['actor'] = "admin";
        $records 	= (new GeneralActivityCollection())
            ->setActor($actor)
            ->searchRecords($pageNo, $pageSize, $condition);

        return $records;
    }

    /**
     * GET: 	/activity/general/search/client/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * Search activity by keyword, date, state
     * @return array
     */
    public function searchByClient($pageNo, $pageSize, $querystring) {
        $condition 	= $this->getCondition();
        $records 	= (new GeneralActivityCollection())
            ->searchRecords($pageNo, $pageSize, $condition);

        return $records;
    }

    /**
     * GET: 	/activity/general/<id:\d+>/relation/product
     * Get the activity relation product by activity id.
     * @param $id
     */
    public function getRelationProductById($id, $pageNo, $pageSize) {
        return (new GeneralActivityHasRelationProductDiscountCollection())
                    ->searchRecordsByActivityId($id, $pageNo, $pageSize);
    }

    /**
     * DELETE: 	/activity/general/<id:\d+>/relation/product
     * Remove relation product.
     * @param $id activity id
     */
    public function removeRelationProduct($id) {
        $actor  = PlatformUser::instanceBySession();
        $rowCount = (new GeneralActivityHasRelationProductDiscountCollection())
                    ->get(array("activity_id" => $id))
                    ->destroy();

        if($rowCount == 0) {
            throw new DbOperationException("Remove relation product fail.", 1);
        }

        return array("id" => $id);
    }

    /**
     * POST: 	/activity/general/<id:\d+>/relation/product
     * Append a new relation product to the activity
     * @param $id activity id
     */
    public function appendRelationProduct($id) {

        $actor 		    = PlatformUser::instanceBySession();
        $attributes = array(
            "activity_id" => $id,
            "relation_product_id" => $this->params("relationProductId"),
            "price" => $this->params("relationProductPrice")
        );
        $discount = new GeneralActivityHasRelationProductDiscountCollection();
        $rowCount = $discount->create($attributes);
        if($rowCount == 0) {
            throw new DbOperationException("Append relation product fail.", 1);
        }

        return $attributes;
    }

    /**
     * POST: 	/activity/general
     * Create new general activity.
     * @return array
     */
    public function create() {
        $actor 		    = PlatformUser::instanceBySession();
        $collection 	= new GeneralActivityCollection();
        $dao            = $collection->dao;
        $attributes     = array();

        $this->receiver["masterId"] = $actor->getId();
        if(!$collection->isValid($this->receiver)) {
            throw new InvalidArgumentException("Invalid parameters.");
        }

        if($dao->transaction()) {
            try {
                $attributes['name']         = $this->receiver['name'];
                $attributes['product_id']   = $this->receiver['productId'];
                $attributes['price']        = $this->receiver['price'];
                $attributes['start_date']   = $this->receiver['startDate'];
                $attributes['end_date']     = $this->receiver['endDate'];
                $attributes['master_id']    = $actor->getId();

                $rowCount = $collection->create($attributes);
                $id = $collection->lastCreated()->getId();
                if($rowCount == 0) {
                    throw new DbOperationException("Create activity fail.", 1);
                }

                if(array_key_exists("relationProductId", $this->receiver) &&
                    array_key_exists("relationProductPrice", $this->receiver)) {

                    $attributes["relationProductDiscount"] = array(
                        "activity_id" => $id,
                        "relation_product_id" => $this->receiver["relationProductId"],
                        "price" => $this->receiver["relationProductPrice"],
                    );
                    $discount = new GeneralActivityHasRelationProductDiscountCollection($dao);
                    $rowCount = $discount->create($attributes["relationProductDiscount"]);
                    if($rowCount == 0) {
                        throw new DbOperationException("Create relation product fail.", 1);
                    }
                }

                $dao->commit();
            }
            catch(Exception $e) {
                $dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Can't open transaction.");
        }

        return $attributes;
    }

    /**
     *	PUT: 	/activity/groupbuying/<id:\d+>/note
     *	Update note info by id
     *
     *	@param $id int activity's id.
     */
    public function updateNote($id) {
        //TODO not implement
    }
}

?>