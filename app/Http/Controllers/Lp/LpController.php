<?php namespace App\Http\Controllers\Lp;

use App\Http\Controllers\Controller;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;
use Auth;
use Excel;
use Hash;
use Mail;


class LpController extends Controller {

	function getPage(){
		
		$id = Input::get("a");
		
		$postData = DB::table('page_source')
			->where("id","=",$id)
			->get();
			
		if(count($postData)>0){
			
			return View::make('lp/'.$postData[0]->html_src);
			
		}else{
			
			return 404;
			
		}
		
	}
	
	function getSuccess(){
		
		$id = Input::get("a");
		
		$postData = DB::table('page_source')
			->where("id","=",$id)
			->get();
			
		if(count($postData)>0){
			
			return View::make('lp/' . $postData[0]->html_src . '_success');
			
		}else{
			
			return 404;
			
		}
		
	}
	
	
}