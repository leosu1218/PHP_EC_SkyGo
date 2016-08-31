<?php
/**
*	FareCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );
require_once( FRAMEWORK_PATH . 'models/Fare.php' );

require_once( FRAMEWORK_PATH . 'collections/Fare/SearchFareKeyword.php' );
require_once( FRAMEWORK_PATH . 'collections/Fare/SearchFareActivityId.php' );
require_once( FRAMEWORK_PATH . 'collections/Fare/SearchFareActivityIds.php' );

/**
*	FareCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Fare
*	@author Rex chen <rexchen@synctech.ebiz.tw> Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class FareCollection extends PermissionDbCollection {

    protected $searchConditions = array();
    protected $joinStatement    = array();

    public function __construct(&$dao=null) {
        parent::__construct($dao);
        $this->helper = new AuthenticateHelper();

        $this->searchConditions = array(
            new SearchFareKeyword(),
            new SearchFareActivityId(),
            new SearchFareActivityIds(),
        );

        $this->joinStatement = array(
        );
    }

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
        $this->dao->fresh();
		$conditions = array('and','1=1');
		$params = array();

        $select = array(
            'f.id id',
            'f.amount amount',
            'f.type type',
            'f.target_amount target_amount',
            'f.global global',
        );

		$this->dao->from("$table f");
		$this->dao->group('f.id');

        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->joinStatement);
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->searchConditions);

        $this->dao->select($select);
		$this->dao->where($conditions,$params);

		$result['recordCount'] = intval($this->dao->queryCount());
		$result['totalRecord'] = $result['recordCount']; 		
		$result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
		$this->dao->paging($pageNo, $pageSize);		
		$result["records"] = $this->dao->queryAll();

		return $result;
    }

    /**
     * Append search condition's statement for search records sql.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function appendStatements(DbHero &$dao, &$params, &$conditions, &$select, &$search, &$sqlStatements) {
        foreach ($sqlStatements as $key => $statement) {
            $statement->append($dao, $params, $conditions, $select, $search);
        }
    }

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "fare";
	}

	public function getModelName() {
		return "Fare";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		
		if(array_key_exists("id", $attributes)) {
			throw new Exception("Can't write the attribute 'id'.");
		}

		return true;
	}

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}
}



?>
