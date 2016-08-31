<?php
/**
 *  NewebOtherServiceProvider code.
 *
 *  PHP version 5.3
 *
 *  @category service provider
 *  @package provider
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
require_once(dirname(__FILE__) . '/../CashFlowServiceProvider.php');
require_once(dirname(__FILE__) . '/NewebOtherServiceConfig.php');
require_once(dirname(__FILE__) . '/GeneralNewebOtherServiceConfig.php');
require_once(FRAMEWORK_PATH . 'extends/LoggerHelper.php');
require_once(FRAMEWORK_PATH . 'extends/ValidatorHelper.php');

class NewebOtherServiceProvider implements CashFlowServiceProvider {

    private $log;
    private $validator;
    private $config = null;

    public function __construct(NewebOtherServiceConfig $config=null) {
        if(is_null($config)) {
            $this->config = new GeneralNewebOtherServiceConfig();
        }
        else {
            $this->config = $config;
        }


        $this->validator 	= new ValidatorHelper();
        $this->log 			= new LoggerHelper(
            $this->config->LOG_PREFIX(),
            $this->config->LOG_PATH());
    }

    /**
     * Logging trade transaction behavior.
     * @param array $param
     * @param string $text
     */
    public function info($param=array(), $text='') {
        $param = array_merge($param, $_SERVER);
        $this->log->info("[Neweb-other trade] $text \r\n" . json_encode($param) . "\r\n");
    }

    /**
     * Logging trade transaction behavior.
     * @param array $param
     * @param string $text Transaction behavior message.
     */
    public function error($param=array(), $text='') {
        $param = array_merge($param, $_SERVER);
        $this->log->error("[Neweb-other trade] $text \r\n" . json_encode($param) . "\r\n");
    }


    /**
     * Get due date for payment params.
     * @param int $days
     * @return bool|string
     */
    private function getDueDate($days=1) {
        $date = strtotime("+$days day", strtotime(date('Y-m-d')));
        return date("Ymd", $date);
    }

    /*  CashFlowServiceProvider Methods. */
    /**
     * Create params for third party cash flow api.
     * @param array $orderInfo The info of prepare order.
     * @return array Params for api.
     */
    public function createPaymentParams($orderInfo) {

        $checksum 	= $this->getChecksum($orderInfo['serial'], $orderInfo['final_total_price']);


        return array(
            'merchantnumber' 	=> $this->config->MERCHANT_NUMBER(),
            'ordernumber' 		=> $orderInfo['serial'],
            'amount' 			=> $orderInfo['final_total_price'],
            'providerUrl'		=> $this->config->PROVIDER_URL(),
            "bankid"            => $this->config->BANK_ID(),
            'returnvalue'       => false,
            'nexturl'           => $this->config->RETURN_URL(),
            'hash'              => $checksum,
            'paymemo'           => "",
            'paytitle'          => "",
            'paymenttype'       => $orderInfo['payment_type'],

            // 24hr market params
            'payname'           => $orderInfo['receiver_name'],
            'payphone'          => $orderInfo['receiver_phone_number'],
            'duedate'           => $this->getDueDate($this->config->DUE_DAYS()),
        );
    }

    /**
     * Get order's checksum.
     * @param $serial
     * @param $price
     * @return string
     */
    public function getChecksum($serial, $price) {

        $checksum = md5(
            $this->config->MERCHANT_NUMBER().
            $this->config->HASH_SALT().
            $price.
            $serial
        );

        return $checksum;
    }

    /**
     * Get outer trade number.
     * @param $id
     * @return mixed
     */
    public function getOuterTradeNo($id) {
        return $id;
    }

    /**
     * Check param is correctly.
     * @param array $param The received notify params.
     * @throws InvalidAccessParamsException
     */
    public function requireParams($param=array()) {
        $this->validator->requireAttribute('merchantnumber', $param);
        $this->validator->requireAttribute('ordernumber', $param);
        $this->validator->requireAttribute('amount', $param);
        $this->validator->requireAttribute('paymenttype', $param);
        $this->validator->requireAttribute('serialnumber', $param);
        $this->validator->requireAttribute('writeoffnumber', $param);
        $this->validator->requireAttribute('timepaid', $param);
        $this->validator->requireAttribute('tel', $param);
        $this->validator->requireAttribute('hash', $param);
    }

    /**
     * Checking received params is valid by checksum.
     * @param array $param The received notify params.
     * @return bool Return true when checksum valid.
     */
    public function isValidChecksum($param=array()) {
        $hash = md5(
            "merchantnumber=".$param['merchantnumber'].
            "&ordernumber=".$param['ordernumber'].
            "&serialnumber=".$param['serialnumber'].
            "&writeoffnumber=".$param['writeoffnumber'].
            "&timepaid=".$param['timepaid'].
            "&paymenttype=".$param['paymenttype'].
            "&amount=".$param['amount'].
            "&tel=".$param['tel'].
            $this->config->HASH_SALT()
        );

        return (strtolower($param['hash'])==strtolower($hash));
    }

    /**
     * Process order trade callback notify.
     * @param array $param The callback info for notify trade result.
     * @param CashFlowOrderCollection $order The collection of order that for recording trade result.
     * @param CashFlowUserNotify $notify
     * @return bool Return true when trade authentication success.
     */
    public function receiveNotify($param=array(), CashFlowOrderCollection $order, CashFlowUserNotify $notify=NULL) {
        try {
            $this->requireParams($param);
            if($this->isValidChecksum($param)) {
                $result = $order->recordResult($order->getTradeSuccessState(), $param['ordernumber']);
                $this->info($param, "Trade successful.");

                if(!is_null($notify)) {
                    $notify->send($result);
                }

                return true;
            }
            else {
                throw new CashFlowNotifyResultException("Invalid trade checksum !", 1);
            }
        }
        catch(CashFlowNotifyResultException $e) {
            $this->error($param, $e->getMessage());
            $order->recordResult($order->getTradeErrorState(), $param['ordernumber']);
            throw $e;
        }
        catch(Exception $e) {
            $this->error($param, $e->getMessage());
            throw $e;
        }
    }
}




?>