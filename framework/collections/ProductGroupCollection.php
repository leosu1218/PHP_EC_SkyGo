<?php
/**
*	ProductGroupCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Product
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'collections/GroupBuyingMasterUserHasProductGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/ProductGroup.php' );


/**
*	ProductGroupCollection Access Product entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package ProductGroup
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class ProductGroupCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "product_group";
	}

	public function getModelName() {
		return "ProductGroup";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		
		// if(array_key_exists("id", $attributes)) {
		// 	throw new Exception("Can't write the attribute 'id'.");
		// 	return false;
		// }

		// if(!array_key_exists("domain_name", $attributes)) {
		// 	throw new Exception("Attributes should be has 'domain_name'.", 1);
		// }

		// if(!array_key_exists("group_id", $attributes)) {
		// 	throw new Exception("Attributes should be has 'group_id'.", 1);
		// }

		// if(!array_key_exists("account", $attributes)) {
		// 	throw new Exception("Attributes should be has 'account'.", 1);
		// }

		// if(!array_key_exists("hash", $attributes)) {
		// 	throw new Exception("Attributes should be has 'hash'.", 1);
		// }

		// if(!array_key_exists("salt", $attributes)) {
		// 	throw new Exception("Attributes should be has 'salt'.", 1);
		// }

		return true;
	}

	/**
	*	Get Primary key attribute name.
	*	
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}

	/**
	*	Default sub search for collection self use.
	*
	*	@param $ids The array is you wanted search.
	*	@return array 	array all of records.
	*/
	private function getDefaultSubSearch( $ids ){
		$channel = "wholesale";
		$typeString = "all";
		$pageNo = 1;
		$pageSize = 999999;
		$searchParams["parentIds"] = $ids;
		$data = $this->search( $channel, $typeString, $pageNo, $pageSize, $searchParams );
		return $data['records'];
	}

	/**
	*	Search all of product group ids sub node id list. 
	*
	*	@param $ids The array is you wanted search.
	*	@return array 	array all of id.
	*/
	private function getSubSearch( $ids = array() ){
		$result = array(); 

		if( !empty($ids) ){

			$newSearchIds = array();
			$data = $this->getDefaultSubSearch( $ids );
			foreach ($data as $key => $record) {
				if( !in_array($record, $result) ){
					array_push($result, $record["id"]);
					array_push($newSearchIds, $record["id"]);
				}
			}
			if( !empty($newSearchIds) ){
				$subResult = $this->getSubSearch($newSearchIds);
				foreach ($subResult as $index => $subRecord) {
					array_push($result, $subRecord);
				}
			}

		}

		return $result;

	}

	/**
	*	Search all of product group ids sub node id list. 
	*
	*	@param $productGroupIds array The group ids.
	*	@return array 	array all of id.
	*/
	public function searchProductGroupSubIds( $productGroupIds=array() ) {
		
		$ids = $this->getSubSearch($productGroupIds);
		foreach ($productGroupIds as $key => $id) {
			if( !in_array($id, $ids) ){
				array_push($ids, (string)$id);
			}
		}
		return $ids;
	}

	public function search( $channel, $typeString, $pageNo, $pageSize, $search )
	{
		$result = $this->getDefaultRecords($pageNo, $pageSize);	
		$table = $this->getTable();
		$conditions = array('and');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			'pg.id id',
			'pg.name name',
			'pg.parent_group_id parent_group_id',
			'pg.type type',
		));

		$this->dao->from("$table pg");
		$this->dao->group('pg.id');

		// $mapping = $this->getMappingQuery();
		// $parent_group_id = $mapping[$channel]['all']['parent_group_id'];

		// array_push($conditions, 'pg.parent_group_id = :parent_group_id');
		// $params[':parent_group_id'] = $parent_group_id;

		// array_push($conditions, 'pg.type = :type');
		// $params[':type'] = $mapping[$channel][$typeString]["type"];

		if(array_key_exists('keyword', $search) && !empty($search['keyword']) )
		{
			array_push($conditions, "(pg.id like :pgid or pg.name like :pgname)");
			$params[':pgid'] = "%".$search['keyword']."%";
			$params[':pgname'] = "%".$search['keyword']."%";
		}

		if(array_key_exists('parentId', $search) && !empty($search['parentId']) )
		{
			array_push($conditions, "(pg.parent_group_id = :parent_group_id)");
			$params[':parent_group_id'] = $search['parentId'];
		}

		if(array_key_exists('parentIds', $search) && !empty($search['parentIds']) )
		{
			$statement = "";
            foreach($search["parentIds"] as $index => $id) {
                $statement .= "pg.parent_group_id=:parentId$index OR ";
                $params[":parentId$index"] = $id;
            }
            $statement = substr($statement, 0, -3);
            array_push($conditions, "($statement)");
		}

		$this->dao->where($conditions,$params);
	
		// Backward compatible.
		$result['recordCount'] = intval($this->dao->queryCount());
		$result['totalRecord'] = $result['recordCount']; 		
		$result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
		$this->dao->paging($pageNo, $pageSize);
		$result["records"] = $this->dao->queryAll();

		return $result;
	}

	/**
	*	Get query node mapping array.
	*
	*	@return array 
	*/
	public function getMappingQuery(){

		return array(
				"wholesale" => array(
						"sub"		=>array( "type"	=> "1" ),
						"product"	=>array( "type"	=> "2" ),
						"all" 		=>array( "parent_group_id"	=> "1" )
					),
				"retail" => array(
						"sub"		=>array( "type"	=> "3" ),
						"product"	=>array( "type"	=> "4" ),
						"all" 		=>array( "parent_group_id"	=> "2" )
					)
			);

	}

	/**
	*	Get create query config array.
	*
	*	@return array 
	*/
	public function getCreateConfig( $options ){
		
		$mapping 	= $this->getMappingQuery();
		$queryData 	= $mapping[ $options["channel"] ][ $options["type"] ]; 
        $data 		= array(
            "name" 				=> $options["name"],
            "parent_group_id" 	=> $options["parent_group_id"],
        	"channel"			=> $options["channel"]
        );
        $config = array_merge( $data, $queryData );

        return $config;
	
	}
	
	/**
	*	Create product group
	*
	*	@return effect_row number 
	*/
	public function productGroupCreate( $options ){
		
		if($this->hasPermission( "create" )) {
			
			$result = array();

			$args = array(
					"name"				=> $options["name"],
					"parent_group_id"	=> $options["parent_group_id"],
					"type"				=> $options["type"]
				);
			$result["productGroupCreate"] = $this->supercreate( $args );
			$id = $this->lastCreated()->getId();
			$collection = new GroupBuyingMasterUserHasProductGroupCollection();
			$result["masterUserHasProductGroup"] = $collection->appendMasterHasProductGroup($id, $options['parent_group_id']);

			return $result;
		
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to create model in " . $this->getTable(), 1);		
		}

	}

	/**
	*	Get create query config array.
	*
	*	@return effect_row number 
	*/
	public function productGroupRemove( $options ){

		if($this->hasPermission( "delete" )) {

			$model = $this->superGetById( $options["id"] );

			$result = array();
			$result["effectRow"] = $model->destroy();
			$result["id"] = $options["id"];

			return $result;
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to list model in " . $this->getTable(), 1);		
		}

	}

	/**
	*	Get channel node records.
	*
	*	@param $channel 		string which channel you wanted ( ex. 'wholesale' / 'retail' )
	*	@param $channelType 	string which node type you wanted ( ex. 'sub' / 'product' / 'all' )
	*	@param $pageNo 			number
	*	@param $pageSize		number 
	*	@return array 		array(
	*		'pageNo' => 1,
	*		'pageSize' => 10,
	*		'records' => array(
	*			array(
	*				'id'=>3,
	*				'name'=>wholesale_other
	*				'parent_group'=>1,
	*				'type'=>2
	*			),
	*			array(
	*				'id'=>11,
	*				'name'=>wholesale_test
	*				'parent_group'=>1,
	*				'type'=>2
	*			)
	*		),
	*		'totalPage' => 1
	*	)
	*/
	public function getNodeRecords( $channel, $groupType, $pageNo, $pageSize ){	

		if($this->hasPermission( "list" )) {

			$mapping = $this->getMappingQuery();

			if(array_key_exists($channel, $mapping)) {
				if( array_key_exists( $groupType, $mapping[$channel] ) ) {
					$attributes = $mapping[ $channel ][ $groupType ];					
					return $this->superGetRecords( $attributes, $pageNo, $pageSize );
				}
				else {
					throw new Exception( "Invalid group type [$groupType].", 1);
				}
			}
			else {
				throw new Exception( "Invalid channel [$channel].", 1);
			}
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to list model in " . $this->getTable(), 1);		
		}
	}
}



?>
