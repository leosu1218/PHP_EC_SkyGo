<?php 


require_once( dirname(__FILE__) . '/exportFactory/ExcelExportFactory.php' );
require_once( dirname(__FILE__) . '/templates/ExcelInvoiceTemplate.php' );
require_once( dirname(__FILE__) . '/entitys/GroupBuyInvoiceEntity.php' );

/**
*  InvoiceExcelExportFactory can help you exporter file
*
*	PHP version 5.3
*
*	@category OrderPickingEntity
*	@package Exporter
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*
*/
class InvoiceExcelExportFactory extends ExcelExportFactory
{
	
	function __construct()
	{	
		parent::__construct();
	}

	public function createTemplate()
	{
		return new ExcelInvoiceTemplate();
	}

	public function createEntity( $entity_type )
	{
		return $this->getEntity( $entity_type );
	}

	/**
    *   get collection list entity
    *   
    *   @return array
    */
    private function getEntityList(){

        return array(
            "groupbuying"    => "GroupBuyInvoiceEntity"
        );
    }    

    /**
    *   get really type collection entity
    *   
    *   @param string category (ex. 'groupbuying' or 'gernal')
    *   @return collection object
    */
    private function getEntity( $type ) {
        $entitys = $this->getEntityList();
        if( array_key_exists($type, $entitys) ) {
            $className = $entitys[ $type ];
            return new $className();
        }
        else {
            throw new Exception("Undefined $type collection.", 1);
        }
    }

	public function getReportName()
	{
		return "invoiceexcel";
	}

}




?>