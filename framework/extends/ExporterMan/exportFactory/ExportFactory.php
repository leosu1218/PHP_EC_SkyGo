<?php
/**
*	ExportFactory code
*
*	PHP version 5.3
*
*	@category export
*	@package ExportFactory
*	@author Rex Chen <rexchen@synctech-infinity.com>, Jai Chien <jaichien@synctech-infinity.com>
*	@copyright 2015 synctech.com
*/

interface ExportFactory
{
	/**
	*	ExportFactory use template output format.
	*
	*	@return $template	
	*/
	public function createTemplate();

	/**
	*	ExportFactory use entity output records and prepare data
	*	for export format.
	*
	*	@param $type string The trade result state.	
	*	@return $entity
	*/
	public function createEntity( $type );

	// public function wirte(ExportTemplate $template , ExportEntity $entity);
	// public function open();
	// public function close();
}


?>