<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//Route::get('/admin/main', 'Admin\AdminController@getMain');


Route::group(['middleware' => 'App\Http\Middleware\Admin'], function(){
	AdvancedRoute::controller('/admin', 'Admin\AdminController');
	AdvancedRoute::controller('/upload', 'Admin\UploadController');
	//AdvancedRoute::controller('/pdf', 'Admin\PdfController');
});

/*
Route::group(['middleware' => 'App\Http\Middleware\Manager'], function(){
	AdvancedRoute::controller('/manager', 'Manager\ManagerController');
});


Route::group(['middleware' => 'App\Http\Middleware\Member'], function(){
	AdvancedRoute::controller('/member', 'Member\MemberController');
	AdvancedRoute::controller('/transaction', 'Member\TransactionController');
	AdvancedRoute::controller('/stripe', 'Payments\PaymentsStripeAchController');
});


Route::group(['middleware' => 'App\Http\Middleware\Common'], function(){
	AdvancedRoute::controller('/common', 'Common\CommonController');
	AdvancedRoute::controller('/zoho', 'ExternalApi\ZohoController');//for developing mode only
	
});
*/


Route::group(['middleware' => ['web']], function () {
	
	Route::get('pages/{post_url}', [
        'uses' => '\App\Http\Controllers\Blog\BlogController@showpost'
    ]);
	
	//AdvancedRoute::controller('/auth', 'Auth\AuthController');

	Route::get('/login2', function(){ 
	
	$password = Illuminate\Support\Facades\Input::get("password");
	
	if($password == "Peakk2018!"){
		
		\Session::put('customerId', 1);
		return \Redirect::to("/");
		
	}
		
	return \Redirect::back();
	
	});
	
	Route::get('/', function(){ $sessionCustomerId = \Session::get('customerId');
	//var_dump($sessionCustomerId);
	if(isset($sessionCustomerId)){return view('web.index');}else{return view('web.devlogin');} });
	Route::get('/terms-of-use', function(){ return view('web.terms'); });
	Route::get('/privacy-policy', function(){ return view('web.policy'); });
	
	AdvancedRoute::controller('/lp', 'Lp\LpController');
	AdvancedRoute::controller('/payments', 'Payments\PaymentsProcessingController');
	AdvancedRoute::controller('/orders', 'Orders\OrdersProcessingController');
	AdvancedRoute::controller('/ext', 'Payments\PaymentsTwispayController');
	

	/*
	AdvancedRoute::controller('/cronjob', 'Cronjob\CronjobController');
	
	AdvancedRoute::controller('/api', 'API\APIController');
	*/
	
});











