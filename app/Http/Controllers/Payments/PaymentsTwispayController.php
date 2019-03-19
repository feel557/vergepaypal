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


use Twispay\Entity\Customer\Customer;
use Twispay\Entity\Order\Currency;
use Twispay\Entity\Order\OrderPurchase;
use Twispay\Exception\ValidationException;
use Twispay\Payment;
use Twispay\PaymentForm;



class PaymentsTwispayController extends Controller{
	
   
	function postOrderStatus(){
		
		$twispayConfig = [
			'siteId' => 1329,
			'secretKey' => 'f108516d927e6c736c53ebc6df44f008',
			'config' => [
				'paymentUrl' => 'https://secure-stage.twispay.com',
				'popupJsUrl' => 'https://secure-stage.twispay.com/assets/script',
				'sslAlgorithm' => 'aes-256-cbc',
			],
		];
		
		$siteId = $twispayConfig['siteId'];
		$secretKey = $twispayConfig['secretKey'];
		$config = $twispayConfig['config'];
		
		$response = new \Twispay\Response($secretKey, $config);
		// this will come from the request (it is put here is only for example)
		//$_POST['opensslResult'] = 'tqb0pHyFGeeWagIthcu/hw==,joq3TJgwFjC7UCHge4Ql7c5kyc/E42GyYfwEnEdZVOzBe5lSFptCmhDVozfffrKJhzvJ3Higsbde+Xg2agBdnOK087Vr6o/F2qEWImOwKpb3BabM9ya6tY0nRoVRx7BJQC9RIVLdTXm0nXMejBE7TM0uDO2JeTX54i2FBpg8oPd0ae82C36yz0amKNyG+7DP10EOxNjVvzQnV2npf7QdR1m2E0iGMBEO1Ykj3Fe6av3n3p15ulkym1B1NxNtVpjSdmpXcN1+ncYU4I/RGNHf6w3+bgLY/j3PCZnIfO0d7KuWQe+33WYb7a7amoYHdLYMNjhWec8GyHtyit7i82bxUV7KMTEfVXKqiaK2uC0liZBQWFY1f/K82E6gIK0Hns8ui/u/qCHUWyFehXR9T7aZQRK9q5kHQnQwPt+Aj4pK16rQF8B76hArKC8hdqZEUvTb0soJ3kpvF4+MTPq/fUCtcrgcYJhZdhGc2NJPONnfea6CjXWKhRGNB3tHmEY8z87AhPFnLBbv39uMIRMW5iOBhNxmutifcapYSZhpNo0FUWcsiQ4GkDuY6QFd7D3nkZX/';
		// load data from $_POST['opensslResult'] and $_GET['opensslResult'] (in this order)
		// alternatively you can pass an array containing the `opensslResult` key
		// or the encrypted response as a string value
		try {
			$response->loadData();
		} catch (\Twispay\Exception\ResponseException $e) {
			// do something on error
			throw $e;
		}
		
		$orderId = $response->getExternalOrderId();
		
		$orderData = json_encode(array(
			"" => "",
		));
		
		if($response->getTransactionStatus() == "complete-ok"){
			
			
			
			// need to get list of possible status
			
			
			
		}
		
		// do something
		echo '<pre>';
		echo 'Order unique identifier (externally provided): ' . $response->getExternalOrderId() . "\n";
		echo 'Order ID (Twispay ID): ' . $response->getOrderId() . "\n";
		echo 'Transaction ID (Twispay ID): ' . $response->getTransactionId() . "\n";
		echo 'Transaction status: ' . $response->getTransactionStatus() . "\n";
		echo 'Customer unique identifier (externally provided): ' . $response->getIdentifier() . "\n";
		echo 'Customer ID (Twispay ID): ' . $response->getCustomerId() . "\n";
		echo 'Amount: ' . $response->getAmount() . "\n";
		echo 'Currency: ' . $response->getCurrency() . "\n";
		echo '</pre>';
		
	}
	
}