<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Option;
use App\Http\Models\PaymentConfirmation;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use App\Http\Models\Product;
use DB, Cart, Validator, Mail;

class PaymentController extends HomeController
{
    public function payment_form(Request $request)
	{
		$css_assets = [
			'lib-bootstrap', 
			'style', 
			'font-awesome', 
			'font-awesome-min', 
			'color-schemes-core', 
			'color-schemes-turquoise', 
			'bootstrap-responsive',
			'font-family',
			'datepicker'
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
			'jquery-cookie',
			'datepicker',
			'datepicker-locales'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Konfirmasi Pembayaran';
		$this->data['bank_account']	= Option::where('meta_key','bank_account')->first();
		$this->data['invoice'] 		= $request->payment;

		if ($this->data['invoice']) {
			$invoice = Order::where('no_invoice',$request->payment)->first();
			$this->data['total_price'] = $invoice->total_price;
		}else{
			$this->data['total_price'] = 0;
		}

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'payment_confirmation', array('data' => $this->data));
	}

	public function payment(Request $request)
	{
		$rules = array(
			'no_invoice' => 'required',
			'account_name' => 'required',
			'bank_account' => 'required',
			'bank_name' => 'required',
			'admin_account' => 'required',
			'total_transfer' => 'required',
			'transfer_date' => 'required', 
			);

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('konfirmasi_pembayaran')->with('failed','Silahkan isi form sesuai yang disediakan');
		}

		$order = Order::where('no_invoice',$request->no_invoice)->first();
		if (is_null($order)) {
			return redirect('konfirmasi_pembayaran')->with('failed','Maaf no invoice tidak terdaftar');
		}

		$payment = new PaymentConfirmation;
		$payment->no_invoice = $order->no_invoice;
		$payment->account_name = $request->account_name;
		$payment->bank_account = $request->bank_account;
		$payment->bank_name = $request->bank_name;
		$payment->admin_account = $request->admin_account;
		$payment->total_transfer = $request->total_transfer;
		$payment->transfer_date = $request->transfer_date;
		$payment->order_id = $order->id;
		$payment->save();

		$order->order_status = "Telah Dibayar";
		$order->save();

		$orderdetail = OrderDetail::where('order_id', $order->id)->get();
		foreach ($orderdetail as $detail) {
			$product = Product::where('id', $detail->product_id)->first();
			$product->sold += $detail->quantity;
			$product->save();
		}
		
		return redirect('konfirmasi_pembayaran')->with('success','Konfirmasi berhasil .Kami akan mengecek pembayaran Anda');
	}

	public function check_invoice(Request $request)
	{
		$invoice = $request->invoice;
		$order = Order::where('no_invoice', $request->invoice)->first();
		if ($order != '') {
			return 'true';
		}
		
		return 'false';
	}

	public function check_paid(Request $request)
	{
		$paid = $request->paid;
		$invoice = $request->invoice;
		$order = Order::where('no_invoice', $invoice)->first();
		if ($paid < $order->total_price) {
			return 'failed';
		}
		
	}
}
