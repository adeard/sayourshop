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
use DB, Cart;
use Redirect, Validator, Session, File, Response, Image;

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
		$this->data['css_assets'] 	= Assets::load('css', ['lib-bootstrap', 'style', 'font-awesome', 'font-awesome-min', 'flexslider', 'color-schemes-core', 'color-schemes-turquoise', 'jquery-parallax', 'bootstrap-responsive','font-family']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'jquery-ui', 'jquery-easing', 'bootstrap-min-lib', 'jquery-isotope', 'jquery-flexslider', 'jquery.elevatezoom', 'jquery-sharrre', 'imagesloaded', 'la_boutique', 'jquery-cookie', 'jquery-parallax-lib']);
		$this->data['title']		= 'Home';
		$this->data['category']		= Category::get();
		$this->data['product']		= Product::where('status', 'publish')
												->orderBy('created_at','DESC')
												->limit(5)
												->get();
		$this->data['sold']			= Product::where('status', 'publish')
												->orderBy('sold','DESC')
												->limit(5)
												->get();
		$this->data['banner']		= Option::where('meta_key','banner_home')->first();

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'home', array('data' => $this->data));
	}

	public function contact_us(Request $request)
	{
		if($request->all()){
			$rules = array(
				'name' => 'required',
				'email' => 'required|email',
				'type' => 'required',
				'message' => 'required',
			);
			$validator = Validator::make($request->all(), $rules);
			if (!$validator->fails()) {
				$ask = New Ask;
				$ask->name = $request->input('name');
				$ask->email = $request->input('email');
				$ask->type = $request->input('type');
				$ask->ask = $request->input('message');
				$ask->status = 0;

				if($ask->save()){
					return redirect('contact')->with('success', 'Pesan anda telah dikirimkan, kami akan membalas pesan anda secepatnya');
				}

			}else{
				return redirect('contact')->with('error', 'harap isi semua form');
			}
		}else{
			$this->data['css_assets'] 	= Assets::load('css', ['lib-bootstrap', 'style', 'font-awesome', 'font-awesome-min', 'flexslider', 'color-schemes-core', 'color-schemes-turquoise', 'jquery-parallax', 'bootstrap-responsive','font-family']);
			$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'jquery-ui', 'jquery-easing', 'bootstrap-min-lib', 'jquery-isotope', 'jquery-flexslider', 'jquery.elevatezoom', 'jquery-sharrre', 'jquery-gmap3', 'imagesloaded', 'la_boutique', 'jquery-cookie', 'jquery-parallax-lib']);
			$this->data['title']		= 'Kontak Kami';
			
		    return view('main_layout')->with('data', $this->data)
									  ->nest('content', 'contact_us', array('data' => $this->data));
		}
	}

	public function terms_conditions(Request $request)
	{
		$this->data['css_assets'] 	= Assets::load('css', ['lib-bootstrap', 'style', 'font-awesome', 'font-awesome-min', 'flexslider', 'color-schemes-core', 'color-schemes-turquoise', 'jquery-parallax', 'bootstrap-responsive','font-family']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'jquery-ui', 'jquery-easing', 'bootstrap-min-lib', 'jquery-isotope', 'jquery-flexslider', 'jquery.elevatezoom', 'jquery-sharrre', 'imagesloaded', 'la_boutique', 'jquery-cookie', 'jquery-parallax-lib']);
		$this->data['title']		= 'Sayourshop | Syarat & Ketentuan';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'terms_conditions', array('data' => $this->data));
	}

	public function privacy_policy(Request $request)
	{
		$this->data['css_assets'] 	= Assets::load('css', ['lib-bootstrap', 'style', 'font-awesome', 'font-awesome-min', 'flexslider', 'color-schemes-core', 'color-schemes-turquoise', 'jquery-parallax', 'bootstrap-responsive','font-family']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'jquery-ui', 'jquery-easing', 'bootstrap-min-lib', 'jquery-isotope', 'jquery-flexslider', 'jquery.elevatezoom', 'jquery-sharrre', 'imagesloaded', 'la_boutique', 'jquery-cookie', 'jquery-parallax-lib']);
		$this->data['title']		= 'Sayourshop | Kebijakan Privasi';

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'privacy_policy', array('data' => $this->data));
	}
	
}
