<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\User;
use App\Http\Models\Category;
use App\Http\Models\Subcategory;
use App\Http\Models\Option;
use App\Http\Models\Order;
use App\Http\Models\PaymentConfirmation;
use Yajra\Datatables\Datatables;
use DB, Validator;

class TransactionController extends AdminController
{

	public function payment_list()
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins', 
			'dataTables_css'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick', 
			'dataTables_js', 
			'dataTables_bootsjs'
		];
		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Pembayaran';
		$this->data['payment']		= PaymentConfirmation::orderBy('created_at','desc')->get();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/transaction/payment', array('data' => $this->data));
	}

    public function order()
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins', 
			'dataTables_css'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick', 
			'dataTables_js', 
			'dataTables_bootsjs'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Pemesanan';
		$this->data['bank_account'] = Option::where('meta_key','bank_account')->first();
		$this->data['order']		= Order::orderBy('created_at','desc')->get();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/transaction/order', array('data' => $this->data));
	}

	public function add_bank_account(Request $request)
	{
		$rules = array(
			'bank_name' => 'required',
			'bank_account' => 'required',
			'account_name' => 'required' 
		);
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return redirect('master/setting/bank_account')->with('failed','Silahkan isi sesuai form ang disediakan');
		}

		$bank_account = [
			'bank_name' => $request->bank_name,
			'bank_account' => $request->bank_account,
			'account_name' => $request->account_name,
		];

		if ($bank = Option::where('meta_key','bank_account')->first()) {
			$unserialize = unserialize($bank->meta_value);
			$sum_array = array_push($unserialize, $bank_account);
			$serialize = serialize($unserialize);
			$total = Option::where('meta_key','bank_account')->update(['meta_value' => $serialize]);
		}else{
			$bank = new Option;
			$bank->meta_key = "bank_account";
			$bank->meta_value = serialize(array($bank_account));
			$bank->save();
		}

		return redirect('master/setting/bank_account')->with('success','Nomor Rekening berhasil ditambahkan');

	}

	public function del_bank_account($id)
	{
		$bank_account = Option::where('meta_key','bank_account')->first();
		$a = unserialize($bank_account->meta_value);
		
		unset($a[$id]);
		
		$reindex = array_values($a);
		$serialize = serialize($reindex);
		$update = Option::where('meta_key','bank_account')->update(['meta_value' => $serialize]);

		return redirect('master/setting/bank_account')->with('success','Nomor rekening berhasil dihapus');
	}

	public function payment(Request $request)
	{
		$order = Order::where('id', $request->payment)->first();
		$order->order_status = "Lunas";
		$order->save();

		return redirect('master/transaction/pembayaran');
	}

	public function insert_shipping(Request $request)
	{
		$order = Order::where('id', $request->orderid)->first();
		$order->no_resi = $request->resi;
		$order->save();
	}

	public function send(Request $request)
	{
		$order = Order::where('id', $request->orderid)->first();
		$product = $order->order_detail;
		
		$order->order_status = "Dikirim";
		$order->save();
	}

	public function list_order(Request $request)
	{
		$orders = Order::where('order_status', $request->order_status)->orderBy('order_date', 'DESC');
		$data = Datatables::of($orders)
				->addColumn('id', 
                            '@if($order_status == "Lunas" && $no_resi == "")
                            	<td><input type="text" class="col-sm-12 resi" id="{{$id}}" name="{{$id}}"></td>
                            @elseif($order_status == "Dikirim")
                            	<td><input type="text" class="col-sm-12 resi" id="{{$id}}" name="{{$id}}" value="{{$no_resi}}" disabled="true"></td>
                            @else
                            	<td><input type="text" id="{{$id}}" class="col-sm-12 resi" value="{{$no_resi}}" disabled="true"></input></td>
                            @endif')
				->addColumn('detail', 
                            '<button class="btn btn-primary btn-xs detail" id="{{$id}}">Lihat</button>')
				->addColumn('opt',
							'@if($order_status == "Dikirim" || $order_status == "Diterima")
                            	<button class="btn btn-success btn-xs" disabled="true">Selesai</button>
                            @else
                            	<button class="btn btn-success btn-xs send" id="send_{{$id}}" disabled="true">Kirim</button>
                            @endif')
				->make(true);
				
		return $data;
	}
}
