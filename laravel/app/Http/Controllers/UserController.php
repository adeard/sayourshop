<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\User;
use App\Http\Models\UserMeta;
use App\Http\Models\Activations;
use App\Http\Models\Order;
use App\Http\Models\Province;
use App\Http\Models\District;
use App\Http\Models\City;
use App\Http\Models\OrderDetail;
use App\Http\Models\Product;
use App\Http\Models\Ask;
use DB, Mail, Sentinel, Validator, Activation, Storage, Input, Session, Redirect, File;

class UserController extends HomeController
{
    public function login_form()
	{
		if (!empty(Sentinel::getUser()->id)) {
			return redirect('/');
		}

		$css_assets = [
			'lib-bootstrap', 
			'style', 
			'font-awesome', 
			'font-awesome-min',
			'color-schemes-core', 
			'color-schemes-turquoise', 
			'jquery-parallax', 
			'bootstrap-responsive',
			'font-family'
		];

		$js_assets = [
			'jquery',
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Login';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'user/login', array('data' => $this->data));
	}

	public function register_form()
	{
		if (!empty(Sentinel::getUser()->id)) {
			return redirect('/');
		}

		$css_assets = [
			'lib-bootstrap', 
			'style', 
			'color-schemes-core', 
			'font-awesome', 
			'font-awesome-min', 
			'color-schemes-turquoise', 
			'bootstrap-responsive',
			'font-family'
		];

		$js_assets = [
			'jquery',
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Register';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'user/register', array('data' => $this->data));
	}

	public function forgot_pass_form()
	{
		if (!empty(Sentinel::getUser()->id)) {
			return redirect('/');
		}
		
		$css_assets = [
			'lib-bootstrap', 
			'style', 
			'color-schemes-core', 
			'font-awesome', 
			'font-awesome-min', 
			'color-schemes-turquoise', 
			'bootstrap-responsive',
			'font-family'
		];

		$js_assets = [
			'jquery', 
			'jquery-ui', 
			'jquery-easing', 
			'bootstrap-min-lib', 
			'jquery-isotope', 
			'jquery-flexslider', 
			'jquery.elevatezoom', 
			'jquery-sharrre', 
			'jquery-gmap3', 
			'imagesloaded', 
			'la_boutique', 
			'jquery-cookie', 
			'jquery-parallax-lib'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Forgot Password';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'user/forgot_pass', array('data' => $this->data));
	}

	public function change_pass_form()
	{
		$css_assets = [
			'lib-bootstrap', 
			'style', 
			'color-schemes-core', 
			'font-awesome', 
			'font-awesome-min', 
			'color-schemes-turquoise', 
			'bootstrap-responsive',
			'font-family'
		];

		$js_assets = [
			'jquery', 
			'jquery-ui', 
			'jquery-easing', 
			'bootstrap-min-lib', 
			'jquery-isotope', 
			'jquery-flexslider', 
			'jquery.elevatezoom', 
			'jquery-sharrre', 
			'jquery-gmap3', 
			'imagesloaded', 
			'la_boutique', 
			'jquery-cookie', 
			'jquery-parallax-lib'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Ubah Password';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'user/change_pass', array('data' => $this->data));
	}

	public function change_pass(Request $request)
	{
		$rules = array(
			'password' => 'required',
			'new_pass' => 'required',
			're_new_pass' => 'required' 
			);

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('form_ubah_pass')->with('error', 'Terdapat form kosong');
		}

		$user = Sentinel::getUser();

		if (password_verify($request->input('password') ,$user->password)) {
			if ($request->input('new_pass') != $request->input('re_new_pass')) {
				return redirect('form_ubah_pass')->with('error','Password tidak cocok');	
			}
				
			$user = Sentinel::update($user, ['password' => $request->input('new_pass')]);

			return redirect('form_ubah_pass')->with('success', 'Password berhasil diubah');		
		}else{
			return redirect('form_ubah_pass')->with('error','Password lama tidak cocok');
		}

	}

	public function register(Request $request)
	{	
		$rules = array(
			'email_input' => 'required|email',
			'pass_input' => 'required',
			're_pass_input' => 'required',
			'fullname_input' => 'required',
			'phone_input' => 'required' 
			);

		$validator 	= Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('daftar')->with('error','Silahkan isi semua form');
		}

		$pass = $request->input('pass_input');
		$re_pass = $request->input('re_pass_input');
		$permissions = array('user.update' => false );
		$user = array (
		 	'email'    => $request->input('email_input'),
		 	'password' => $pass,
		 	'fullname' => $request->input('fullname_input'),
		 	'phone' => $request->input('phone_input'),
		 	'status' => '0',
		 	'permissions' => $permissions
		);

		if ($pass != $re_pass) {
			return redirect('daftar')->with('error', 'Password tidak cocok');
		}

		if ( is_null(Sentinel::findByCredentials($user)) ) { //cek email
			$register = Sentinel::register($user);
			$new_member = User::find($register->id);
			$new_member->phone = $request->input('phone_input');
			$new_member->fullname = $request->input('fullname_input');
			$new_member->status = '0';
			$new_member->save();

			Activation::create($register);

			$getActive = Activations::where('user_id', $register->id)->first(); // get key code from activation table
			//----------temporary-----------// 
			$this->account_activation($register->id, $getActive->code); //auto activation
			//------------------------------//

			//-------temporary disabled---------//
			//$this->SendConfirmationEmail($request->email_input, $getActive->code, $register->id, $user); // send email, key, id, data($user) to mail

			// return redirect('login_form')->with('success','Pendaftaran berhasil kode aktivasi akun telah dikirimkan. Silahkan cek email Anda.');
			//----------------------------------//

			return redirect('login_form')->with('success', 'Pendaftaran berhasil .Selamat Datang di Sayourshop');
		}else{
			return redirect('daftar')->with('error','Maaf email Anda sudah terdaftar');
		}
	}

	public function dashboard()
	{
		$css_assets = [
			'lib-bootstrap',
			'lib-bootstrap-min', 
			'style', 
			'color-schemes-core', 
			'font-awesome', 
			'font-awesome-min', 
			'color-schemes-turquoise', 
			'bootstrap-responsive',
			'font-family', 
			'star-rating', 
			'star-rating-min'
		];

		$js_assets = [
			'jquery', 
			'jquery-ui', 
			'jquery-easing', 
			'bootstrap-min-lib', 
			'jquery-isotope', 
			'jquery-flexslider', 
			'jquery.elevatezoom', 
			'jquery-sharrre', 
			'jquery-gmap3', 
			'imagesloaded', 
			'la_boutique', 
			'jquery-cookie',
			'star-rating', 
			'star-rating-min'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | My Profile';
		$this->data['province']		= Province::get();
		$this->data['user']			= Sentinel::getUser();
		$this->data['rekening']		= UserMeta::where('user_id', $this->data['user']->id)->where('meta_key','bank_account')->first();
		$this->data['address']		= UserMeta::where('user_id', $this->data['user']->id)->where('meta_key','address')->first();	
		$this->data['wish']			= UserMeta::where('user_id', $this->data['user']->id)->where('meta_key','wishlist')->first();
		$this->data['wishlist'] 	= array();
		$this->data['order']		= Order::where('user_id', $this->data['user']->id)->orderBy('order_date','desc')->get();

		if (!empty($this->data['wish'])) {
			$unserialize = unserialize($this->data['wish']->meta_value);

			foreach ($unserialize as $value) {
				$product = Product::where('slug', $value)->first();
				$image = unserialize($product->image);
				array_push($this->data['wishlist'], $product);
			}

		}

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'user/dashboard', array('data' => $this->data));
	}

	public function upload(Request $request) 
	{
		$id = Sentinel::getUser();
	  	$file = array('image' => Input::file('image'));

	  	$rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
	  	$validator = Validator::make($file, $rules);
		if ($validator->fails() || !Input::file('image')->isValid()) {
	    	return redirect('dashboard')->with('failed','Upload Gagal');
	  	}

		File::delete('photo_profile/'.$id->image);//hapus foto lama
		$destinationPath = storage_path('photo_profile'); // upload path
		$extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
		$fileName = $id->id.'.'.$extension; // renameing image
		Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
		//insert DB
		$id->image = $fileName;
		$id->save(); 
		//end insert
		
		return redirect('dashboard')->with('completed','Upload berhasil');
	}

	public function login(Request $request)
	{
		$rules = array(
			'email' => 'required|email',
			'password' => 'required' 
			);

		$validator 	= Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('login_form')->with('error','Silahkan isi form yang tersedia');
		}

		$credentials = array(
		        'email'    		=> $request->email,
		   		'password' 		=> $request->password
		    );

		$user = Sentinel::findByCredentials($credentials); //return data" user
		$check_user = Sentinel::validateCredentials($user, $credentials); //return boolean 1/0
		if ($user == "" || $check_user == "") { 
			return redirect('login_form')->with('error','Email/Password Anda salah');
		}
			
		$active = Activation::completed($user);
		if ($active == "") { 
			return redirect('login_form')->with('error','Akun Anda belum diaktivasi/aktivasi sudah tidak berlaku .Silahkan cek email Anda atau kirim ulang kode aktivasi');
		}
		
		Sentinel::login($user);

		return redirect('/');
	}

	public function admin_login(Request $request)
	{
		$rules = array(
			'email' => 'required|email',
			'password' => 'required' 
			);

		$validator 	= Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('master/login')->with('error','Email or Password Cannot Blank');
		}

		$credentials = array(
		        'email'    		=> $request->email,
		   		'password' 		=> $request->password
		    );
		
		$user = Sentinel::findByCredentials($credentials);
		$check_user = Sentinel::validateCredentials($user, $credentials); //return boolean 1/0
		if ($user == "" || $check_user == "") { //cek akun
			return redirect('master/login')->with('error','Email/Password Anda salah');
		}
		
		$active = Activation::completed($user);
		if ($active == "") { 
			return redirect('master/login')->with('error','Akun Anda belum diaktivasi/aktivasi sudah tidak berlaku .Silahkan cek email Anda atau kirim ulang kode aktivasi');
		}
		
		Sentinel::login($user);

		return redirect('/master');
	}

	public function update(Request $request)
	{
		$rules = array(
			'fullname_input' => 'required',
			'phone_input' => 'required'
			);

		$validator 	= Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('dashboard')->with('error','maaf ada form yang kosong');
		}
		
		$user = Sentinel::getUser();
		$user->fullname = $request->input('fullname_input');
		$user->phone = $request->input('phone_input');
		$user->save();

		return redirect('dashboard')->with('success','profil berhasil diperbaharui');
	}


	public function logout(Request $request)
	{
		Sentinel::logout();

		return redirect('/');
	}

	public function add_bank_acc(Request $request)
	{
		$rules = array(
			'bank' => 'required',
			'bank_account' => 'required',
			'account_name' => 'required'
			);

		$validator 	= Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('dashboard')->with('fail','Nomor rekening gagal ditambahkan');
		}

		$rekening = [
			'bank' => $request->bank,
			'nomor_rekening' => $request->bank_account,
			'atas_nama' => $request->account_name
			];

		if ($user_meta = UserMeta::where('user_id', Sentinel::getUser()->id)->where('meta_key','bank_account')->first()) {
			$unserialize = unserialize($user_meta->meta_value);
			$sum_array = array_push($unserialize, $rekening);
			$serialize = serialize($unserialize);
			$total = UserMeta::where('user_id', $user_meta->user_id)->where('meta_key','bank_account')->update(['meta_value' => $serialize]);
		} else {
			$usermeta = new UserMeta;
			$usermeta->user_id = Sentinel::getUser()->id;
			$usermeta->meta_key = "bank_account";
			$usermeta->meta_value = serialize(array($rekening));
			$usermeta->save();
		}

		return redirect('dashboard')->with('add','Nomor rekening berhasil ditambahkan');
	}

	public function delete_bank_acc($no_rek)
	{
		$user_meta = UserMeta::where('user_id', Sentinel::getUser()->id)->where('meta_key','bank_account')->first();
		$a = unserialize($user_meta->meta_value);
		
		unset($a[$no_rek]);

		$reindex = array_values($a);
		$serialize = serialize($reindex);
		$update = UserMeta::where('user_id', $user_meta->user_id)->where('meta_key','bank_account')->update(['meta_value' => $serialize]);

		return redirect('dashboard')->with('add','Nomor rekening berhasil dihapus');
	}

	public function add_address(Request $request)
	{
		$rules = array(
			'name' => 'required',
			'province' => 'required',
			'city' => 'required',
			'district' => 'required',
			'address' => 'required',
			'phone' => 'required'
			);

		$validator 	= Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('dashboard')->with('fail','Alamat gagal ditambahkan');
		}
			
		$alamat = [
			'nama' => $request->input('name'),
			'telepon' => $request->input('phone'),
			'provinsi' => $request->input('province'),
			'kota' => $request->input('city'),
			'kecamatan' => $request->input('district'),
			'alamat' => $request->input('address')
			];

		if ($user_meta = UserMeta::where('user_id', Sentinel::getUser()->id)->where('meta_key','address')->first()) {
			$unserialize = unserialize($user_meta->meta_value);
			$sum_array = array_push($unserialize, $alamat);
			$serialize = serialize($unserialize);
			$total = UserMeta::where('user_id', $user_meta->user_id)
								->where('meta_key','address')
								->update(['meta_value' => $serialize]);
		}else{
			$usermeta = new UserMeta;
			$usermeta->user_id = Sentinel::getUser()->id;
			$usermeta->meta_key = "address";
			$usermeta->meta_value = serialize(array($alamat));
			$usermeta->save();
		}

		return redirect('dashboard')->with('add','Alamat berhasil ditambahkan');
	}

	public function delete_address($no_alamat)
	{
		$user_meta = UserMeta::where('user_id', Sentinel::getUser()->id)->where('meta_key','address')->first();
		$a = unserialize($user_meta->meta_value);

		unset($a[$no_alamat]);

		$reindex = array_values($a);
		$serialize = serialize($reindex);
		$update = UserMeta::where('user_id', $user_meta->user_id)->where('meta_key','address')->update(['meta_value' => $serialize]);
		
		return redirect('dashboard')->with('add','Alamat berhasil dihapus');
	}

	public function modal_detail(Request $request)
	{
		$data['detail']	= OrderDetail::where('order_id', $request->orderid)->get();
		
		return view('order.modal_detail')->with('data', $data);
	}

	public function address_content(Request $request)
	{
		$usermeta = UserMeta::where('user_id', Sentinel::getUser()->id)->where('meta_key','address')->first();
		$address = unserialize($usermeta->meta_value);
		
		$this->data['address'] 	= $address[$request->id];
		$this->data['province'] = Province::where('id', $this->data['address']['provinsi'])->first();
		$this->data['district'] = District::where('id', $this->data['address']['kecamatan'])->first();
		$this->data['city'] 	= City::where('id', $this->data['address']['kota'])->first();
		$this->data['weight']	= $request->weight;

		$costs = app('App\Http\Controllers\OrderController')->get_cost($this->data['address']['kota']);
		$cost = json_decode($costs);

		$this->data['cost_data'] = serialize($cost->rajaongkir->results[0]->costs);

		return view('address_content')->with('data', $this->data);
	}

	public function SendConfirmationEmail($email, $key, $id, $data)
	{
    	Mail::send('email.account_activation', ['key' => $key, 'id' => $id, 'data' => $data], function ($m) use ($email) {
            $m->from('sayour@shop.com', 'sayourshop.com');

            $m->to($email)->subject('SayourShop Account Activation');
        });
    }

    public function account_activation($id, $key)
    {
    	$now = Date("Y-m-d H:i:s");
    	$getActive = Activations::where('code', $key)->where('user_id', $id)->first();
    	$complete = $getActive->completed;
    	$getActive->completed = 1;
    	$getActive->completed_at = $now;

    	if($complete == 1){
    		return redirect('login_form')->with('error', 'Akun anda sudah diaktivasi!');
    	}

	    if($getActive->save()){
	    	return redirect('login_form')->with('success', 'Akun berhasil di aktivasi. Anda sudah dapat login sekarang!');
	    }else{
	    	return redirect('error');
	    }
    }

    public function update_order(Request $request)
    {
    	$this->data['order']		= Order::where('user_id', $request->user_id)->orderBy('order_date', 'DESC')->get();
    	
    	return view('user/update_order')->with('data', $this->data);
    }

    public function ask_product(Request $request)
    {
    	$user = Sentinel::getUser();
    	$product = Product::where('id', $request->product_id)->first();
    	
    	$ask = New Ask;
    	if ($user->fullname != '') {
    		$ask->name = ucwords($user->fullname);
    	} else {
    		$ask->name = "Guest";
    	}

		$ask->email = $request->email;
		$ask->type = "pertanyaan";
		$ask->ask = "Produk ".ucwords($product->name)." - ".$request->message;
		$ask->status = 0;
		$ask->save();
    }

    public function mail()
	{
	   	$mgClient = new Mailgun('key-e44e6a737078d6ef54c0038256a361e1');
		$domain = "sandbox567d300e15164764bc0ba77bc8f1879d.mailgun.org";

		$result = $mgClient->sendMessage($domain, array(
		    'from'    => 'Excited User <mailgun@sandbox567d300e15164764bc0ba77bc8f1879d.mailgun.org>',
		    'to'      => 'Baz <YOU@sandbox567d300e15164764bc0ba77bc8f1879d.mailgun.org>',
		    'subject' => 'Hello',
		    'text'    => 'Testing some Mailgun awesomness!'
		));
	}
}