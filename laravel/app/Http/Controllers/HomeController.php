<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Category;
use App\Http\Models\Guest;
use App\Http\Models\Product;
use App\Http\Models\Option;
use App\Http\Models\Ask;
use App\Http\Models\Subscribe;
use DB, Cart;
use Redirect, Validator, Session, File, Response, Image, Sentinel;

class HomeController extends Controller
{
	public function __construct(Request $request)
	{
		$this->data['category'] 		= Category::get();
		$this->data['request'] 			= $request;
		$this->data['cart'] 			= Cart::count();
		//$this->data['main-cart-content'] = dd($this->get_cart());

		// $this->middleware('getGuest');
	}

    public function index()
	{
		$css_assets = [
			'lib-bootstrap',
			'style',
			'font-awesome',
			'font-awesome-min',
			'flexslider',
			'color-schemes-core',
			'color-schemes-turquoise',
			'jquery-parallax',
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
			'imagesloaded',
			'la_boutique',
			'jquery-cookie',
			'jquery-parallax-lib'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Home';
		$this->data['category']		= Category::get();
		$this->data['banner']		= Option::where('meta_key','banner_home')->first();
		$this->data['product']		= Product::where('status', 'publish')
												->orderBy('created_at','DESC')
												->limit(5)
												->get();
		$this->data['sold']			= Product::where('status', 'publish')
												->orderBy('sold','DESC')
												->limit(5)
												->get();
		$user = Sentinel::getUser();
		if ($user == '') {
			$visitors = Option::where('meta_key', 'visitors')->first();
			$visitors->meta_value += 1;
			$visitors->save();
		}

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'home', array('data' => $this->data));
	}

	public function contact_us(Request $request)
	{
		if($request->all()) {
			$rules = array(
				'name' => 'required',
				'email' => 'required|email',
				'type' => 'required',
				'message' => 'required',
			);
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return redirect('contact')->with('error', 'harap isi semua form');
			}
				
			$ask = New Ask;
			$ask->name = $request->input('name');
			$ask->email = $request->input('email');
			$ask->type = $request->input('type');
			$ask->ask = $request->input('message');
			$ask->status = 0;
			$ask->save();
			
			return redirect('contact')->with('success', 'Pesan anda telah dikirimkan, kami akan membalas pesan anda secepatnya');
		}else{
			$css_assets = [
				'lib-bootstrap',
				'style',
				'font-awesome',
				'font-awesome-min',
				'color-schemes-core',
				'color-schemes-turquoise',
				'bootstrap-responsive',
				'font-family'
			];

			$js_assets = [
				'jquery',
			];

			$this->data['css_assets'] 	= Assets::load('css', $css_assets);
			$this->data['js_assets'] 	= Assets::load('js', $js_assets);
			$this->data['title']		= 'Kontak Kami';
			
		    return view('main_layout')->with('data', $this->data)
									  ->nest('content', 'contact_us', array('data' => $this->data));
		}
	}

	public function terms_conditions(Request $request)
	{
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
		$this->data['title']		= 'Sayourshop | Syarat & Ketentuan';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'terms_conditions', array('data' => $this->data));
	}

	public function privacy_policy(Request $request)
	{
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
		$this->data['title']		= 'Sayourshop | Kebijakan Privasi';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'privacy_policy', array('data' => $this->data));
	}
	
	public function subscribe(Request $request)
	{
		if (!empty($request->email)) {
			$check_email = Subscribe::where('email', $request->email)->first();
			
			if (!empty($check_email)) {
				return 'failed';
			}
			
			$new_subscribe = new Subscribe;
			$new_subscribe->email = $request->email;
			$new_subscribe->save();
			
			return 'success';
		}
	}
	
}
