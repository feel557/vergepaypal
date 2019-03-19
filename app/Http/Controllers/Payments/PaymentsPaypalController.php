<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;
use Auth;
use Excel;
use Hash;
use Mail;
use Response;


/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


/*

ROUTES

	Route::get('paywithpaypal', array('as' => 'addmoney.paywithpaypal','uses' => 'AddMoneyController@payWithPaypal',));
	Route::post('paypal', array('as' => 'addmoney.paypal','uses' => 'AddMoneyController@postPaymentWithpaypal',));
	Route::get('paypal', array('as' => 'payment.status','uses' => 'AddMoneyController@getPaymentStatus',));
		

*/

class PaymentsPaypalController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
        
        /** setup PayPal api context **/
		
		
        $paypal_conf = \Config::get('paypal');
		
		//var_dump($paypal_conf);
		
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    
	
    public function paypalPayment($paymentData){
	

		/*
			
			$paymentData = array(
				"order_id" => ,
				"amount" => ,
				"url_payment_status" => ,
			);
	
		*/

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
		
		
        $item_1 = new Item();
        $item_1->setName('Order-'.$paymentData["order_id"]) /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($paymentData["amount"]); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
		
		
        /*
			$tax_amount = 0.0975*$order[0]->total;
			$shipping_amount = 3;
			
			$order_total_amount = $order[0]->total + $tax_amount + $shipping_amount;
		*/
		/*
			$details = new Details();
			$details->setShipping($shipping)
			->setTax($tax)
			->setSubtotal($order[0]->total);
		*/
	
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($paymentData["amount"]);
			//->setDetails($details);
			
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('order '. $paymentData["order_id"] .' transaction');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl( $paymentData["url_payment_status"] ) /** Specify return URL **/
            ->setCancelUrl( $paymentData["url_payment_status"] );
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
            /** dd($payment->create($this->_api_context));exit; **/
			
        try {
			
            $payment->create($this->_api_context);
			
        } catch (\PayPal\Exception\PPConnectionException $ex) {
			
            if (\Config::get('app.debug')) {
				
				return array(
					"success" => false,
					"error_message" => 'Connection timeout'
				);
				
            } else {
				
				return array(
					"success" => false,
					"error_message" => 'Some error occur, sorry for inconvenient'
				);
				
            }
        }
		
		$redirect_url = null;
        foreach($payment->getLinks() as $link) {
			
            if($link->getRel() == 'approval_url') {
				
                $redirect_url = $link->getHref();
                break;
				
            }
			
        }
		
		if(!is_null($redirect_url)){
				
			return array(
				"success" => true,
				"payment_id" => $payment->getId(),
				"redirect_url" => $redirect_url
			);
			
		}
		
		return array(
			"success" => false,
			"error_message" => 'Unknown error occurred'
		);
		
    }
	
	
	
	
	
	
	
	
    public function getPaymentStatus($payment_id, $PayerID, $token){
		
       // if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
		if(empty($PayerID) || empty($token)){
			
			return array(
				"success" => false,
				"error_message" => 'Payment failed'
			);
			
        }
		
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later **/
		
        if ($result->getState() == 'approved') { 
            
			return array(
				"success" => true
			);
		
        }
		
		return array(
			"success" => false,
			"error_message" => 'Payment failed'
		);
		
    }
	
}