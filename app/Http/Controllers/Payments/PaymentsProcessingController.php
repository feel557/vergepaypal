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

class PaymentsProcessingController extends Controller {



	public function postPayCardBt(){
		
		$productId = Input::get('product_id');
		$productQty = Input::get('product_qty');
		
		$firstName = Input::get('first_name');
		$lastName = Input::get('last_name');
		$email = Input::get('email');
		
		$cardNumber = Input::get('card_number');
		$cardCVC = Input::get('cvc');
		$exp_month = Input::get('exp_month');
		$exp_year = Input::get('exp_year');
		$billingZip = Input::get('zip');
		
		
		$errors = 0;
		$error_message = "";
		
		if(!isset($productId) or empty($productId)){$errors++; $error_message .= "product id error, ";}
		if(!isset($productQty) or empty($productQty)){$errors++; $error_message .= "product qty error, ";}
		if(!isset($firstName) or empty($firstName)){$errors++; $error_message .= "first name error, ";}
		if(!isset($lastName) or empty($lastName)){$errors++; $error_message .= "last name error, ";}
		if(!isset($cardNumber) or empty($cardNumber)){$errors++; $error_message .= "card number error, ";}
		if(!isset($cardCVC) or empty($cardCVC)){$errors++; $error_message .= "cvc error, ";}
		if(!isset($exp_month) or empty($exp_month)){$errors++; $error_message .= "exp_month error, ";}
		if(!isset($exp_year) or empty($exp_year)){$errors++; $error_message .= "exp_year error, ";}
		if(!isset($billingZip) or empty($billingZip)){$errors++; $error_message .= "zip error, ";}
		
		if($errors > 0){
			
			return Redirect::back()->withErrors(array("errors" => "Please fill all required fields: ".$error_message));
			
		}
		
		//if data is ok, insert order to db
		
		// check customer
		
		$getCustomerData = DB::table("users")
			->where("email", "=", $email)
			->get();
		
		if(count($getCustomerData)>0){
			
			$customerId = $getCustomerData[0]->id;
			
		}else{
			
			$customerId = DB::table("users")
			->insertGetId(array(
				"user_type" => 3,
				"email" => $email,
				"first_name" => $firstName,
				"last_name" => $lastName,
			));
			
		}
		
		
		$getProductData = DB::table("_products")
			->where("id", "=", $productId)
			->get();
		
		//check prod existence
		
		
		
		$amount = $getProductData[0]->price * $productQty;
		
		// payment_status 0 = no, 1 = initiated, 2 = successful, 3 = errors
		$db_array = array(
			"product_id" => $productId,
			"product_qty" => $productQty,
			"customer_id" => $customerId,
			"total_amount" => $amount,
			"payment_status" => 1,
		);
			
		$orderId = DB::table("_orders")
			->insertGetId($db_array);
		
		
		$paymentData = array(
			"credit_card" => array(
				"cardholderName" => $firstName ." ". $lastName,
				"number" => $cardNumber,
				"cvv" => $cardCVC,
				"expirationYear" => $exp_year,
				"expirationMonth" => $exp_month,
				),
			"billingZip" => $billingZip,
			"amount" => $amount,
			"orderId" => $orderId
		);
		
		// payment transaction
		
		
		
		$PaymentsBraintreeController = new \App\Http\Controllers\Payments\PaymentsBraintreePController();
		$result = $PaymentsBraintreeController->addSale($paymentData);
		
		$errors = 0;
		$error_mess = "";
		
        if($result->success){
			
            $transaction = $result->transaction;

            $t_id = $transaction->id;
            $t_authCode = $transaction->processorAuthorizationCode;
            $t_ts = $transaction->updatedAt;
            $t_amount = $transaction->amount;

            // create payment history instance; create your own payments table as you see fit.

			$payment_arr = array(
				'updatedAt' => $t_ts,
				'reference_number' => $t_id,
				'auth_code' => $t_authCode,
				'card_type' => $transaction->creditCardDetails->cardType,
				'cc_last_four' => $transaction->creditCardDetails->last4,
				'cardholder_name' => $transaction->creditCardDetails->cardholderName,
			);

			$db_array = array(
				"payment_status" => 2,
				"payment_log" => json_encode($payment_arr)
				
			);
				
			DB::table("_orders")
			->where("id", "=", $orderId)
			->update($db_array);

			
			
        }elseif($result->errors->deepSize() > 0) {
			
			$v = $result->errors;
			$r = new \ReflectionObject($v);

			$me = $r->getName() .' {' . implode(', ', array_map(
				 function($p) use ($v) {
					 $p->setAccessible(true);
					 return $p->getName() .': '. $p->getValue($v);
				 }, $r->getProperties())) .'}';
			
			$error_mess = $me;
			$errors++;
			
		} else {
			
			$error_mess = "Code" . $result->transaction->processorSettlementResponseCode . ", Text: " . $result->transaction->processorSettlementResponseText;
			$errors++;
			
		}
		
		
		if($errors > 0){
			
			return Redirect::back()->withErrors(array("errors" => 'Transaction was not successful. Reason: '.$error_mess));
			
        }
		
	}




}