<?php namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;
use Auth;
use Excel;
use Hash;
use Mail;

class PaymentsBraintreePController extends Controller {

	/*
	
	
	*/
	
    public function addSale($paymentData){
		
        \Braintree_Configuration::environment('production');
        \Braintree_Configuration::merchantId('xxx');
        \Braintree_Configuration::publicKey('xxx');
        \Braintree_Configuration::privateKey('xxx');

        $result = \Braintree_Transaction::sale($paymentData);
		
		/*
		[
            'amount' => $paymentData["amount"],
            'creditCard' => $paymentData["credit_card"],
            'orderId' => $paymentData["orderId"],
            'billing' => [
                'postalCode' => $paymentData["billingZip"],
            ],
            'options' => [
                'submitForSettlement' => True,
				//'storeInVaultOnSuccess' => true,
            ]]
		*/

		return $result;
		
    }

	




}