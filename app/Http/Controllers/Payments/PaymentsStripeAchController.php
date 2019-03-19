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

class PaymentsStripeAchController extends Controller {

	/*
	
	*/
	
	/* --- ACH --- */
	
	private $settings;
	private $user_message = array();

	
	public function __construct() {

		$this->settings = array(
			"plaid_client_id" => "xxx",
			"plaid_secret" => "xx",
			"plaid_public_key" => "xxx",
			"stripe_test_api_key" => "xxx",
			"stripe_live_api_key" => "",
			"sp_environment" => "sandbox",
			"log" => "off",
		
		);

	}

	public function getPaymentAch(){
		
		return View::make('member/payment_ach');
		
	}
	
	public function getBankList(){
		
		return $this->stripe_bank_accounts_list(Auth::user()->id);
		
	}
	
	public function stripe_bank_accounts_list($user_id){
		
		$stripe_key = ( $this->settings['sp_environment'] === 'live' ) ? $this->settings['stripe_live_api_key'] : $this->settings['stripe_test_api_key'];
		
		\Stripe\Stripe::setApiKey($stripe_key);

		$stripe_customer_id_checking = DB::table("users")
			->where("id", "=", $user_id)
			->get();
			
		if (count($stripe_customer_id_checking)>0 && !is_null($stripe_customer_id_checking[0]->stripe_customer_id)) {
			
			$stripe_customer_id = $stripe_customer_id_checking[0]->stripe_customer_id;
			//$customer = \Stripe\Customer::retrieve($stripe_customer_id);
			
			$res = \Stripe\Customer::retrieve($stripe_customer_id)->sources->all(array(
			  "object" => "bank_account"
			));
			
			return $res;
			
		}
			
		return null;
		
	}
	
	public function getDeleteBank(){
		
		$bank_source_id = Input::get('id');
		
		try{
			
			$result = $this->stripe_delete_bank($bank_source_id);
		
		}catch(\Exception $e){
			
			//
			
		}
		
		return Redirect::back();
		
	}

	public function stripe_delete_bank($bank_source_id){
		
		$stripe_key = ( $this->settings['sp_environment'] === 'live' ) ? $this->settings['stripe_live_api_key'] : $this->settings['stripe_test_api_key'];
		
		\Stripe\Stripe::setApiKey( $stripe_key );
		
		$stripe_customer_id_checking = DB::table("users")
			->where("id", "=", Auth::user()->id)
			->get();
		
		if (count($stripe_customer_id_checking)>0 && !is_null($stripe_customer_id_checking[0]->stripe_customer_id)) {
			
			$stripe_customer_id = $stripe_customer_id_checking[0]->stripe_customer_id;
			
			//var_dump($stripe_customer_id);
			//var_dump($bank_source_id);
			
			$delete_result = \Stripe\Customer::retrieve($stripe_customer_id)->sources->retrieve($bank_source_id)->delete();
			
			//var_dump($delete_result);
			
			if(isset($delete_result->deleted) && $delete_result->deleted == true){
				
				return 1;
				
			}
			
		}
		
		return 0;
		
	}
	
	public function stripe_add_bank_account( $token ){

		// Live or test?
		$stripe_key = ( $this->settings['sp_environment'] === 'live' ) ? $this->settings['stripe_live_api_key'] : $this->settings['stripe_test_api_key'];
		
		$return = array( 'error' => false );

		\Stripe\Stripe::setApiKey( $stripe_key );
		
		$stripe_customer_id_checking = DB::table("users")
			->where("id", "=", Auth::user()->id)
			->get();
		
		if (count($stripe_customer_id_checking)>0 && !is_null($stripe_customer_id_checking[0]->stripe_customer_id)) {
			
			$stripe_customer_id = $stripe_customer_id_checking[0]->stripe_customer_id;
			$customer = \Stripe\Customer::retrieve($stripe_customer_id);
			
		} else {
			// Create a Customer:
			$customer = \Stripe\Customer::create(array(
				'email' => Auth::user()->email,
				'source' => $token,
				'description' => ' User: ' . Auth::user()->email
			));

			$stripe_customer_id = $customer->id;

			//Add to user
			
			$update_array = array(
					"stripe_customer_id" => $stripe_customer_id,
				);
			
			DB::table("users")
			->where("id","=",Auth::user()->id)
			->update($update_array);
			
		}

		// Figure out if the user is using a stored bank account or a new bank account by comparing bank account fingerprints
		$token_data = \Stripe\Token::retrieve($token);

		$this_bank_account = $token_data['bank_account'];
		$cust_banks = $customer['sources']['data'];
		$source = null;

		foreach ($cust_banks as $bank) {
			if ($bank['fingerprint'] == $this_bank_account['fingerprint']) {
				$source = $bank['id'];
			}
		}

		// If this bank is not an existing one, we'll add it
		if (is_null($source)) {
			$new_source = $customer->sources->create(array('source' => $token));
			$source = $new_source['id'];
		}

		return $return;
		
	}
	
	
	public function retrieveDescriptionFromTransactionId($transaction_id){
		
		$transactionData = DB::table("_member_transaction_steps")
			->where("id", "=", $transaction_id)
			->get();
		
		$propertyListText = "";
		$totalClosingCosts = 0;
		$totalReserves = 0;
		$resultText = "";
		$totalInvestments = 0;
		
		$property_data_array_json = $transactionData[0]->property_data_array;
		
		if(!is_null($property_data_array_json)){
			
			$property_data_array = json_decode($property_data_array_json, true);
			
			foreach($property_data_array as $transaction_property_item){
				
				$current_property_id = $transaction_property_item["property_id"];
				$current_property_invest_amount = $transaction_property_item["invest_amount"];
				$current_property_invest_ownership_percentage = $transaction_property_item["invest_ownership_percentage"];
				
				$current_property_closing_costs = $transaction_property_item["closing_costs"];
				$current_property_reserves = $transaction_property_item["reserves"];
				
				$cost_reserves = $current_property_closing_costs+$current_property_reserves;
				
				$current_subtotal = $current_property_invest_amount+$cost_reserves;
				
				$totalClosingCosts += $current_property_closing_costs;
				$totalReserves += $current_property_reserves;
				$totalInvestments += $cost_reserves + $current_subtotal + $current_property_invest_amount;
				
				$property_data = DB::table('_property_details')
					->where("id","=",$current_property_id)
					->get();
					
				if(count($property_data)>0){
					
					$property_item = $property_data[0];
					
					$property_address_text = $property_item->address . " " . $property_item->city . ", " . $property_item->state . " " . $property_item->zip;
					
					$propertyListText .= number_format((float)$current_property_invest_ownership_percentage, 0, '.', ',') . "% property " . $property_address_text . " for $" . number_format((float)$current_property_invest_amount, 0, '.', ',') . "; ";
					
				}
				
			}
			
			$resultText = "Payment $". number_format((float)$totalInvestments, 0, '.', ',') ." for: " . $propertyListText . " Total Closing Costs: $" . number_format((float)$totalClosingCosts, 0, '.', ',') . "; Total Reserves: $" . number_format((float)$totalReserves, 0, '.', ',');
		
		}
		
		return $resultText;
		
	}
	
	public function postChargeSource(){
		
		$bank_source_id = Input::get('bank_source_id');
		$transaction_id = Input::get('transaction_id');
		$amount = Input::get('amount');
		$amount = (int)$amount*100;
		
		$amount = 1500*100;//test only!!!
		
		$stripe_customer_id_checking = DB::table("users")
			->where("id", "=", Auth::user()->id)
			->get();
			
		if (count($stripe_customer_id_checking)>0 && !is_null($stripe_customer_id_checking[0]->stripe_customer_id)) {
		
			$stripe_customer_id = $stripe_customer_id_checking[0]->stripe_customer_id;
			
			$description = $this->retrieveDescriptionFromTransactionId($transaction_id);
			
			$chargeResult = $this->charge_stripe_source($amount, $description, $stripe_customer_id, $bank_source_id);
			
			if(isset($chargeResult['result']) && $chargeResult['result']->status == "succeeded"){
				
				//var_dump("SUCCESS");//just for test, for live it always pending few days
				
			}elseif(isset($chargeResult['result']) && $chargeResult['result']->status == "pending"){
				
				//var_dump("PENDING");//might take up to 5 business days
				
				//add to database
				$db_array = array(
					"user_id" => Auth::user()->id,
					"transaction_id" => $transaction_id,
					"stripe_source_id" => $chargeResult['result']->id,
					"amount" => $amount,
					"time_start" => time(),
					"status" => 1
				);
						
				DB::table("_member_transaction_ach_payment")
					->insert($db_array);
						
			}elseif(isset($chargeResult['result']) && $chargeResult['result']->status == "failed"){
				
				
				
			}else{
				
				
				
			}
			
			//var_dump($chargeResult['result']);
			
		
		}
		
		return Redirect::back();
		
	}
	
	public function charge_stripe_source($amount, $description, $stripe_customer_id, $source){
		
		// CHARGING
		$stripe_key = ( $this->settings['sp_environment'] === 'live' ) ? $this->settings['stripe_live_api_key'] : $this->settings['stripe_test_api_key'];
		
		\Stripe\Stripe::setApiKey( $stripe_key );
		
		
		
		// Try to authorize the bank
		$charge_args = array(
			'amount' => $amount,
			'currency' => 'usd',
			'description' => $description,
			'customer' => $stripe_customer_id,
			'source' => $source
		 );

		$return = array( 'error' => false );

		try {

			$charge = \Stripe\Charge::create($charge_args);
			$return['result'] = $charge;

		} catch(\Stripe\Error\Card $e) {

			// Since it's a decline, \Stripe\Error\Card will be caught
			$return = $e->getJsonBody();

		} catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
			$return = $e->getJsonBody();

		} catch (\Stripe\Error\InvalidRequest $e) {

			// Invalid parameters were supplied to Stripe's API
			$return = $e->getJsonBody();

		} catch (\Stripe\Error\Authentication $e) {

			// Authentication with Stripe's API failed
			$return = $e->getJsonBody();

		} catch (\Stripe\Error\ApiConnection $e) {

			// Network communication with Stripe failed
			$return = $e->getJsonBody();

		} catch (\Stripe\Error\Base $e) {

			// Display a very generic error to the user, and maybe send
			$return = $e->getJsonBody();

		} catch (Exception $e) {

			// Something else happened, completely unrelated to Stripe
			$return = $e->getJsonBody();

		}

		// log error if there is any.
		if ( $return['error'] && $this->settings['log'] === 'on' ) {
			$message = 'DESCRIPTION: ' . $description . ' CHARGE: ' . $amount . ' TYPE: ' . $return['error']['type'] . ' PARAM: ' . $return['error']['param'] . ' MESSAGE: ' . $return['error']['message'];
			
		}

		return $return;

	}
	

	public function getAddBank(){
		
		$plaid_bank_token = Input::get('public_token');
		$plaid_account_id = Input::get('account_id');
		
		$return = $this->call_plaid( $plaid_bank_token, $plaid_account_id );
		
		return $return;
		
	}
	
	public function call_plaid( $plaid_bank_token, $plaid_account_id ){

		$env = ( $this->settings['sp_environment'] === 'live' ) ? 'production' : 'sandbox';
		$headers[] = 'Content-Type: application/json';

		$params = array(
			'client_id'    => $this->settings['plaid_client_id'],
			'secret'       => $this->settings['plaid_secret'],
			'public_token' => $plaid_bank_token
		 );

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://" . $env . ".plaid.com/item/public_token/exchange");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 80);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if(!$result = curl_exec($ch)) {
			trigger_error(curl_error($ch));
		}
		curl_close($ch);

		$jsonParsed = json_decode($result);


		$btok_params = array(
			'client_id'    => $this->settings['plaid_client_id'],
			'secret'       => $this->settings['plaid_secret'],
			'access_token' => $jsonParsed->access_token,
			'account_id'   => $plaid_account_id
		 );

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://" . $env . ".plaid.com/processor/stripe/bank_account_token/create");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($btok_params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 80);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if(!$result = curl_exec($ch)) {
			trigger_error(curl_error($ch));
		}
		curl_close($ch);

		$btoken = json_decode($result);

		
		//create bank account in customer's
		return $this->stripe_add_bank_account( $btoken->stripe_bank_account_token );
		
	}

	
	
	
	
	
}