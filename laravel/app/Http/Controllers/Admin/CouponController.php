<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Option;

use Input;
use DB;
use Redirect,Validator,Session;

class CouponController extends AdminController
{
	
	public function list_coupon()
	{
		$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins', 'dataTables_css', 'datepicker', 'daterangepicker']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick', 'dataTables_js', 'dataTables_bootsjs', 'datepicker', 'daterangepicker']);
		$this->data['title']		= 'Coupon | List';
		$this->data['kupon']		= Option::where('meta_key','voucher')->get();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/setting/coupon', array('data' => $this->data));
	}

	public function create()
	{
		$name = $_POST['name'];
		$code = $_POST['code'];
		$disc = $_POST['disc'];
		$maxdisc = $_POST['maxdisc'];
		$beginDate = $_POST['beginDate'];
		$endDate = $_POST['endDate'];

		try{
			$voucher = [
				'name' => $name,
				'code' => $code,
				'discount' => $disc,
				'maxDiscount' => $maxdisc,
				'beginDate' => $beginDate,
				'endDate' => $endDate,
				];
			if ($voc = Option::where('meta_key','voucher')->first()) {
				$unserialize = unserialize($voc->meta_value);
				$sum_array = array_push($unserialize, $voucher);
				$serialize = serialize($unserialize);
				$total = Option::where('meta_key','voucher')->update(['meta_value' => $serialize]);

				return redirect('master/setting/coupon')->with('success','Code Voucher berhasil ditambahkan');
			}else{
				$voc = new Option;
				$voc->meta_key = "voucher";
				$voc->meta_value = serialize(array($voucher));
				$voc->save();

				return redirect('master/setting/coupon')->with('success','Code Voucher berhasil ditambahkan');
			}
		}catch(Exception $e){
			return 'error';
		}
	}

	public function edit($id)
	{
		$name = $_POST['name'];
		$code = $_POST['code'];
		$disc = $_POST['disc'];
		$maxdisc = $_POST['maxdisc'];
		$beginDate = $_POST['beginDate'];
		$endDate = $_POST['endDate'];

		$voc = Option::where('meta_key','voucher')->first();
		$a = unserialize($voc->meta_value);

		$a[$id]['name'] = $name;
		$a[$id]['code'] = $code;
		$a[$id]['discount'] = $disc;
		$a[$id]['maxDiscount'] = $maxdisc;
		$a[$id]['beginDate'] = $beginDate;
		$a[$id]['endDate'] = $endDate;
		
		$reindex = array_values($a);
		$serialize = serialize($reindex);
		Option::where('meta_key','voucher')->update(['meta_value' => $serialize]);
		
	}

	public function delete($id)
	{
		$voc = Option::where('meta_key','voucher')->first();
		$a = unserialize($voc->meta_value);
		unset($a[$id]);
		$reindex = array_values($a);
		$serialize = serialize($reindex);
		Option::where('meta_key','voucher')->update(['meta_value' => $serialize]);
		
		return redirect('master/setting/coupon/')->with('success','Voucher berhasil dihapus');
	}
}