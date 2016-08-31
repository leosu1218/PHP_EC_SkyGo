<?php
/**
*	WholesaleProductCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/WholesaleProduct.php' );

/**
*	WholesaleProductCollection Access Product entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class WholesaleProductCollection extends PermissionDbCollection {

	/**
	*	Search records.
	*
	*
	*	@param $search array The search conditions.
	*	@return array The records.
	*/
	public function searchRecords($pageNo, $pageSize, $search=array()) {

		$result = $this->getDefaultRecords($pageNo, $pageSize);	
		$table = $this->getTable();
		$conditions = array('and','1=1');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			'wp.id id',
			'wp.active_maximum maxDays',
			'wp.active_minimum minDays',
			'wp.wholesale_price wholesalePrice',
            'wp.end_price minPrice',
            'wp.suggest_price suggestPrice',
            'wp.name productName',
            'wp.detail productDetails',
            'wp.cover_photo_img coverPhoto',
            'wp.explain_text explainText',
            'wp.remark remark',
//            'wp.fare fare',
//            'wp.fare_type fareType',
            'wp.active_groupbuying groupbuying',
            'wp.youtube_url youtubeUrl',
            'wp.media_type mediaType',
            'wp.master_id master_id',
            'wp.modify_time modify_time',
            'wp.cost_price costPrice',
            'wp.propose_price proposePrice',

            'master.name masterName',

			'pg.id groupId',
			'pg.name groupName',
		));
		$this->dao->leftJoin(
			'product_group pg',
			'pg.id=wp.product_group_id');
		$this->dao->leftJoin(
			'gb_master_user_has_product_group mhpg',
			'pg.id=mhpg.product_group_id');

        $this->dao->leftJoin( 'platform_user master', 'master.id=wp.master_id');


		$this->dao->from("$table wp");
		$this->dao->group('wp.id');
		$this->dao->order('wp.modify_time DESC');

        if(array_key_exists("groupbuying", $search)) {
            array_push($conditions, 'wp.active_groupbuying=:active_groupbuying');
            $params[':active_groupbuying'] = $search['groupbuying'];
        }

		if(array_key_exists('masterId', $search)) {
			$this->dao->leftJoin(
				'gb_master_user mu',
				'mhpg.gb_master_user_id=mu.id');

			array_push($conditions, 'mu.id=:muid');
			$params[':muid'] = $search['masterId'];
		}

		if(array_key_exists('keyword', $search)) {
			// append product name keyword conditions.			
			$keyword = $search['keyword'];
			array_push($conditions, 'wp.name like :name');
			$params[':name'] = "%$keyword%";
		}

		if(array_key_exists('productId', $search)) {
			$productId = $search['productId'];
			array_push($conditions, 'wp.id=:productId');
			$params[':productId'] = $productId;
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
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "wholesale_product";
	}

	public function getModelName() {
		return "WholesaleProduct";
	}

	public function getPermissionName(){
		return "wholesale";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {

		$this->vaildateFiled( 
				array( 
                    "name",
                    
                    "active_maximum",
                    "active_minimum",
                    
                    "wholesale_price",
                    "suggest_price",
                    "end_price",

                    "tag",
                    "weight",
                    "product_length",
                    "product_width",
                    "product_height",

                    "detail",
                    "explain_text",
                    "product_group_id",
                    "cover_photo_img",
                    
                    "active_groupbuying",
                    "media_type",
                    "youtube_url"
            	),
                $attributes 
            );
		return true;
	}

	/**
    *   vaildate you needed fileds
    *   
    *   @param array $vaildateFileds (ex. array( "name", "parent_group_id", "type" ) )
    *	@param array $attributes (ex. array( "name"=>"hello", "price"=>"150", ... ))
    */
    public function vaildateFiled( $vaildateFileds, $attributes ){

        foreach ($vaildateFileds as $key => $value) {
            
            if( !array_key_exists($value, $attributes) ) {
                throw new Exception("Error missing param $key", 1);
            }

            if( is_null($value) ){
	        	throw new Exception("Error the $key filed is null", 1);
	        }

        }

        $this->attributes = $attributes;

    }

    /**
    *   vaildate you needed fileds
    *   
    *	return @array (ex. array( "name"=>"hi", "price"=>"300", ... ))
    */
    public function getAttributes(){
        return array( 
                    "name" 				=> $this->attributes["name"], 
                    
                    "active_maximum"  	=> $this->attributes["active_maximum"], 
                    "active_minimum"  	=> $this->attributes["active_minimum"], 
                    
                    "wholesale_price"  	=> $this->attributes["wholesale_price"],
                    "suggest_price"     => $this->attributes["suggest_price"], 
                    "end_price"  		=> $this->attributes["end_price"],
					"cost_price"  		=> $this->attributes["cost_price"],
					"propose_price"  	=> $this->attributes["propose_price"],

                    "tag" 				=> $this->attributes["tag"],
                    "weight"			=> $this->attributes["weight"],
                    "product_length"	=> $this->attributes["product_length"],
                    "product_width" 	=> $this->attributes["product_width"],
                    "product_height"	=> $this->attributes["product_height"],

                    "detail"  			=> $this->attributes["detail"],
                    "explain_text"  	=> $this->attributes["explain_text"],
                    "product_group_id"  => $this->attributes["product_group_id"],
                    "cover_photo_img" 	=> $this->attributes["cover_photo_img"],
                    
                    "active_groupbuying"=> $this->attributes["active_groupbuying"],
                    "media_type" 		=> $this->attributes["media_type"],
                    "youtube_url" 		=> $this->attributes["youtube_url"],
            );
    }

    /**
    *   convert date 
    *   
    *	return @String (ex. "2015-08-06 00:00:00" )
    */
    public function getDateFormat( $date ){
    	if( $date ){
    		$zone = 'Asia/Taipei';
            date_default_timezone_set( $zone );
            $date = new DateTime( $date );
            $date->setTimezone( new DateTimeZone( $zone ) );
            $result_date = $date->format("Y-m-d H:i:s");
            return $result_date;   
        }
        else{
        	$this->responser->send(array("message" => "Missing parameter [ date ]."), $this->responser->BadRequest());
        }
    }

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}


	public function getList( $pageNo, $pageSize, $attributes = array() ){

		$this->permissionCheck( "read" );
		$attributes = array( "state" => 0 );
		return	$this->superGetRecords( $attributes, $pageNo, $pageSize );

	}

	/**
	*	執行更新的動作
	*
	*	@param array $attributes 要更新的資料內容
	*	@return boolean 成功回傳true
	*/
	public function productUpdate( $product_id ,$user){

		if( !$this->hasPermission( "update" ) ){
			throw new AuthorizationException("Actor haven't permission to update model in " . $this->getTable(), 1);	
		}
		$model = $this->getById( $product_id );
		$options = $this->getAttributes();
        $options['modify_time'] = date("Y-m-d H:i:s");
        $options['master_id'] = $user->getId();
        $result = $model->update($options);
		return $result;
	}

	/**
	*	remove product.
	*
	*	@return effect_row number 
	*/
	public function productRemove( $options ){
		
		$permission = "delete";
		$this->hasPermission( $permission );
		$id = $options['id'];
		if( $this->isActivityStart($id) ) {

			$result = array();

			$model = $this->superGetById( $id );
        	$params = array( "state" => 1 );
        	
        	$result['effectRow'] = $model->update($params);
			$result["id"] = $id;

			return $result;

		}		
		else {
			throw new Exception("Activity is start. $id model in " . $this->getTable(), 1);
		}

	}

	/**
	*	check product is activity or not yet.
	*
	*	@param number $id product's id for query activity time(ready_time)
	*	@return boolean
	*/
	// public function isActivityStart( $productId ){

	// 	$model = $this->superGetById( $productId );
	// 	$activityTime = $model->getAttribute( "ready_time" );
	// 	$now = new DateTime("now");
	// 	if( $now < $activityTime ){
	// 		return false;
	// 	}else{
	// 		return true;
	// 	}

	// }

	/**
	*	Create product.
	*
	*	@return effect_row number 
	*/
	// public function productCreate( $options ) {		
	// 	$this->permissionCheck( "create" );
	// 	return $this->create( $options );
	// }

	public function permissionCheck( $permissions ){

		if( is_array($permissions) ){
			foreach ($permissions as $key => $value) {
				if( !$this->hasPermission( $value ) ){
					throw new AuthorizationException("Actor haven't permission to " . $value . " collection in " . $this->getTable(), 1);	
				}	
			}
		}
		else{
			if( !$this->hasPermission( $permissions ) ){
				throw new AuthorizationException("Actor haven't permission to " . $permissions . " collection in " . $this->getTable(), 1);	
			}
		}

	}
}



?>
