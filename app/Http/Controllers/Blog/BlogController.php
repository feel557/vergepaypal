<?php namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;
use Auth;
use Excel;
use Hash;
use Mail;


class BlogController extends Controller {

	
		


	function showposts($url){
		
		$postData = DB::table('post_category')
			->where("url","=",$url)
			->get();
			
		if(count($postData)>0){
			
			$data = DB::table('post')
			->where("type","=","0")
			->where("cat","=",$postData[0]->id)
			->orderBy("id", "DESC")
			//->limit(12)
			->get();
			
			return View::make('posts', array(
				"category" => $postData,
				"data" => $data
			));
			
		}else{
			
			return 404;
			
		}
		
		
		
	}
	


	


	function showpost($url){
		
		$postData = DB::table('post')
		->where("url", "=", $url)
		->get();
		
		if(count($postData)>0){
			
			return View::make('web/page', array(
				"data" => $postData
			));
			
		}else{
			
			return 404;
			
		}
		
	}
	









}