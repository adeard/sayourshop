<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Product;
use App\Http\Models\Province;
use App\Http\Models\City;
use App\Http\Models\District;
use App\Http\Models\UserMeta;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use App\Http\Models\Option;
use App\Http\Models\Reviews;
use App\Http\Models\Distributor;
use DB, Cart, Sentinel, Validator;

class OrderController extends HomeController
{
    public function cart_form()
	{
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
			'jquery-min',
			'jquery-ui', 
			'bootstrap-min-lib', 
			'jquery-isotope', 
			'jquery-flexslider', 
			'jquery.elevatezoom', 
			'jquery-sharrre', 
			'imagesloaded', 
			'la_boutique', 
			'jquery-cookie'
		];

		$this->data['title']		= 'Keranjang';
		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['provinces']	= Province::get();
		$this->data['cart']			= Cart::content();
		$this->data['address']		= UserMeta::where('user_id', Sentinel::getUser()->id)
												->where('meta_key','address')
												->first();
		$sum = 0;

		foreach (Cart::content() as $key) {
			$product = Product::where('id', $key->id)->first();
			$unserialize = unserialize($product->properties);
			
			if ($key->qty >= $unserialize[$key->options[1]][$key->options[0]]) {
				$key->qty = $unserialize[$key->options[1]][$key->options[0]];
				$key->subtotal = $key->price*$key->qty;
			}

			$result = $product->weight*$key->qty;
			$sum += $result;
		}

		$this->data['weight']		= $sum;

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'order/cart', array('data' => $this->data));
	}

	public function order_review($id)
	{
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
			'jquery-min',
			'jquery-ui', 
			'bootstrap-min-lib', 
			'jquery-isotope', 
			'jquery-flexslider', 
			'jquery.elevatezoom', 
			'jquery-sharrre', 
			'imagesloaded', 
			'la_boutique', 
			'jquery-cookie'
		];

		$this->data['title']		= 'Checkout';
		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['order']		= Order::where('id', $id)->first();
		$this->data['orderdetail']  = OrderDetail::where('order_id', $this->data['order']->id)->get();
		$this->data['bank']			= Option::where('meta_key','bank_account')->first();
		$this->data['address']		= UserMeta::where('user_id', Sentinel::getUser()->id)
												->where('meta_key','address')
												->first();
		Cart::destroy();

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'order/order_review', array('data' => $this->data));
	}

	public function check_order_form()
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
			'jquery-gmap3', 
			'imagesloaded', 
			'la_boutique', 
			'jquery-cookie', 
			'jquery-parallax-lib'
		];

		$this->data['title']		= 'Sayourshop | Check Order';
		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'order/check_order', array('data' => $this->data));
	}

	public function order(Request $request)
	{
		$product = Product::where('id', $request->id)->first();
		$product_property = unserialize($product->properties);

		foreach (Cart::content() as $key) {
			if ($key->id == $request->id && $key->options[0] == $request->size && $key->options[1] == $request->warna) {
				$qty_tmp = $product_property[$request->warna][$request->size] + $request->quantity;
				
				if($qty_tmp > $product_property[$request->warna][$request->size]){
					return redirect('detail/'.$request->id)->with('failed', 'Maaf stok yg tersedia sudah maksimal');
				}

			}

		}

		$this->data['count'] = Reviews::where('product_id',$request->id)->get();

		$properti = array();
		array_push($properti, $request->size);
		array_push($properti, $request->warna);
		
		$order = array(
			'id' => $request->id, 
			'name' => $request->name, 
			'qty' => $request->quantity, 
			'price' => $request->price, 
			'options' => $properti 
		);
		$rowid = Cart::add($order);

		return redirect('detail/'.$request->id)->with('success', 'Barang telah ditambahkan ke dalam keranjang ');
	}

	public function delete_order(Request $request)
	{
		Cart::remove($request->rowid);
	}

	public function update_order_quantity(Request $request)
	{
		Cart::update($request->rowid, $request->quantity);

		$rowid = Cart::get($request->rowid);
		$rowid->qty = $request->quantity;
		$rowid->subtotal = $rowid->qty * $rowid->price;

		$this->data['new_subtotal_product'] = $rowid->subtotal;
		$this->data['new_total_price'] = Cart::total();

		return $this->data;
	}

	public function checkout_order()
	{
		$css_assets = [
			'lib-bootstrap', 
			'style', 
			'font-awesome', 
			'color-schemes-core', 
			'color-schemes-turquoise', 
			'bootstrap-responsive',
			'font-family'
		];

		$this->data['title']		= 'Daftar Pesanan';
		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery']);
		$this->data['provinces']	= Province::get();

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'order/checkout', array('data' => $this->data));
	}

	public function checkout(Request $request)
	{
		$user = Sentinel::getUser();

		$order = new Order;
		$order->user_id = $user->id;
		$order->order_status = 'Menunggu Pembayaran';

		foreach (Cart::content() as $key) {
			$product = Product::where('id', $key->id)->first();
			$distributor = Distributor::where('id', $product->distributor_id)->first();
			
			$color = $key->options[1];
			$size = $key->options[0];
			$unserialize = unserialize($product->properties);
			$stock = $unserialize[$color][$size];
			
			if ($key->qty > $stock) {
				return redirect('keranjang')->with('fail', 'Stock Produk <b>'.$key->name.'</b> yang Anda pesan tersisa <b>'.$stock.'</b>');
			}

			if ($distributor == '') {
				return redirect('keranjang')->with('fail', 'Maaf produk '.$key->name.' telah habis');
			}

			if ($product->status != 'publish') {
				return redirect('keranjang')->with('fail', 'Maaf produk '.$key->name.' telah habis');
			}

		}

		if (is_numeric($request->input('address_check'))) {
			$meta = UserMeta::where('user_id', $user->id)
							->where('meta_key','address')
							->first();
			$unserialize = unserialize($meta->meta_value);
			$address = $unserialize[$request->input('address_check')];

			$order->order_name = $address['nama'];
			$order->order_phone = $address['telepon'];
			$order->order_address = $address['alamat'];
			$order->province_id = $address['provinsi'];
			$order->order_date = date('Y-m-d');
			$order->city_id = $address['kota'];
			$order->district_id = $address['kecamatan'];
			$order->courier = $request->input('courier_check');
			$order->shipping_price = $request->input('shipping_price');
			$order->order_email = $user->email;
			
			if ($request->input('coupon_code')) {
				$order->discount_code = $request->input('coupon_code');
				$order->total_discount = $request->input('discount');
				$order->total_price = (Cart::total()+$request->input('shipping_price'))-$request->input('discount');
			}else{
				$order->total_price = $request->input('cart_total') + $request->input('shipping_price');	
			}

			$order->save();

			$insert_id = $order->id;

			$order = Order::find($insert_id);
			$order->total_weight = $request->input('weight');
			$order->no_invoice = date('Ymd').$user->id.$insert_id;
			$order->save();

			foreach (Cart::content() as $key) {
				$product = Product::where('id', $key->id)->first();
				$result = $product->weight*$key->qty;
				$rowid = 'properties_'.$key->rowid;

				$orderdetail = new OrderDetail;
				$orderdetail->order_id = $insert_id;
				$orderdetail->properties = $request->input($rowid);
				$orderdetail->product_id = $key->id;
				$orderdetail->quantity = $key->qty;
				$orderdetail->total_price = $key->price*$key->qty;
				$orderdetail->total_weight = $result;
				$orderdetail->save();
			}

			//update quantity product
			$orderdetail = OrderDetail::where('order_id', $insert_id)->get();
			foreach ($orderdetail as $detail) {
				$unserialize = unserialize($detail->properties);
				$product = Product::where('id', $detail->product_id)->first();
				$productqty = unserialize($product->properties);
				$productqty[$unserialize[1]][$unserialize[0]] -= $detail->quantity;
				$product->properties = serialize($productqty);
				$product->quantity -= $detail->quantity;
				$product->save();
			}

		}else{
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
				return redirect('keranjang')->with('fail', 'Silahkan isi alamat baru sesuai form yang disediakan');
			}

			$order->order_date = date('Y-m-d');
			$order->order_address = $request->input('address');
			$order->courier = $request->input('courier_check_new');
			$order->order_name = $request->input('name');
			$order->order_phone = $request->input('phone');
			$order->province_id = $request->input('province');
			$order->city_id = $request->input('city');
			$order->district_id = $request->input('district');
			$order->shipping_price = $request->input('shipping_price_new');
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

			if ($request->input('coupon_code')) {
				$order->discount_code = $request->input('coupon_code');
				$order->total_discount = $request->input('discount');
				$order->total_price = (Cart::total()+$request->input('shipping_price_new'))-$request->input('discount');
			}else{
				$order->total_price = $request->input('cart_total_new') + $request->input('shipping_price_new');	
			}

			$order->save();

			$insert_id = $order->id;

			$order = Order::find($insert_id);
			$order->total_weight = $request->input('weight_new');
			$order->no_invoice = date('Ymd').$user->id.$insert_id;
			$order->save();

			foreach (Cart::content() as $key) {
				$product = Product::where('id', $key->id)->first();
				$result = $product->weight*$key->qty;
				$rowid = 'properties_'.$key->rowid;

				$orderdetail = new OrderDetail;
				$orderdetail->order_id = $insert_id;
				$orderdetail->properties = $request->input($rowid);
				$orderdetail->product_id = $key->id;
				$orderdetail->quantity = $key->qty;
				$orderdetail->total_price = $key->price*$key->qty;
				$orderdetail->total_weight = $result;
				$orderdetail->save();
			}

			//update quantity product
			$orderdetail = OrderDetail::where('order_id', $insert_id)->get();
			foreach ($orderdetail as $detail) {
				$unserialize = unserialize($detail->properties);
				$product = Product::where('id', $detail->product_id)->first();
				$productqty = unserialize($product->properties);
				$productqty[$unserialize[1]][$unserialize[0]] -= $detail->quantity;
				$product->properties = serialize($productqty);
				$product->quantity -= $detail->quantity;
				$product->save();
			}

		}

		return redirect('order_review/'.$insert_id);
	}

	public function discount(Request $request)
	{
		
		$voucher = Option::where('meta_key','voucher')->first()->meta_value;
		$coupon = unserialize($voucher);
		$code = null;

		foreach ($coupon as $value) {
			if ($value['code'] == $request->coupon) {
				$code = $value;
			}
		}

		if ($code == null) {
			return redirect('keranjang')->with('fail', "Kode Voucher tidak terdaftar");
		}

		$total = Cart::total();
		$begin_date = date_create($code['beginDate']);
		$date= date_create($code['endDate']);
		$day = date_create(date("Y-m-d"));
		
		if($day <= $date && $day >= $begin_date) {
			$disc = (($total*$code['discount'])/100);

			if ($disc > $code['maxDiscount']) {
				$disc = $code['maxDiscount'];
			}

			return redirect('keranjang')->with('discount', $disc)->with('coupon', $request->coupon);
		}
		
		return redirect('keranjang')->with('fail', "Kode Voucher tidak berlaku");
		

	}

	public function city_content(Request $request)
	{
		$this->data['city_data'] = City::where('id_province', $request->id)->get();

		return view('city_content')->with('data', $this->data);
	}

	public function district_content(Request $request)
	{
		$this->data['district_data'] = District::where('id_city', $request->id)->get();

		return view('district_content')->with('data', $this->data);
	}

	public function check_order(Request $request)
	{
		$order = Order::where('no_invoice', $request->invoice)->first();
		$this->data['title']	= 'Check Order';

		if ($order == '') {
			return redirect('cek_order_form')->with('failed','Maaf No Invoice tidak terdaftar');
		}
		
		$this->order_review($order->id);

		return view('main_layout')->with('data', $this->data)
							  		->nest('content', 'order/user_check_order', array('data' => $this->data));
	}

	public function get_cost($id)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => 'origin=79&destination='.$id.'&weight=1000&courier=jne',
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/x-www-form-urlencoded",
		    "key: 3817d2082f278a925d3407ecb04732d3"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		 	echo "cURL Error #:" . $err;
		} else {
			return $response;
		}

	}

	public function check_shipping(Request $request)
	{
		$cost = json_decode($this->get_cost($request->id));
		$this->data['cost_data'] = serialize($cost->rajaongkir->results[0]->costs);

		return view('cost_content')->with('data', $this->data);
	}

	public function check_shipping_new(Request $request)
	{
		$cost = json_decode($this->get_cost($request->id));
		
		$this->data['cost_data'] = serialize($cost->rajaongkir->results[0]->costs);
		$this->data['weight']	 = $request->weight;

		return view('check_shipping_new')->with('data', $this->data);
	}

	public function modal_review(Request $request)
	{
		$this->data['order'] = OrderDetail::where('order_id', $request->order_id)
											->groupby('product_id')
											->get();	
		return view('user/modal_review')->with('data', $this->data);
	}

	public function add_review(Request $request)
	{	
		$order = Order::where('id', $request->order_id)->first();
		$order->order_status = 'Diterima';
		$order->save();

		$orderdetail = OrderDetail::where('order_id', $request->order_id)
									->where('product_id', $request->product_id)
									->get();
		
		foreach ($orderdetail as $value) {
			$value->review = 'reviewed';
			$value->save();
		}

		$product = Product::where('id', $request->product_id)->first();
		$product->rating += $request->rating;
		$product->save();

		$review = new Reviews;
		$review->user_id = Sentinel::getUser()->id;
		$review->product_id = $product->id;
		$review->rating = $request->rating;
		$review->review = $request->review;
		$review->status = 'publish';
		$review->save();
	}
}
