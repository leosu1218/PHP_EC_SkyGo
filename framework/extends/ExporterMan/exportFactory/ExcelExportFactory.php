<?php


require_once( dirname(__FILE__) . '/ExportFactory.php' );
require_once( dirname(__FILE__) . '/../libs/PHPExcel.php' );

abstract class ExcelExportFactory implements ExportFactory
{	

	function __construct()
	{	
		// parent::__construct();
		$this->export = new PHPExcel();

		//use var
		// $this->excelFormat 	= NULL;
		$this->excelField 	= array();

		/* php excel setting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Taipei');
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	}

	/**
	*	ExportFactory use template output format.
	*
	*	@return $template	
	*/
	abstract function createTemplate();

	/**
	*	ExportFactory use entity output records and prepare data
	*	for export format.
	*
	*	@param $type string The trade result state.	
	*	@return $entity
	*/
	abstract function createEntity( $type );

	abstract function getReportName();

	public function open( $format )
    {
        $this->excelFormat = $format;
    }

    /**  write export flow start  **/
	public function write( ExportTemplate $template , ExportEntity $entity )
	{
		$this->excelSetting( $template->getConfig() );

		$field_data 	= $template->getField();
		$records_data 	= $entity->getRecords();

		$this->setTemplateField( $field_data );
		$this->setRecords( $records_data );

		$this->execute();
	}

	public function excelSetting( $properties )
	{
		 $this->export->getProperties()->setCreator( $properties["creator"] )
                        ->setLastModifiedBy( $properties["lastModifiedBy"] )
                        ->setTitle( $properties["title"] )
                        ->setSubject( $properties["subject"] )
                        ->setDescription( $properties["description"] )
                        ->setKeywords( $properties["keywords"] )
                        ->setCategory( $properties["category"] );
	}

	public function setTemplateField( $field )
	{
		$this->template_field = $field; //array
	}

	public function setRecords( $data )
	{
		$this->sheets = $data;
	}

	public function execute()
	{

		foreach ($this->sheets as $sheetIndex => $sheet) {

			$this->export->createSheet();
            $this->export->setActiveSheetIndex($sheetIndex);

			$this->prepareFieldExcel( $sheetIndex, $sheet['fields'] );
			$this->prepareRecordExcel( $sheetIndex, $sheet["records"] );

			$title = " work sheet ".$sheetIndex."";
			$this->export->getActiveSheet()->setTitle( $title );
		}

	}

	public function prepareFieldExcel( $sheet, $data )
	{
		
		$ascii 		= 65;
        $rowNumber 	= 1;
        $cellField 	= array();

        foreach ($data as $number => $field_value) {
                
            $fieldString 				= $this->getFieldTitleString( $field_value );

            if($fieldString)
            {
            	$cellString 				= chr($ascii) . $rowNumber;
            	$cellField[ $field_value ]	= chr($ascii);

            	$this->setSheetToExcel( $sheet, $cellString, $fieldString );

            	$ascii++;
            }

        }
        array_push( $this->excelField, $cellField );
	}

	public function prepareRecordExcel( $sheet, $records )
	{
	    $ascii = 65;
	    $cellField = $this->excelField[$sheet];

		$rowNumber = 2;
        foreach ($records as $key => $record) {

           foreach ($record as $field => $value) {

                if( array_key_exists( $field, $cellField) )
                {
                	$cellString = $cellField[ $field ] . $rowNumber;
                	$this->setSheetToExcel( $sheet, $cellString, $value );
                }

           }

           $rowNumber++;
        }
	}

	public function getFieldTitleString( $field_value )
	{
		if( array_key_exists($field_value, $this->template_field) )
		{
			return $this->template_field[ $field_value ];
		}
		return false;
	}

	public function setSheetToExcel( $index, $cellString, $cellValue )
	{
		$this->export
			// ->setActiveSheetIndex($index)
			->getActiveSheet()
			->setCellValue( $cellString, $cellValue );
	}
	/**  write export flow end  **/


	/**  save export flow start **/
	public function save( $dirName, $fileName )
	{
        $name = urlencode( $fileName.".xlsx");
        $this->filename = $this->getDateTime() . $name;
		$this->excelWrite( $dirName, $this->filename, $this->excelFormat );
	}

	public function excelWrite( $dirName, $filename, $format )
	{
		ob_end_clean();
		header("Content-type: text/html; charset=utf-8");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;filename=" . $filename);

        $writer = PHPExcel_IOFactory::createWriter( $this->export, $format );
        $writer->save( REPORT . $dirName . "/" . $filename );
	}
	/**  save export flow end **/

	/**  close export flow start **/
	public function close()
	{

	}
	/**  close export flow end **/

	public function getOutputFileName()
	{
		return $this->filename;
	}

	public function getDateTime()
	{
		$datetime = new DateTime("now");
        $date = $datetime->format('Y-m-d His')." ";
        return $date;
	}

	

}

?>