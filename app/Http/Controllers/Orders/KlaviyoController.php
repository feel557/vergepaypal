<?php namespace App\Http\Controllers\Orders;


class KlaviyoController {
	
	public function addProfile($postData){
		
		//$url = "https://a.klaviyo.com/api/identify";
		$url = "https://a.klaviyo.com/api/track";
		
		/*$postData = array(
				"token" => "NtMBTc",
				"properties" => array(
					'$email' => 'george.washington@example.com',
					'$first_name' => 'George',
					'$last_name' => 'Washington',
					'$city' => 'Mount Vernon',
					'$region' => 'Virginia',
					'$zip' => '22121',
					'$country' => 'United States',
					'$timezone' => 'US/Eastern',
					'$phone_number' => '',
					)
			);*/
		
		$url .= "?data=" . base64_encode(json_encode($postData));
		
		//var_dump($url);
		
		$result = $this->curlCustomRequest($url, "GET");
		
		return $result;
		
	}
	
	public function trackAction($postTrackData, $userEmail, $event){

		$postData = array(
					"event" => $event,
					"customer_properties" => array(
						'$email' => $userEmail,
						'$first_name' => $userEmail
					),
					"time" => time(),
					"properties" => $postTrackData
				);
				
		$postData["token"] = "NtMBTc";
	
		$url = "https://a.klaviyo.com/api/track";
		$url .= "?data=" . base64_encode(json_encode($postData));
		
		$result = $this->curlCustomRequest($url, "GET");
		
		return $result;
		
	}
	
	public function curlCustomRequest($url, $method, $postData = null){
		
		$options = array(
		
			CURLOPT_CUSTOMREQUEST => $method,//"PUT";
			//CURLOPT_POSTFIELDS => http_build_query($postData);
			//CURLOPT_POST => true,
			//CURLOPT_POSTFIELDS => $postData,
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_HEADER => true,
			//CURLOPT_FOLLOWLOCATION => true,
			//CURLOPT_AUTOREFERER => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			//CURLOPT_SSL_VERIFYPEER => false,

		);
		
		$ch = curl_init($url);
		
		if($method == "PUT" OR $method == "POST"){
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			
		}
		
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);

		curl_close($ch);
	
		return $content;
		
	}
	
	
}