<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;
use Auth;
use Excel;
use Hash;
use Mail;
use App\Http\Controllers\ExternalApi\ZohoController;

class AdminController extends Controller {


	function getLoginAsUser(){

		$user = Auth::loginUsingId(Input::get('id'));
		
		if($user->user_type == 3){
			
			$link = '/member/profile';
			
		}elseif($user->user_type == 2){
			
			$link = '/common/settings';
			
		}
		
		if (!$user){
			
			throw new Exception('Error logging in');
			
		}else{
			
			return Redirect::to($link);
			
		}

	}

	
	
	
	/* -- Member Sale Transactions -- */
	
	function getTransactionSaleList(){
		
		$sale_status = Input::get('sale_status');
		$sign_status = Input::get('sign_status');
		
		$saleTransactions = DB::table('_member_property_bits_sales');
		
		if(isset($sale_status)){$saleTransactions = $saleTransactions->where("sale_status", "=", $sale_status);}
		if(isset($sign_status)){$saleTransactions = $saleTransactions->where("sign_status", "=", $sign_status);}
		
		$saleTransactions = $saleTransactions->orderBy("id", "DESC")->Paginate(30);
		
		return View::make('admin/transaction_sale_list', array(
			"data" => $saleTransactions
		));
		
	}
	
	function postTransactionSaleConfirmPrice(){
	
		$id = Input::get('id');
	
		$saleTransaction = DB::table('_member_property_bits_sales')
			->where("id", "=", $id)
			->get();
			
		if(count($saleTransaction)>0){
			
			$totalAmount = $saleTransaction[0]->price_per_bit * $saleTransaction[0]->bits_to_sell;
		
			$update_array = array(
				"sale_status" => 1,
				"amount_to_sell" => $totalAmount
			);
		
			DB::table('_member_property_bits_sales')
				->where("id", "=", $id)
				->update($update_array);
				
			//email to member
			
			$message = '<h1>BitREI confirmed price per bit for resell offer</h1>
						<p>BitREI confirmed price: $'. $saleTransaction[0]->price_per_bit .' per bit for resell offer</p>';
						
			$this->sendEmailToUser($saleTransaction[0]->user_id, "BitREI confirmed price per bit for resell offer", $message);
			
			
		}
		
		return Redirect::back();
			
	}
	
	function postTransactionSaleOfferPrice(){
	
		$price = Input::get('price');
		$id = Input::get('id');
	
		$update_array = array(
			"price_per_bit" => $price,
			"sale_status" => 3
		);
	
		DB::table('_member_property_bits_sales')
			->where("id", "=", $id)
			->update($update_array);
			
		// Insert Offer Log	
				
		$update_array_log = array(
			"sale_transaction_id" => $id,
			"user_id" => Auth::user()->id,
			"price_per_bit" => $price,
			"operation_code" => 3
		);	
				
		DB::table("_member_property_bits_sales_offer_log")
				->insert($update_array_log);
		
			
		//email to member
		$userDataRequest = DB::table('_member_property_bits_sales')
			->where("id", "=", $id)
			->get();
			
		$message = '<h1>BitREI submitted counter resell offer</h1>
					<p>BitREI submitted counter resell offer with price per bit: $'. $price .'</p>';
					
		$this->sendEmailToUser($userDataRequest[0]->user_id, "BitREI submitted counter resell offer", $message);
		
		
		
		return Redirect::back();
			
	}
	
	function getTransactionSaleComplete(){
		
		$transaction_id = Input::get('id');
		
		$db_array = array(
					"sale_status" => 2,
				);
				
		DB::table("_member_property_bits_sales")
			->where("id", "=", $transaction_id)
			->update($db_array);
			
		//update property investor list	
		$this->updatePropertyInvestorListBySellTransaction($transaction_id);
		
		return Redirect::back();
		
	}
	
	
	function sendEmailToUser($user_id, $subject, $message){
		
		if($user_id == 1){
		
			$user_data = DB::table('users')
							->where("user_type","=",21)
							->get();
		
		}else{
		
			$user_data = DB::table('users')
							->where("id","=",$user_id)
							->get();
		
		}
		
		foreach($user_data as $user_item){
			
			$email = $user_item->email;
			
			$data["email"] = $email;
			$data["subject"] = $subject;
			$data["text"] = $message;
			
			//Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
				//		$message->to($data["email"])->subject($data["subject"]);
					//});
					
			try{		
						
				\Mailgun::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
					$message->to($data["email"])->subject($data["subject"]);
				});
				
			}catch(\Exception $e){
				
				//
				
			}
					
		}
		
	}
	
	
	function postUpdateInvestorBitsQty(){
		
		$user_id = Input::get('user_id');
		$property_id = Input::get('property_id');
		$qty = Input::get('qty');
		
		//update
		DB::table("_property_investors")
			->where("user_id", "=", $user_id)
			->where("property_id", "=", $property_id)
			->update(array(
				"bits_qty" => $qty
			));
			
		return Redirect::back();
		
	}
	
	function updatePropertyInvestorListByBuyTransaction($transaction_id){
		
		
		$trxData = DB::table("_member_transaction_steps")
			->where("id", "=", $transaction_id)
			->get();
			
		$property_data_array = json_decode($trxData[0]->property_data_array, true);
		
		foreach($property_data_array as $transaction_property_item){
		
			$current_property_id = $transaction_property_item["property_id"];
			$current_user_id = $trxData[0]->user_id;
			$current_property_invest_amount = $transaction_property_item["invest_amount"];
			$current_property_bits = $transaction_property_item["invest_ownership_percentage"]/10;
			
			$currentMemberInvestDataByProperty = DB::table("_property_investors")
				->where("user_id", "=", $current_user_id)
				->where("property_id", "=", $current_property_id)
				->get();
			
			if(count($currentMemberInvestDataByProperty)>0){
				
				//update
				DB::table("_property_investors")
					->where("id", "=", $currentMemberInvestDataByProperty[0]->id)
					->update(array(
						"bits_qty" => $current_property_bits + $currentMemberInvestDataByProperty[0]->bits_qty,
					));
				
			}else{
				
				//insert
				DB::table("_property_investors")
					->insert(array(
						"property_id" => $current_property_id,
						"user_id" => $current_user_id,
						"bits_qty" => $current_property_bits,
					));
				
			}
			

		}
			
			
		
	}
	
	function updatePropertyInvestorListBySellTransaction($transaction_id){
		
		$trxData = DB::table("_member_property_bits_sales")
			->where("id", "=", $transaction_id)
			->get();
			
		$current_property_id = $trxData[0]->property_id;
		$current_user_id = $trxData[0]->user_id;
		$current_property_bits = $trxData[0]->bits_to_sell;
		
		$currentMemberInvestDataByProperty = DB::table("_property_investors")
			->where("user_id", "=", $current_user_id)
			->where("property_id", "=", $current_property_id)
			->get();
		
		if(count($currentMemberInvestDataByProperty)>0){
			
			//update
			DB::table("_property_investors")
				->where("id", "=", $currentMemberInvestDataByProperty[0]->id)
				->update(array(
					"bits_qty" => $currentMemberInvestDataByProperty[0]->bits_qty - $current_property_bits
				));
			
		}
			
	}
	
	/* -- Member Purchase Transactions -- */
	
	function getCompleteTransaction(){
		
		$transaction_id = Input::get('id');
		
		$db_array = array(
					"status" => 2,
					"date_finished" => date("Y-m-d H:i:s")
				);
				
		DB::table("_member_transaction_steps")
			->where("id", "=", $transaction_id)
			->update($db_array);
			
		//update property investor list	
		$this->updatePropertyInvestorListByBuyTransaction($transaction_id);
		
		
		
		// here is zoho
		$zohoController = new ZohoController();
		$zohoController->getCreateOrder($transaction_id);
	
		return Redirect::back();
		
	}
	
	function getCancelTransaction(){
		
		$transaction_id = Input::get('id');
		
		// update transaction
		$db_array = array(
					"status" => 9,
					"date_finished" => date("Y-m-d H:i:s")
				);
				
		DB::table("_member_transaction_steps")
			->where("id", "=", $transaction_id)
			->update($db_array);
			
		//update property

		$transactionData = DB::table("_member_transaction_steps")
			->where("id", "=", $transaction_id)
			->get();
			
		if($transactionData[0]->docusign_offering_statement_json_status == 2){
			
			$property_data_array_json = $transactionData[0]->property_data_array;
			
			if(!is_null($property_data_array_json)){
				
				$property_data_array = json_decode($property_data_array_json, true);
				
				foreach($property_data_array as $transaction_property_item){
					
					$current_property_id = $transaction_property_item["property_id"];
					$current_property_invest_amount = $transaction_property_item["invest_amount"];
					$current_property_invest_ownership_percentage = $transaction_property_item["invest_ownership_percentage"];
					
					$property_data = DB::table('_property_details')
						->where("id","=",$current_property_id)
						->get();
						
					if(count($property_data)>0){
						
						$property_item = $property_data[0];
						
						$newPropertyInvestedAmount = $property_item->current_invested_amount - $current_property_invest_amount;
						
						$newPropertyAvailablePercents = $property_item->current_available_percent_to_invest + $current_property_invest_ownership_percentage;
						
						$update_array = array(
							"current_invested_amount" => $newPropertyInvestedAmount,
							"current_available_percent_to_invest" => $newPropertyAvailablePercents,
						);
						
						DB::table("_property_details")
							->where("id", "=", $current_property_id)
							->update($update_array);
							
					}
					
				}
			
			}
			
		}
		
		// here is zoho
		$zohoController = new ZohoController();
		$zohoController->getCreateOrder($transaction_id);
		
		return Redirect::back();
		
	}
	
	
	
	

	function getTransactionList(){
		
		$transactions = DB::table("_member_transaction_steps");
		//$transactions = $transactions->where("user_id","=",Auth::user()->id);
		
		$date_1 = Input::get("date_1");
		$date_2 = Input::get("date_2");
		
		if(isset($date_1)){
			
			$date = \DateTime::createFromFormat('m/d/Y', $date_1);
			$date_1_str = $date->format('Y-m-d H:i:s');
		
			$transactions = $transactions->where("date_started",">=",$date_1_str);
			
		}
		
		if(isset($date_2)){
			
			$date = \DateTime::createFromFormat('m/d/Y', $date_2);
			// add +1 day
			$date->add(new \DateInterval('P1D'));
			$date_2_str = $date->format('Y-m-d H:i:s');
			
			$transactions = $transactions->where("date_started","<=",$date_2_str);
			
		}
		
		$transactions = $transactions->orderBy("id", "DESC")->Paginate(10);
		
	
		return View::make('admin/transaction_list', array(
			"data" => $transactions
		));
	
		
		
	}
	

	//=================>>> Blog Pages

	function getBlogPages(){

		$array = DB::table('post')
		->where("type", "=", "2")
		->get();

		return View::make('admin/blog/blog_posts', array(
			"data" => $array,
			"name" => "Pages"
		));

	}

	function getBlogPosts(){

		$array = DB::table('post')
		->where("type", "=", "0")
		->get();

		return View::make('admin/blog/blog_posts', array(
			"data" => $array,
			"name" => "Articles"
		));

	}


	function getBlogPageAdd(){

		return View::make('admin/blog/blog_page_edit', array(
		//	"data" => $array
		));

	}

	function getBlogPageEdit(){

		$id = Input::get('id');

		$array = DB::table('post')
		->where("id", "=", $id)
		->get();

		return View::make('admin/blog/blog_page_edit', array(
			"data" => $array
		));

	}

	function getBlogPageDel(){

		$id = Input::get('id');

		$array = DB::table('post')
		->where("id", "=", $id)
		->delete();

		//return Redirect::back();
		return Redirect::to("/admin/blog-pages");
	}






	function postBlogAddPage(){

		$name = Input::get('name');
		$text = Input::get('text');
		
		$articleId = Input::get('id');

		$anons = Input::get('anons');
		$title = Input::get('title');
		$description = Input::get('description');
		$keywords = Input::get('keywords');
		$cat = Input::get('cat');
		
		$type = Input::get('type');

		$h_url = $this->ru2Lat($title);
		$url_text = Input::get('url');
		
		if(isset($articleId)){

			DB::table('post')
			->where("id", "=", $articleId)
			->update(array(
				"name" => $name,
				"text" => $text,
				"anons" => $anons,
				"title" => $title,
				"description" => $description,
				"keywords" => $keywords,
				"cat" => $cat,
				"type" => $type,
				"url" => $url_text
			));

		}else{
		
			$articleId = DB::table('post')
			->insertGetId(array(
				"name" => $name,
				"text" => $text,
				"anons" => $anons,
				"title" => $title,
				"description" => $description,
				"keywords" => $keywords,
				"cat" => $cat,
				"type" => $type
			));
			
			
			// URL
			if(isset($url_text) && $url_text != ''){
				$h_url = $url_text."-".$articleId;
			}else{
				$h_url = $h_url."-".$articleId;
			}
			
			DB::table('post')
			->where("id","=",$articleId)
			->update(array(
				"url" => $h_url
			));
			
			
			if($type == 0){
				
				// Sitemap
				$this->makeSiteMap();

			}
			
		}
		
		

		if (Input::hasFile('fupload')) {

			$files = Input::file('fupload');
			$avatar = $this->postLoadImage($files);
			DB::table('post')
			->where("id", "=", $articleId)
			->update(array(
			"ava" => $avatar,
			));

		}

		
		

		return Redirect::to("/admin/blog-page-edit/?id=".$articleId);

	}



	//==============> SECTION: Property

	function getPropertyList(){
		
		$data = DB::table('_property_details')
			//->where("id","=",$property_id)
			->orderBy("id", "DESC")
			->Paginate(50);
		
		foreach($data as $item){
			
			$item_img = DB::table('_property_images')
			->where("property_id","=",$item->id)
			->get();
			
			$item->images = $item_img;
			
		}
		
		return View::make('admin/property/list', array(
			"data" => $data
		));

	}
	
	function getPropertyAdd(){
		
		
		return View::make('admin/property/edit', array(
			//"data" => $array
		));

		
	}
	
	function getPropertyDetails(){
		
		$propertyId = Input::get('id');
		
		$data = DB::table('_property_details')
			->where("id","=",$propertyId)
			->get();
		
		$item_img = DB::table('_property_images')
			->where("property_id","=",$data[0]->id)
			->get();
		
		if(count($item_img)>0){
			
			$data->images = $item_img;
			
		}
		
		$financial_data = DB::table('_property_financial_data')
			->where("property_id","=",$data[0]->id)
			->orderBy("date_1", "DESC")
			->get();
		
		if(count($financial_data)>0){
			
			$data->financial_data = $financial_data;
			
		}
		
		$tenant_data = DB::table('_property_tenant_details')
			->where("property_id","=",$data[0]->id)
			->get();
			
		
		if(count($tenant_data)>0){
			
			$data->tenant_data = $tenant_data[0];
			
		}
		
		$investorList = $this->getListPropertyInvestors($data[0]->id);
		
		if(count($investorList)>0){
			
			$data->investor_data = $investorList;
			
		}
		
		return View::make('admin/property/details', array(
			"data" => $data
		));

		
	}
	
	function getListPropertyInvestors($input_property_id){
		
		$userTransactions = DB::table('_member_transaction_steps')
						->where("status", "=", 2)
						->get();
						
		$propertyListInvest = array();
		
		foreach($userTransactions as $transactionItem){
			
			if(!is_null($transactionItem->property_data_array)){
				
				$property_data_array_json = $transactionItem->property_data_array;
				
				if(!is_null($property_data_array_json)){
					
					$property_data_array = json_decode($property_data_array_json, true);
					
					foreach($property_data_array as $transaction_property_item){
						
						if($input_property_id == $transaction_property_item["property_id"]){
							
							$current_property_id = $transaction_property_item["property_id"];
							$current_user_id = $transactionItem->user_id;
							$current_property_invest_amount = $transaction_property_item["invest_amount"];
							$current_property_bits = $transaction_property_item["invest_ownership_percentage"]/10;
							
							$current_property_closing_costs = $transaction_property_item["closing_costs"];
							$current_property_reserves = $transaction_property_item["reserves"];
							
							$cost_reserves = $current_property_closing_costs+$current_property_reserves;
							
							$current_subtotal = $current_property_invest_amount+$cost_reserves;
							
							
							//count invested amount for particular property
							$findPropertyInvestList = 0;
							$i = 0;
							foreach($propertyListInvest as $propInvItem){
								
								if($propInvItem['user_id'] == $current_user_id){
									
									$findPropertyInvestList = 1;
									$propertyListInvest[$i]['invested_amount'] = $propertyListInvest[$i]['invested_amount'] + $current_property_invest_amount;
									$propertyListInvest[$i]['bits_qty'] = $propertyListInvest[$i]['bits_qty'] + $current_property_bits;
									$propertyListInvest[$i]['closing_costs'] = $propertyListInvest[$i]['closing_costs'] + $current_property_closing_costs;
									$propertyListInvest[$i]['reserves'] = $propertyListInvest[$i]['reserves'] + $current_property_reserves;
									$propertyListInvest[$i]['subtotal'] = $propertyListInvest[$i]['subtotal'] + $current_subtotal;
									
								}
								
								$i++;
								
							}
							
							if($findPropertyInvestList == 0){
								
								$userSaleTransactions = DB::table('_member_property_bits_sales')
								->where("user_id", "=", $current_user_id)
								->where("property_id", "=", $current_property_id)
								->where("sale_status", "=", 2)
								->get();
									
								$bitsQtyMinus = 0;
								$investAmountMinus = 0;
								
								foreach($userSaleTransactions as $saleTrxItem){
									
									$bitsQtyMinus += $saleTrxItem->bits_to_sell;
									$investAmountMinus += $saleTrxItem->price_per_bit * $saleTrxItem->bits_to_sell;
									
								}
								
								$current_property_bits = $current_property_bits - $bitsQtyMinus;
								$current_property_invest_amount = $current_property_invest_amount - $investAmountMinus;
								
								$propertyListInvest[] = array(
									"user_id" => $current_user_id,
									"property_id" => $current_property_id,
									"invested_amount" => $current_property_invest_amount,
									"bits_qty" => $current_property_bits,
									"closing_costs" => $current_property_closing_costs,
									"reserves" => $current_property_reserves,
									"subtotal" => $current_subtotal,
								);
								
							}
							
						}
						
					}
					
				}
				
			}
			
		}
		
		return $propertyListInvest;
		
	}
	
	function getPropertyEdit(){
		
		$propertyId = Input::get('id');
		
		$data = DB::table('_property_details')
			->where("id","=",$propertyId)
			->get();
		
		foreach($data as $item){
			
			$item_img = DB::table('_property_images')
			->where("property_id","=",$item->id)
			->get();
			
			$item->images[] = $item_img;
			
		}
		
		return View::make('admin/property/edit', array(
			"data" => $data
		));

		
	}
	
	function getPropertyEditAvailability(){
		
		
		return View::make('admin/property/', array(
			"data" => $array
		));

		
	}
	
	
	
	
	function getPropertyImageMakeThumbnail(){
		
		
		$propertIdQ = DB::table('_property_images')
			->where("id", "=", Input::get('id'))
			->get();
		
		if(count($propertIdQ)>0){
			
			DB::table('_property_images')
				->where("property_id", "=", $propertIdQ[0]->property_id)
				->update(array("is_thumbnail" => 0));
			
		}
		
		DB::table('_property_images')
			->where("id", "=", Input::get('id'))
			->update(array("is_thumbnail" => 1));
		
		return Redirect::back();

	}
	
	
	function getPropertyImageSort(){
		
		DB::table('_property_images')
			->where("id", "=", Input::get('id'))
			->update(array("sort" => Input::get('sort')));
		
		return Redirect::back();

	}
	
	
	
	function getPropertyImageDelete(){
		
		DB::table('_property_images')
			->where( "id", "=", Input::get('id') )
			->delete();
		
		return Redirect::back();

	}
	
	function postPropertyImageUpdate(){
		
		DB::table('_property_images')
			//->where( "id", "=", Input::get('img') )
			->insert(array("property_id" => Input::get('property_id'), "img" => Input::get('img')));
		
		return Redirect::back();

	}
	
	function postPropertyImageFileUpload(){
		
		var_dump( Input::all() ) ;
		
		if (Input::hasFile('file')) {
				
			$file = Input::file('file');
			
			if ($file->isValid()) {

				$filename = $file->getClientOriginalName();

				//$name =  time() . '.' . end(explode(".",$filename));
				$appex = time();
				
				
				
				$file->move(public_path()."/upload_files/", $appex . $filename);
				
				$path = "/upload_files/" . $appex . $filename;
				
				//rename($filename, $name);
				//$url = '/src/public/upload_files/' . $appex . $filename;
				
				DB::table('_property_images')
				//->where( "id", "=", Input::get('img') )
				->insert(array("property_id" => Input::get('property_id'), "img" => $path));
				
				$message = "ok";
			
			} else {
				
				$message = 'An error occured while uploading the file.';
			
			}
			
		} else {
			
			$message = 'No file uploaded.';
		
		}
		
		echo $message;
		
	}
	
	function postDeleteProperty(){
		
		$propertyId = Input::get('id');
		
		$transactions = DB::table('_member_transaction_steps')
			->get();
			
		$existence = 0;
		
		foreach($transactions as $transaction_item){
			
			if(!is_null($transaction_item->property_data_array)){
				
				$prop_data = json_decode($transaction_item->property_data_array);
				foreach($prop_data as $item){
					
					if($item->property_id == $propertyId){
						
						$existence++;
						
					}
					
				}
				
			}
			
		}
			
		if($existence > 0){
			
			return Redirect::back()->withErrors(array("error" => "You can't delete this property because this property exists in member transactions "));
			
		}else{
		
		DB::table('_property_details')
			->where("id","=",$propertyId)
			->delete();
			
		DB::table('_property_images')
			->where("property_id","=",$propertyId)
			->delete();
		
		DB::table('_property_tenant_details')
			->where("property_id","=",$propertyId)
			->delete();
		
		}
		
		return Redirect::to('/admin/property-list');
		
	}
	
	function postPropertyAddUpdate(){
		
		$propertyId = Input::get('id');
		
		//$is_featured = Input::get('is_featured');
		$is_homepage = Input::get('is_homepage');
		$available_percent_to_invest = Input::get('available_percent_to_invest');
		
		//$current_available_percent_to_invest = Input::get('current_available_percent_to_invest');
		//$current_invested_amount = Input::get('current_invested_amount');
		
		$property_price = str_replace(",","",Input::get('property_price'));
		
		$zip = Input::get('zip'); if(!isset($zip)){ $zip = 0; }
		
		
		
		$bedroom = Input::get('bedroom'); if(!isset($bedroom)){ $bedroom = 0; }
		$bathroom = Input::get('bathroom'); if(!isset($bathroom)){ $bathroom = 0; }
		$sq_ft = Input::get('sq_ft'); if(!isset($sq_ft)){ $sq_ft = 0; }
		$year_house_built = Input::get('year_house_built'); if(!isset($year_house_built)){ $year_house_built = 0; }
		//$year_roof = Input::get('year_roof'); if(!isset($year_roof)){ $year_roof = 0; }
		//$year_ac = Input::get('year_ac'); if(!isset($year_ac)){ $year_ac = 0; }
		$current_rent = Input::get('current_rent'); if(!isset($current_rent)){ $current_rent = 0; }
		
		//$minimum_investment = Input::get('minimum_investment'); if(!isset($minimum_investment)){ $minimum_investment = 0; }
		$estimated_market_rent = Input::get('estimated_market_rent'); if(!isset($estimated_market_rent)){ $estimated_market_rent = 0; }
		//$estimated_repairs = Input::get('estimated_repairs'); if(!isset($estimated_repairs)){ $estimated_repairs = 0; }
		
		
		$sort = Input::get('sort'); if(!isset($sort)){ $sort = 0; }
		
		//if(!isset($is_featured)){ $is_featured = 0; }
		if(!isset($is_homepage)){ $is_homepage = 0; }
		
		//$cle = preg_replace('/[^0-9]/', '', $cle_bit);
		
		
		
		$update_array = array(
			"address" => Input::get('address'),
			"city" => Input::get('city'),
			"state" => Input::get('state'),
			"zip" => Input::get('zip'),
			"bedroom" => $bedroom,
			"bathroom" => $bathroom,
			"sq_ft" => str_replace(",","",$sq_ft),
			"year_house_built" => str_replace(",","",$year_house_built),
			"current_rent" => str_replace(",","",$current_rent),
			"thumbnail" => Input::get('thumbnail'),
			"description" => Input::get('description'),
			"closing_date" => Input::get('closing_date'),
			"status" => 1, //Input::get('status'),
			"map_url" => Input::get('map_url'),
			"estimated_market_rent" => str_replace(",","",$estimated_market_rent),
			"title" => Input::get('title'),
			"sort" => $sort,
			"property_price" => str_replace(",","",$property_price),
			"available_percent_to_invest" => Input::get('available_percent_to_invest'),
			"_default_cap_ex" => str_replace(",","",Input::get('_default_cap_ex')),
			"_default_vacancy_rate" => str_replace(",","",Input::get('_default_vacancy_rate')),
			"_default_appreciation" => str_replace(",","",Input::get('_default_appreciation')),
			"_default_taxes" => str_replace(",","",Input::get('_default_taxes')),
			"_default_insurance" => str_replace(",","",Input::get('_default_insurance')),
			"_default_maintenance" => str_replace(",","",Input::get('_default_maintenance')),
			"_default_property_management" => str_replace(",","",Input::get('_default_property_management')),
			"_default_bitrei_trustee" => str_replace(",","",Input::get('_default_bitrei_trustee')),
			"_forecast_growth_rate_1year" => str_replace(",","",Input::get('_forecast_growth_rate_1year')),
			"is_homepage" => $is_homepage,
			"display_status" => Input::get('display_status')
		);
		
		
		
		if(isset($propertyId)){
			
			$propertyId = DB::table('_property_details')
			->where("id","=",$propertyId)
			->update($update_array);
			
			//var_dump($propertyId);
			
		}else{
			
			//var_dump(2);
			
			$current_available_percent_to_invest = 100;
			$current_invested_amount = 0;
			
			if(isset($available_percent_to_invest)){$current_available_percent_to_invest = $available_percent_to_invest;}
			if(isset($property_price) && isset($available_percent_to_invest)){$current_invested_amount = $property_price * ((100 - $available_percent_to_invest)/100);}
			
			$update_array["current_available_percent_to_invest"] = $current_available_percent_to_invest;
			$update_array["current_invested_amount"] = $current_invested_amount;
			//var_dump($update_array);
			$propertyId = DB::table('_property_details')
			->insertGetId($update_array);
			
			return Redirect::to("/admin/property-edit/?id=".$propertyId);
		}
		
		return Redirect::back();

	}
	
	
	function postPropertyInvestDataUpdate(){
		
		$propertyId = Input::get('property_id');
		
		$current_available_percent_to_invest = Input::get('current_available_percent_to_invest');
		$current_invested_amount = Input::get('current_invested_amount');
		
		$update_array = array();
		
		if(isset($propertyId)){
			
			$update_array["current_available_percent_to_invest"] = $current_available_percent_to_invest;
			$update_array["current_invested_amount"] = $current_invested_amount;
			
			$propertyId = DB::table('_property_details')
			->where("id","=",$propertyId)
			->update($update_array);
			
		}
		
		return Redirect::back();

	}
	
	
	
	
	
	
	function postPropertyFinancialDataUpdate(){
		
		$financial_data_id = Input::get('financial_data_id');
		
		$update_array = array(
			"property_price" => Input::get('property_price'),
			"rent_collected" => Input::get('rent_collected'),
			"total_expenses" => Input::get('total_expenses'),
			"property_management_fee" => Input::get('property_management_fee'),
			"operating_expenses" => Input::get('operating_expenses'),
			"tax" => Input::get('tax'),
			"hoa" => Input::get('hoa'),
			"property_insurance" => Input::get('property_insurance'),
			"occupancy_rate" => Input::get('occupancy_rate'),
			"timeframe" => Input::get('timeframe'),
			//"date_1" => Input::get('date_1'),
			//"date_2" => Input::get('date_2'),
		
		);
		
		// date time
		$timeframe = Input::get('timeframe');
		$date_1 = Input::get('date_1');
		
		if(isset($timeframe)){
			
			if($timeframe == "month"){
				
				$date_1_obj = \DateTime::createFromFormat('m/d/Y', $date_1);
				$date_1_formatted = $date_1_obj->format('Y-m-d H:i:s');
				
				$date_2_obj = $date_1_obj->modify('+1 month');
				$date_2_formatted = $date_2_obj->format('Y-m-d H:i:s');
				
				$update_array["date_1"] = $date_1_formatted;
				$update_array["date_2"] = $date_2_formatted;
				
			}
			
		}
		
		
		
		if(isset($financial_data_id)){
			
			$propertyId = DB::table('_property_financial_data')
			->where("id","=",$financial_data_id)
			->update($update_array);
			
		}else{
			
			$update_array['property_id'] = Input::get('property_id');
			
			$dataId = DB::table('_property_financial_data')
			->insertGetId($update_array);
			
			//return Redirect::to("/admin/property-edit/?id=".$propertyId);
		}
		
		return Redirect::back();

	}
	
	function getPropertyFinancialDataDelete(){
		
		$dataId = DB::table('_property_financial_data')
			->where("id", "=", Input::get('id'))
			->delete();
		
		return Redirect::back();

	}
	
	
	function postPropertyTenantDataUpdate(){

		//$tenant_data_id =  = Input::get('tenant_data_id');
		
		$tenant_data = DB::table('_property_tenant_details')
			->where("property_id","=",Input::get('property_id'))
			->get();
		
		$update_array = array(
			"property_status" => Input::get('property_status'),
			"original_lease_start" => Input::get('original_lease_start'),
			"lease_end_date" => Input::get('lease_end_date'),
			"security_deposit" => Input::get('security_deposit'),
			"lease_concessions" => Input::get('lease_concessions'),
			"section_8" => Input::get('section_8'),
			"tenant_background_check" => Input::get('tenant_background_check'),
			"income_at_3x" => Input::get('income_at_3x'),
			"rent_payment_status" => Input::get('rent_payment_status'),
			"resp_item_refrigerator" => Input::get('resp_item_refrigerator'),
			"resp_item_stove" => Input::get('resp_item_stove'),
			"resp_item_washer" => Input::get('resp_item_washer'),
			"resp_item_dryer" => Input::get('resp_item_dryer'),
			"resp_item_dishwaser" => Input::get('resp_item_dishwaser'),
			"resp_item_microwave" => Input::get('resp_item_microwave'),
			"resp_item_gas" => Input::get('resp_item_gas'),
			"resp_item_water" => Input::get('resp_item_water'),
			"resp_item_electric" => Input::get('resp_item_electric'),
			"resp_item_sewer" => Input::get('resp_item_sewer'),
			"resp_item_lawn" => Input::get('resp_item_lawn'),
			"resp_item_garbage" => Input::get('resp_item_garbage'),
			"resp_item_hoa" => Input::get('resp_item_hoa'),
		);
		
		if(count($tenant_data)>0){
			
			$propertyId = DB::table('_property_tenant_details')
			->where("id","=",$tenant_data[0]->id)
			->update($update_array);
			
		}else{
			
			$update_array['property_id'] = Input::get('property_id');
			
			$dataId = DB::table('_property_tenant_details')
			->insertGetId($update_array);
			
		}
		
		return Redirect::back();

	}
	
	
	// property bank account update
	function postPropertyBankUpdate(){

		$property_id = Input::get('property_id');
		$bank_id = Input::get('bank_id');
		
		$update_array = array("bank_account_id" => $bank_id);
		
		DB::table('_property_details')
			->where("id","=",$property_id)
			->update($update_array);
			
		return Redirect::back();

	}
	
	
	
	
	
	// bank wiring
	
	function getBankDataList(){
		
		$data = DB::table('__bank_wiring_data')
			//->where("id", "=", Input::get('id'))
			->get();
		
		return View::make('admin/bank_wiring/list', array(
			"data" => $data
		));

	}
	
	function getBankDataEdit(){
		
		$data = DB::table('__bank_wiring_data')
			->where("id", "=", Input::get('id'))
			->get();
		
		return View::make('admin/bank_wiring/edit', array(
			"data" => $data
		));

	}
	
	function getBankDataAdd(){
		
		return View::make('admin/bank_wiring/edit');

	}
	
	function postBankDataUpdate(){

		$bank_data_id = Input::get('id');
		
		$update_array = array(
			"address" => Input::get('address'),
			"bank_name" => Input::get('bank_name'),
			"account_number" => Input::get('account_number'),
			"routing_number" => Input::get('routing_number'),
			"swift" => Input::get('swift'),
		);
		
		if(isset($bank_data_id)){
			
			$propertyId = DB::table('__bank_wiring_data')
			->where("id","=",$bank_data_id)
			->update($update_array);
			
		}else{
			
			$dataId = DB::table('__bank_wiring_data')
			->insertGetId($update_array);
			
			return Redirect::to("/admin/bank-data-edit?id=" . $dataId);

		}
		
		return Redirect::back();

	}
	
	function getBankDataDelete(){

		$bank_data_id = Input::get('id');
		
		DB::table('__bank_wiring_data')
			->where("id","=",$bank_data_id)
			->delete();
			
		return Redirect::to("/admin/bank-data-list");

	}
	
	
	
	
	
	
	
	
	
	
	
	//==============> SECTION: Documents

	function getDocumentsList(){

		$array = DB::table('_member_documents')
		//->where("id", "=", $id)
		->get();

		return View::make('admin/documents/documents_list', array(
			"data" => $array
		));

	}


	function getDocumentsAdd(){

		return View::make('admin/documents/documents_edit', array(
		//	"data" => $array
		));

	}

	function getDocumentsEdit(){

		$id = Input::get('id');

		$array = DB::table('_member_documents')
		->where("id", "=", $id)
		->get();

		return View::make('admin/documents/documents_edit', array(
			"data" => $array
		));

	}

	function getDocumentsDel(){

		$id = Input::get('id');

		$array = DB::table('_member_documents')
		->where("id", "=", $id)
		->delete();

		//return Redirect::back();
		return Redirect::to("/admin/documents-list");
		
	}


	function postDocumentsAdd(){

		$id = Input::get('id');
		
		$category_id = Input::get('category_id');
		$user_id = Input::get('user_id');
		$property_id = Input::get('property_id');
		
		$data_to_db = array(
				//"path_name" => ___,
				"category_id" => $category_id,
				"user_id" => $user_id,
				"property_id" => $property_id,
				"manager_id" => Auth::user()->id
			);
		
		if(isset($id)){

			DB::table('_member_documents')
			->where("id", "=", $id)
			->update($data_to_db);

		}else{
		
			$id = DB::table('_member_documents')
			->insertGetId($data_to_db);

		}
		
		
		if (Input::hasFile('upload')) {
			
			$file = Input::file('upload');
			if ($file->isValid()) {
				
				$filename = $file->getClientOriginalName();
				
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$random = md5(time() . rand() . $filename);
				$newFilename = $random . "." . $ext;
				
				$file->move(storage_path('app/member_documents'), $newFilename);
				//$file->move(public_path()."/upload_files/", $newFilename);
				//rename($filename, $name);
				$url = '/upload_files/' . $newFilename;				
					
				DB::table('_member_documents')
				->where("id","=",$id)
				->update(array(
					"path_name" => $newFilename
				));
				
			} else {
				
				$message = 'An error occured while uploading the file.';
			
			}
			
		}

		return Redirect::to("/admin/documents-edit/?id=".$id);

	}

	
	
	function getDownloadDocument(){

		$doc_array = DB::table('_member_documents')
		->where("id", "=", Input::get('id'))
		->get();
		
		if(count($doc_array)>0){
		
			$path_name = $doc_array[0]->path_name;
		
			$ext = pathinfo($path_name, PATHINFO_EXTENSION);
		
			$file = storage_path('app/member_documents') . "/" . $path_name;

			//echo $file;
			
			//here check user's permissions to read this file
			$user_can_download = 1;
			
			$download_filename = "Category User Property" . "." . $ext;

			if (file_exists($file) && $user_can_download == 1) {
				
				if (headers_sent()) {
					// HTTP header has already been sent
					return false;
				}
				// clean buffer(s)
				while (ob_get_level() > 0) {
					ob_end_clean();
				}
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename=".$download_filename);
				header('Content-Type: application/octet-stream');
				header("Content-Transfer-Encoding: binary");
				readfile($file);
				// avoid any further output
				exit;
						
				//return response()->download($file, $download_filename);
				
			}else{
				
				echo "File doesn't exist";
				
			}
			
		}
		
	}
	
	
	
	//==============> SECTION: Documents Categories

	function getDocumentsCategories(){

		$array = DB::table('_member_documents_categories')
		//->where("id", "=", $id)
		->get();

		return View::make('admin/documents/categories_list', array(
			"data" => $array
		));

	}


	function getDocumentsCategoriesAdd(){

		return View::make('admin/documents/categories_edit', array(
		//	"data" => $array
		));

	}

	function getDocumentsCategoriesEdit(){

		$id = Input::get('id');

		$array = DB::table('_member_documents_categories')
		->where("id", "=", $id)
		->get();

		return View::make('admin/documents/categories_edit', array(
			"data" => $array
		));

	}

	function getDocumentsCategoriesDel(){

		$id = Input::get('id');

		$array = DB::table('_member_documents_categories')
		->where("id", "=", $id)
		->delete();

		//return Redirect::back();
		return Redirect::to("/admin/documents-categories");
		
	}


	function postDocumentsCategoriesAdd(){

		$id = Input::get('id');
		
		$name = Input::get('name');
		
		$data_to_db = array(
				"name" => $name,
			);
		
		if(isset($id)){

			DB::table('_member_documents_categories')
			->where("id", "=", $id)
			->update($data_to_db);

		}else{
		
			$id = DB::table('_member_documents_categories')
			->insertGetId($data_to_db);

		}
		
		return Redirect::to("/admin/documents-categories-edit/?id=".$id);

	}

	
	
	
	
	//==============> SECTION: Additional PHP functions

	function postLoadImage($files){

		$max_image_size = 3*1024*1024;
		$time = time();
		$path_to_img = '/upload_files/';
		$path_to_small = '/upload_files/';
		$i=0;

		foreach ($files as $file) {

		$i++;
		if(!empty($file)){
		$destinationPath = public_path().$path_to_img;
		$filename = str_random(6) . '_' . $file->getClientOriginalName();
		$uploadSuccess = $file->move($destinationPath, $filename);



		if($file->getClientOriginalName() != ""){
		//=======

		if(preg_match('/[.](GIF)|(gif)$/', $filename)) {
		$im = imagecreatefromgif($destinationPath.$filename) ; //если оригинал был в формате gif
		}
		if(preg_match('/[.](PNG)|(png)$/', $filename)) {
		$im = imagecreatefrompng($destinationPath.$filename) ;//если оригинал был в формате png
		}
		if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
		$im = imagecreatefromjpeg($destinationPath.$filename); //если оригинал был в формате jpg
		}


		$quality = 88; //Качество создаваемого изображения
		$w_src = imagesx($im); //вычисляем ширину
		$h_src = imagesy($im); //вычисляем высоту изображения
		if ($w_src < 800) {$w = $w_src;} else { $w = 800; }


		$prop = $w_src/$h_src;
		$h = $w/$prop;
		$dest = imagecreatetruecolor($w,$h);
		imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $h, $w_src, $h_src);

	

		$random = md5((time()+$i) . rand() . $filename);
		//$ext = explode(".", $filename);
		$avatar = $random.".jpg";//заносим в переменную путь до аватара.
//		imagejpeg($dest, $destinationPath.$avatar, $quality);//сохраняем изображение формата jpg в нужную папку


		unlink ($destinationPath.$filename);//удаляем оригинал загруженного изображения

		//----------------------------------------- мини квадрат

		$w2 = 360;  // ширина изображения
		$quality2 = 75; //Качество создаваемого изображения max 100
		$w_src21 = imagesx($im); //вычисляем ширину
		$h_src21 = imagesy($im); //вычисляем высоту изображения

		$dest21 = imagecreatetruecolor($w2,$w2);
		if ($w_src21 > $h_src21){
		imagecopyresampled($dest21, $im, 0, 0, round((max($w_src21,$h_src21)-min($w_src21,$h_src21))/2), 0, $w2, $w2, min($w_src21,$h_src21), min($w_src21,$h_src21));
		}
		if ($w_src21 < $h_src21){
		imagecopyresampled($dest21, $im, 0, 0, 0, 0, $w2, $w2, min($w_src21,$h_src21), min($w_src21,$h_src21));
		}
		if ($w_src21 == $h_src21){
		imagecopyresampled($dest21, $im, 0, 0, 0, 0, $w2, $w2, $w_src21, $h_src21);
		}
		imagejpeg($dest21, public_path().$path_to_small.$avatar, $quality2); //сохраняем изображение формата jpg в нужную папку

		//----------------------------------------- мини квадрат


		// освобождаем память
		imagedestroy($im);
//		imagedestroy($watermark);

		return $avatar;


		}
		}
		}
	}



	public function ru2Lat($string){

		$string = str_replace("("," ",$string);
		$string = str_replace(")"," ",$string);
		$string = preg_replace("/[^a-zA-ZА-Яа-я0-9\s]/iu", '', $string);

		//$string = iconv("UTF-8","windows-1251",$string);

		//echo "$string <br>";

		//$rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я');
		//$lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
		//$string = str_replace($rus,$lat,$string);



		//$string = strtr($string,"АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ","ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");

		//$string = $this->strtr_utf8($string, "АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ", "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");

		$string = strtolower($string);
		$string=str_replace("\r","-",$string);
		$string=str_replace("\r\n","-",$string);
		$string=str_replace("\n","-",$string);
		$string=preg_replace("/  +/"," ",$string);

		$string = str_replace("_","",$string);
		$string = str_replace(" ","-",$string);
		$string=preg_replace("/--+/","-",$string);
		//echo "$string";

		//$string = iconv("windows-1251","UTF-8",$string);

		return($string);

	}

		
		
		

	public function strtr_utf8($str, $from, $to) {

		$keys = array();
		$values = array();
		preg_match_all('/./u', $from, $keys);
		preg_match_all('/./u', $to, $values);
		$mapping = array_combine($keys[0], $values[0]);
		return strtr($str, $mapping);

	}
	

	function makeSiteMap(){

		$xml33='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		'.$string33.'</urlset>';


		$fp33 = fopen(public_path().'/sitemap.xml','a'); //открытие файла
		flock($fp33,LOCK_EX); //блокировка файла
		ftruncate ($fp33,0); //удаляем старое содержимое файла
		fwrite($fp33,$xml33); //записываем в него новое содержимое
		fflush($fp33); //очищение файлового буфера и запись в файл
		flock($fp33,LOCK_UN); //снятие блокировки
		fclose($fp33); //закрытие файла



	}
	
		

	//==============> SECTION: CURL
		
	
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
	
	
	public function curlGet($url){
		
		$options = array(
		
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
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);

		curl_close($ch);
	
		return $content;
		
	}
	




}