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

class PaymentsStripeController extends Controller {

	/*
	

	*/
	
	
	
	
	public function getBitTest(){
		
		return View::make('member/payment_bitcoin');
		
	}


	public function getPaymentSaveSource(){
		
		try{
			
			$transaction_id = Input::get('transaction_id');
			
			$source_id = Input::get('source_id');
			$bitcoin_address = Input::get('bitcoin_address');
			$bitcoin_amount = Input::get('bitcoin_amount');
			$bitcoin_uri = Input::get('bitcoin_uri');
			
			$existedTransaction = DB::table("_member_transaction_steps")
				->where("id","=",$transaction_id)
				->where("user_id","=",Auth::user()->id)
				->get();
				
			if(count($existedTransaction)>0){
				
				//echo "exist";
				
				$transactionBitcoinPayment = DB::table("_member_transaction_bitcoin_payment")
				->where("transaction_id","=",$transaction_id)
				->get();
				
				$update_array = array(
					"stripe_source_id" => $source_id,
					"stripe_source_bitcoin_address" => $bitcoin_address,
					"stripe_source_amount_satoshi" => $bitcoin_amount,
					"stripe_source_link" => $bitcoin_uri,
					"time_start" => time(),
				);
				
				if(count($transactionBitcoinPayment)>0){
					
					if($transactionBitcoinPayment[0]->status == 0){
						
						DB::table("_member_transaction_bitcoin_payment")
						->where("id","=",$transactionBitcoinPayment[0]->id)
						->update($update_array);
					
					}elseif($transactionBitcoinPayment[0]->status == 2){
						
						// redirect successfull payment
						
					}
					
				}else{
					
					$update_array["transaction_id"] = $transaction_id;
					$update_array["status"] = 0;
					
					DB::table("_member_transaction_bitcoin_payment")
					->insert($update_array);
					
				}
				
			}
				
		}catch(\Exception $e){
				
				//
				echo $e->getMessage();
				
		}
			
		return 1;
		
	}


}