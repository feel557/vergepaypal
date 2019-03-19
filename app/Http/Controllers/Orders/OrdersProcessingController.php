<?php namespace App\Http\Controllers\Orders;

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
//use App\Http\Controllers\ExternalApi\ExternalExchanges\Bittrex\BittrexClass;
use App\Http\Controllers\ExternalApi\BittrexController;
use Twilio\Rest\Client;

use Twispay\Entity\Customer\Customer;
use Twispay\Entity\Order\Currency;
use Twispay\Entity\Order\OrderPurchase;
use Twispay\Exception\ValidationException;
use Twispay\Payment;
use Twispay\PaymentForm;




class OrdersProcessingController extends Controller {

	private $api_token = "xxx";

	
	
	public function sendToBackendApi($url, $postDataArray){
		
		// SEND ORDER TO BACKEND SERVER
		
		$postDataArray["api_token_ru48dhd8d9"] = $this->api_token;
		
		
		try {
			
			$ch = curl_init();

			if (FALSE === $ch)
				throw new \Exception('failed to initialize');

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			//CURLOPT_POST => true,
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postDataArray));
			//CURLOPT_HEADER => true,
			//CURLOPT_FOLLOWLOCATION => true,
			//CURLOPT_AUTOREFERER => true,
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$content = curl_exec($ch);

			if (FALSE === $content)
				throw new \Exception(curl_error($ch), curl_errno($ch));

			// ...process $content now
		} catch(\Exception $e) {

			trigger_error(sprintf(
				'Curl failed with error #%d: %s',
				$e->getCode(), $e->getMessage()),
				E_USER_ERROR);

		}
	
		//var_dump($content);
		
		
		/*
		$options = array(
		
			CURLOPT_CUSTOMREQUEST => "POST",
			//CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($postDataArray),
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_HEADER => true,
			//CURLOPT_FOLLOWLOCATION => true,
			//CURLOPT_AUTOREFERER => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			//CURLOPT_SSL_VERIFYPEER => false,

		);
		
		$ch = curl_init($url);
		
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);
*/
		curl_close($ch);
	
		$result = json_decode($content);
		
		return $result;
		
	}
	
	function isValidEmail($email) {
		
		return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);

	}
	
	function twilioSend($phone, $message){
	
		// test success +15005550006
		// test failed +15005550001
	
		//$sid = "xxx"; // trial
		//$token = "xxx"; // trial
		
		$sid = "xxx"; // live
		$token = "xxx"; // live

		try{
			
			$client = new \Twilio\Rest\Client($sid, $token);
			$message = $client->messages->create(
			  $phone,//'+1xxx', // Text this number
			  array(
				'from' => '+1xxx', // From a valid Twilio number
				'body' => $message
			  )
			);

		}catch(\Twilio\Exceptions\RestException $e){
			
			//var_dump($e);
			//echo "<br>";
			
			return array(
				"success" => false,
				"message" => $e->getMessage()
				);
			
		}
		
		return array(
				"success" => true,
				"message" => "",
				"result" => $message
				);
		
	}
	
	function getPhoneVerification(){
		
		////return 1;//ONLY FOR TEST
		
		$recaptchaResult = $this->checkRecaptcha(Input::get("g_recaptcha_response"));
		
		if($recaptchaResult == true){
		
			$phone = Input::get('phone');
			$country_code = Input::get('country_code');
			
			$output_phone = preg_replace('/[^0-9]/', '', $phone);
			$output_code = preg_replace('/[^0-9]/', '', $country_code);
			
			$result_phone = "+".$output_code.$output_phone;
			
			//var_dump($result_phone);
			
			$digits = 4;
			$rnd_str = rand(pow(10, $digits-1), pow(10, $digits)-1);
			
			$message = "Your code: ".$rnd_str;
			
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$time = microtime();
			
			$userHash = md5( $user_ip . $time . $phone );
			
			$res = $this->twilioSend($result_phone, $message);
			
			//var_dump($res);
			
			if(isset($res["success"]) && $res["success"] == true){
				
				$db_array = array(
								"timest_ux" => time(),
								"phone" => $result_phone,
								"user_ip" => $user_ip,
								"user_hash_id" => $userHash,
								"sent_phone_code" => $rnd_str
								);
								
				DB::table("_user_phone_verification")
						->insert($db_array);
						
				\Session::put('customerHashId',$userHash);
						
				return 1;
				
			}
			
		}
		
		return 0;
		
	}
	
	
	
	function getPhoneCheck(){
		
		////return 1;//ONLY FOR TEST
		
		$code = Input::get('code');
		$customerHashId = \Session::get('customerHashId');
							
		$getResult = DB::table("_user_phone_verification")
					->where("user_hash_id", "=", $customerHashId)
					->where("sent_phone_code", "=", $code)
					->get();
					
		if(count($getResult) > 0){
			
			DB::table("_user_phone_verification")
					->where("id", "=", $getResult[0]->id)
					->update(array(
							"verified" => 1
							));
							
			\Session::put('phoneVerified',1);
			
			return 1;
			
		}
		
		return 0;
		
	}
	
	public function cronDeletePhoneVerifications(){
		
		// > 5 min
		
		$current_time = time() - (60*5);
		
		$getUserCheck = DB::table("_user_phone_verification")
					->where("timest_ux", "<", $current_time)
					->where("verified", "=", 0)
					->get();
					
		foreach($getUserCheck as $item){
			
			DB::table("_user_phone_verification")
					->where("id", "=", $item->id)
					->delete();
			
		}
		
		
	}
	
	
	function checkRecaptcha($recaptchaResponse){
		
		$recaptchaPostData = array(
				"secret" => "xxx",
				"response" => $recaptchaResponse,//Input::get("g-recaptcha-response"),
				"remoteip" => $_SERVER["REMOTE_ADDR"],
					);
			
		$url = "https://www.google.com/recaptcha/api/siteverify";
		
		$content = $this->curlPost($url, $recaptchaPostData); //var_dump($content);
	
		$json = json_decode($content);
		
		//var_dump($content);
		
		if($json->success == true){
			
			return true;
			
		}
		
		return false;
		
	}
	
	public function curlPost($url, $postData){
		
		$options = array(
		
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_HEADER => true,
			//CURLOPT_FOLLOWLOCATION => true,
			//CURLOPT_AUTOREFERER => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			//CURLOPT_SSL_VERIFYPEER => false,

		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);

		curl_close($ch);
	
		return $content;
		
	}

	function postOrderProcessing(){
		
		$sessionCustomerId = \Session::get('customerId');
		
		/*
		$verge_address = Input::get('verge_address');
		$amount_usd = Input::get('amount_usd');
		$amount_usd = floatval($amount_usd);
		$email = Input::get('email');
		$phone = Input::get('phone');
		*/
		
		$postDataInput = Input::get('data');
		$postDataJsonObj = json_decode($postDataInput);
		
		$verge_address = $postDataJsonObj->verge_address;
		$amount_usd = $postDataJsonObj->amount_usd;
		$amount_usd = floatval($amount_usd);
		$email = $postDataJsonObj->email;
		$phone = $postDataJsonObj->phone;
		
		
		
		
		
		
		$errors = 0;
		$error_message = "";
	
		// 1. check balance
		$url = "http://xxx/api/balance";
		$postDataArray = array();
		$result = $this->sendToBackendApi($url, $postDataArray);
			
		if(floatval($result->Balance) < 10 ){
			
			$errors++; $error_message = "INSUFFICIENT FUNDS ON EXCHANGE - TRY AGAIN LATER";
			
		}else{
		
			$error_message = "Please fill all required fields: ";
		
			if(!isset($verge_address) or empty($verge_address) or strlen($verge_address)!=34){$errors++; $error_message .= "incorrect verge address, ";}
			if(!isset($email) or empty($email)){$errors++; $error_message .= "incorrect email, ";}
			if(!isset($amount_usd) or empty($amount_usd) ){$errors++; $error_message .= "incorrect fiat amount, ";}
			if(!$this->isValidEmail($email)){$errors++; $error_message .= "invalid email, ";}
			if( $amount_usd < 0 OR $amount_usd > 500 ){$errors++; $error_message .= "invalid usd amount, ";}
			
		}
		
		if($errors > 0){
			
			$return_array = array(
				"success" => false,
				"errors" => $error_message,
			);
			
			//return Redirect::back()->withErrors(array("msg" => $error_message))->withInput();
			return response()->json($return_array);
			exit();
			
		}
		
		
		// 2. send
		
		$customerHashId = \Session::get('customerHashId');
		
		$getUserCheck = DB::table("_user_phone_verification")
					->where("user_hash_id", "=", $customerHashId)
					->where("verified", "=", 1)
					->get();
					
		if(count($getUserCheck)>0){
		
			$url = "http://xxx/api/add-order";
			$postDataArray = array(
				"user_hash_id" => $customerHashId,
				"session_customer_id" => $sessionCustomerId,
				"verge_address" => $verge_address,
				"amount_usd" => $amount_usd,
				"email" => $email,
				"phone" => $getUserCheck[0]->phone,
			);
			
			//var_dump($postDataArray);
			
			$result = $this->sendToBackendApi($url, $postDataArray);
			
			//var_dump($result);
			
			if(isset($result->success) && $result->success == true){
				
				$form = $result->data;
				
				//return Redirect::to($result->redirect_url);
				return response()->json(array(
					"success" => true,
					"errors" => "",
					"data" => $form
				));
				exit();
				
			}else{
				
				return response()->json(array(
						"success" => false,
						"errors" => json_encode($result)
					));
				exit();
				
			}
			
		}
		
		//return 500;
		return response()->json(array(
				"success" => false,
				"errors" => 500,
			));
		exit();
		
	}
	
	
	function getStepTransfer(){
		
		$withdrawalId = Input::get("t_id");
		
		$url = "http://xxx/api/step-transfer-by-wid";
		$postDataArray = array(
			"withdrawal_id" => $withdrawalId,
		);
		
		$result = $this->sendToBackendApi($url, $postDataArray);
		
		if(isset($result->success) && $result->success == true){
			
			return View::make('web/step_transfer', array(
					
					"order_data" => $result->order_data,
					"wallet_total_trx_count" => $result->wallet_total_trx_count,
					"previous_order_data" => $result->previous_order_data
					
				));
			
		}
		
		return 500;
				
	}
	
	
	
	// return url from payment processor (twispay in this case) POST(test)/GET(live)
	function postOrderStatus(){
		
		//$order_hash = Input::get("order_hash");
		$opensslResult = Input::get("opensslResult");
		// send to api
		
		$url = "http://xxx/api/payment-status-api";
		$postDataArray = array(
			"opensslResult" => $opensslResult
		);
		
		$result = $this->sendToBackendApi($url, $postDataArray);

		if(isset($result->success) && $result->success == true){
			
			return Redirect::to( "/orders/step-transfer?t_id=" . $result->withdrawal_external_id );
			
		}elseif(isset($result->message)){
			
			return $result->message;
			
		}
		
		return 500;
		
	}
	
	
	
	
	
	
	
	
	
	
	
	function getWithdrawalDetails(){
		
		$withdrawalId = Input::get("t_id");

		// send to api
		
		$url = "http://xxx/api/withdrawal-details";
		$postDataArray = array(
			"w_id" => $withdrawalId,
		);
		
		$result = $this->sendToBackendApi($url, $postDataArray);

		if(isset($result->success) && $result->success == true){
			
			if($result->status_id == 1){
				
				return $result->trx_id;
				
			}
			
			return $result->status_id;
			
		}
		
		return 0;
		
	}
	
	
	
	
	
	
	function getTrxBlocks(){
		
		
		$trxId = Input::get("trx_id");
		$url = "https://verge-blockchain.info/api/getrawtransaction?txid=".$trxId."&decrypt=1";
		
		try {
			$ch = curl_init();

			if (FALSE === $ch)
				throw new \Exception('failed to initialize');

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt(/* ... */);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$content = curl_exec($ch);

			if (FALSE === $content)
				throw new \Exception(curl_error($ch), curl_errno($ch));

			// ...process $content now
		} catch(\Exception $e) {

			trigger_error(sprintf(
				'Curl failed with error #%d: %s',
				$e->getCode(), $e->getMessage()),
				E_USER_ERROR);

		}
	
		$result = json_decode($content);
		
		if(!isset($result->confirmations)){
			
			//var_dump($result);
			return 0;
			
		}
		
		return $result->confirmations;
		
	}
	
	function curlGetRequest($url){
		
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_HEADER => true,
			CURLOPT_FOLLOWLOCATION => true,
			//CURLOPT_AUTOREFERER => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
		);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		//if(!is_null($headers)){ curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); }
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		//$info = curl_getinfo($ch);
		//$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		//$header = substr($response, 0, $header_size);
		//$body = substr($response, $header_size);		

		curl_close($ch);
	
		return array($response);
		
	}

	
	
	
	
	
	// use ANOTHER account to check currency rate!!!!!!!!! Important for security reasons
	function getCurrencyRate(){
	
		$bittrexController = new BittrexController();
		$result = $bittrexController->getXvgPrice();
		
		//$amount_usd = Input::get("amount_usd");
		
		echo $result*1.1;
		
	}
	
	
	
	
	
	
	


}