<?php namespace App\Http\Controllers\Admin;




use App\Http\Controllers\Controller;
//use App\Http\Controllers\BaseController;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use DB;
use Auth;
use Excel;

#use App\Http\Controllers\Payments\PaymentsController;

use Hash;
use Mail;

#use App\Http\Controllers\Customer\CustomerController;


class UploadController extends Controller {



function postWa(){
	
	// if(Auth-type == 21){}
	
	//$publicUploadPath = public_path().'/upload_files/';
	
	$CKEditor = Input::get('CKEditor');
    $funcNum = Input::get('CKEditorFuncNum');
    $message = $url = '';
    if (Input::hasFile('upload')) {
        $file = Input::file('upload');
        if ($file->isValid()) {
			
            $filename = $file->getClientOriginalName();
			
			//$name =  time() . '.' . end(explode(".",$filename));
			$appex = time();
            //$file->move(storage_path()."/app/public/", $filename);
			$file->move(public_path()."/upload_files/", $appex . $filename);
			//rename($filename, $name);
            $url = '/upload_files/' . $appex . $filename;
        } else {
            $message = 'An error occured while uploading the file.';
        }
    } else {
        $message = 'No file uploaded.';
    }
    return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';

	
}





function postTutorialAddPage(){

	$name = Input::get('name');
	$text = Input::get('text');
	$id = Input::get('id');

	if(isset($id)){

		DB::table('tutorials')
		->where("id", "=", $id)
		->update(array(
			"name" => $name,
			"text" => $text
		));

	}else{

		$id = DB::table('tutorials')
		->insertGetId(array(
			"name" => $name,
			"text" => $text
		));

	}

	return Redirect::to("/admin/tutorial-page-edit/?id=".$id);

}









}