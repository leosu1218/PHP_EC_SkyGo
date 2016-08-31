<?php
/**
*  GroupBuyingActivityController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@syncte-infinity.com>
*  @copyright 2015 synctech.com
*/
require_once( FRAMEWORK_PATH . 'collections/UnifiedOrderCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingActivityCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleProductSpecCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleMaterialsCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/WholesaleExplainMaterialsCollection.php' );

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );

class GroupBuyingActivityController extends RestController {

    /**
     * PUT: 	/activity/groupbuying/list/state
     * The platform admin update activities state by id list.
     *
     */
    public function updateStateByIds() {
        $data = array();
        try {
            $ids        = $this->params("ids");
            $stateText  = $this->params("stateText");
            $actor      = PlatformUser::instanceBySession();
            $collection = new GroupBuyingActivityCollection();
            $rowCount   = $collection->setActor($actor)->updateStateByIds($ids, $stateText);

            if($rowCount > 0) {
                $this->responser->send( $data, $this->responser->OK());
            }
            else {
                $data["message"] = "Accept access but no changed.";
                $this->responser->send( $data, $this->responser->InternalServerError());
            }
        }
        catch(OperationConflictException $e) {
            $data['message'] = $e->getMessage();
            $this->responser->send( $data, $this->responser->Conflict());
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
     * PUT: 	/user/groupbuyingmaster/self/activity/list/state
     * The group buying master update activities state by id list.
     *
     */
    public function updateStateByMasterSelf() {
        $data = array();
        try {
//            TODO refactoring (Too long method)
            $ids        = $this->params("ids");
            $stateText  = $this->params("stateText");
            $master     = GroupBuyingMasterUser::getRecordBySession();
            $collection = new GroupBuyingActivityCollection();

            if($stateText != GroupBuyingActivityCollection::CONFIRMED_STATEMENT_STATE &&
                $stateText != GroupBuyingActivityCollection::ABNORMAL_STATEMENT_STATE) {
                throw new InvalidAccessParamsException("Invalid state request.");
            }

            $result = $collection->searchRecords(1, 9999, array("ids" => $ids, "masterId" => $master["id"]));
            $records = $result["records"];
            $nextState = $collection->getState($stateText);

            foreach($records as $index => $record) {
                $recordState = $collection->getState($record["stateText"]);
                if(!$recordState->canChangeState($nextState)) {
                    $id = $record["id"];
                    throw new OperationConflictException("Conflict change activity records[$id] to state[$stateText].");
                }
            }

            $rowCount = $collection->multipleUpdateById($ids, $nextState->getChangeAttributes());

            if($rowCount > 0) {
                $this->responser->send( $data, $this->responser->OK());
            }
            else {
                $data["message"] = "Accept access but no changed.";
                $this->responser->send( $data, $this->responser->InternalServerError());
            }
        }
        catch(OperationConflictException $e) {
            $data['message'] = $e->getMessage();
            $this->responser->send( $data, $this->responser->Conflict());
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
	*	GET: 	/user/groupbuyingmaster/self/activity/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
	*	Search activity by keyword, date, state
	*
	*/
	public function searchByMasterSelf($pageNo, $pageSize, $querystring) {
		$data = array();
		try {

			$master 				= GroupBuyingMasterUser::getRecordBySession(); 
			$condition 				= $this->getCondition();
			$condition["masterId"] 	= $master["id"];
   			$records 				= (new GroupBuyingActivityCollection())   							
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
	*	GET: 	/user/groupbuyingmaster/self/activity/<id:\d+>
	*	Get a activity details by groupbuying master self
	*
	*/
	public function getByMasterSelf($id) {
        $data = array();
        try {
//            TODO refactory(Too long method)
            $master 				    = GroupBuyingMasterUser::getRecordBySession();
            $condition 				    = $this->getCondition();
            $condition["activityType"]	= GroupBuyingActivity::TYPE_NAME;
            $condition["masterId"] 	    = $master["id"];
            $condition["actor"]         = "admin";
            $condition["ids"] 	        = array($id);
            $collection                 = new GroupBuyingActivityCollection();
            $dao                        = $collection->dao;
            $result 				    = $collection->searchRecords(1, 1, $condition);

            if(count($result["records"]) == 1) {
                $record = $result["records"][0];
                $record["totalSpecPrice"] = 0;
                $record["totalSpecAmount"] = 0;
            }
            else {
                $this->responser->send( $data, $this->responser->NotFound());
            }

            $condition                  = array();
            $condition["activityType"]	= GroupBuyingActivity::TYPE_NAME;
            $condition["state"]	        = UnifiedOrderCollection::COMPLETED_ORDER_STATE;
            $condition["masterId"] 	    = $master["id"];
            $condition["activityId"]	= $id;
            $result 				    = (new UnifiedOrderCollection())
                                            ->searchSpecRecords(1, 1000000, $condition);

            foreach($result["records"] as $index => $spec) {
                $record["totalSpecPrice"] += $spec["spec_total_price"];
                $record["totalSpecAmount"] += $spec["spec_amount"];
            }

            $this->responser->send( $record, $this->responser->OK());
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
     * GET: /activity/groupbuying/<id:\d+>/buyinfo
     * Get activity buying page infos.
     * (Used for buyer)
     * @param $id int activity's id.
     */
	public function getBuyInfo($id) {
        $activity  = new GroupBuyingActivityCollection();
        $record    = $activity->getUnifiedById($id);
        if(count($record) == 0) {
            throw new DataAccessResultException("User request not exists groupbuying activity[$id]");
        }
        return $record;
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
    	$this->getQueryString("state", $condition);
        $this->getQueryString("order", $condition);
    	$this->getQueryString("startDateOpen", $condition);
    	$this->getQueryString("startDateClose", $condition);
    	$this->getQueryString("endDateOpen", $condition);
    	$this->getQueryString("endDateClose", $condition);
    	$this->getQueryString("isAfterStartDate", $condition);
    	$this->getQueryString("isAfterEndDate", $condition);

    	return $condition;
    }

    /**
    *	GET: 	/activity/groupbuying/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
    *	Search activity by keyword, date, state 
    *
    */
    public function searchByAdmin($pageNo, $pageSize, $querystring) {
    	$data = array();

        try {
        	$actor 		= PlatformUser::instanceBySession();
        	$condition 	= $this->getCondition();
            $condition['actor'] = "admin";
   			$records 	= (new GroupBuyingActivityCollection())
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
     *	GET: 	/activity/groupbuying/search/client/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     *	Search activity by keyword, date, state
     *
     */
    public function searchByClient($pageNo, $pageSize, $querystring) {
        $condition 	= $this->getCondition();
        $records 	= (new GroupBuyingActivityCollection())
            ->searchRecords($pageNo, $pageSize, $condition);

        return $records;
    }


	/**
	*	PUT: 	/activity/groupbuying/<id:\d+>/note
	*	Update note info by id
	*
	*	@param $id int activity's id.
	*/
	public function updateNote($id) {
		$data = array();
	     try {

	     	$note 		= $this->receiver["note"];
	       	$actor 		= PlatformUser::instanceBySession();
	       	$collection = new GroupBuyingActivityCollection();
   			$rowCount 	= $collection
   							->getById($id)
   							->setActor($actor)
   							->update(array("note" => $note));

	        if($rowCount > 0) {
	          	$this->responser->send($data, $this->responser->OK());
	        }
	        else {
	          	throw new DbOperationException("No changed in the operation.", 1);	          	
	        }
	    }      
	    catch(DbOperationException $e) {
	    	$data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );   
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
	*	POST: 	/user/groupbuyingmaster/self/activity
	*	Create a activiry by groupbuying master.
	*
	*
	*/
	public function createByMasterSelf() {
//        TODO refactoring
		$data = array();
   		try {
   			$master 		= GroupBuyingMasterUser::getRecordBySession();
   			$collection 	= new GroupBuyingActivityCollection();
   			$this->receiver["masterId"] = $master['id'];

   			if($collection->isValid($this->receiver)) {

   				$attributes = array();
   				$attributes['name']         = $this->receiver['name'];
   				$attributes['product_id']   = $this->receiver['productId'];
   				$attributes['price']        = $this->receiver['price'];
   				$attributes['start_date']   = $this->receiver['startDate'];
   				$attributes['end_date']     = $this->receiver['endDate'];
   				$attributes['master_id']    = $master['id'];

   				$rowCount 	= $collection->create($attributes);
   				if($rowCount > 0) {
   					$this->responser->send($data, $this->responser->Created());
   				}
   				else {
   					throw new Exception("Create activity faild.", 1);
   				}
   			}
   			else {
   				throw new Exception("Invalid parameters.", 1);
   			}
   		}
   		catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send($data, $this->responser->Unauthorized());
	    }
	    catch(InvalidAccessParamsException $e) {
	    	$data['message'] = $e->getMessage();
	    	$this->responser->send( $data, $this->responser->BadRequest());
	    }
   		catch(Exception $e) {
   			$data['message'] = SERVER_ERROR_MSG;
	        $this->responser->send($data, $this->responser->InternalServerError());
   		}
	}

}




?>