<?php
/**
*  ExportController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'extends/ExporterFactory.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );


class ExportController extends RestController {

    public function __construct() {
        parent::__construct();                
    }

    /**
    *
    *   POST:  /export/excel/<product:\w+>/<category:\w+>
    *
    *   @param $productCategory string which product category you wanted used ( ex. 'wholesale' )
    *   @param $reportCategory  string which category you wanted download ( ex. 'pickup' or 'returned' or 'invoice' )
    *   
    */
    public function exportExcel( $product_category, $report_category ){

            $data = array();

            try {

                $fileName   = $report_category;
                $export     = $this->getExport( $report_category."excel" );
                $export->open('Excel2007');
                
                $template   = $export->createTemplate();
                $entity     = $export->createEntity( $this->params("entity_type") );

                $ids = $this->params("ids");
                $entity->setResource( $ids );

                $export->write( $template, $entity );
                $export->save( $report_category, $fileName );

                $data["fileName"] = $export->getOutputFileName();

                $export->close();

                $this->responser->send( $data, $this->responser->OK() );

            }
            catch(OperationConflictException $e){
                $data['message'] = $e->getMessage();
                $this->responser->send( $data, $this->responser->Conflict() );
            }
            catch(DataAccessResultException $e){
            	$data['message'] = $e->getMessage();
              	$this->responser->send( $data, $this->responser->NotFound() );
            }      
            catch(InvalidAccessParamsException $e) {
              $data['message'] = $e->getMessage();
              $this->responser->send( $data, $this->responser->BadRequest() );
            }
            catch(AuthorizationException $e) {
              $data['message'] = $e->getMessage();
              $this->responser->send( $data, $this->responser->Forbidden() );         
            }
            catch ( Exception $e) {
              $data['message'] = SERVER_ERROR_MSG;
              $data['message'] = $e->getMessage();              		
              $this->responser->send( $data, $this->responser->InternalServerError() );
            }
    }

    // public function getReportInfoList()
    // {
    //     return array(
    //             "pickup"=>array(
    //                     "activity"=>"waitingdelivery",
    //                     "order"=>"paid"
    //                 )
    //         );
    // }

    // public function getReportInfo( $report_category )
    // {
    //     $list = $this->getReportInfoList();
    //     if( !array_key_exists($report_category, $list) )
    //     {
    //         throw new Exception("Error unsport this [ $report_category ] report service.", 1);
    //     }
    //     return $list[ $report_category ];
    // }

    public function getExport( $category )
    {
        $factory = new ExporterFactory( $category );
        return $factory->create();
    }

    public function getEntityList(){
        return array(
                "orderPicking"=>"OrderPickingEntity",
                "returnedSales"=>"ReturnedSalesEntity",
                "invoice"=>"InvoiceEntity",
            );
    }

    public function getEntity( $collection, $category ){
        
        $entitys = $this->getEntityList();
        if( array_key_exists($category, $entitys) ) {
            $class = $entitys[ $category ];
            return new $class( $collection );
        }
        else {
            throw new Exception("Undefined $category collection.", 1);
        }
    }

    /**
    *   get collection list entity
    *   
    *   @return array
    */
    public function getCollectionsList(){

        return array(
            "wholesale"         => "GroupBuyingActivityCollection",
            "wholesale_order"   => "GroupBuyingOrderCollection"
        );
    }    

    /**
    *   get really category collection entity
    *   
    *   @param string category (ex. 'wholesale' or 'retail')
    *   @return collection object
    */
    public function getCollection( $category ) {
        $collections = $this->getCollectionsList();
        if( array_key_exists($category, $collections) ) {
            $collectionName = $collections[ $category ];
            return new $collectionName();
        }
        else {
            throw new Exception("Undefined $category collection.", 1);
        }
    }


}




?>