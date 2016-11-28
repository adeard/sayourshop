<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\User;
use App\Http\Models\Ask;
use App\Http\Models\Category;
use App\Http\Models\Guest;
use App\Http\Models\Product;
use App\Http\Models\Subcategory;
use App\Http\Models\Distributor;
use App\Http\Models\Order;
use App\Http\Models\Option;
use Yajra\Datatables\Datatables;

use DB, Mail, Sentinel, Validator, Activation, Storage, Input, Session, Redirect, File, Highchart;

class AdminController extends Controller
{

	public function __construct(){
		$this->middleware('admin');

		$this->data['guest'] = Guest::all()->count();
	}

	// ========== TEMPLATE ============

    public function home()
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins', 
			'icheck', 
			'morris_chart', 
			'jvectormap', 
			'dataTables_min'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick', 
			'morris_chart_js', 
			'sparkline', 
			'jvectormap_js', 
			'jvectormap_world_js', 
			'knob', 
			'dataTables_js', 
			'highchart', 
			'export-highchart'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Master';
		$this->data['new']			= Order::where('order_status', 'Menunggu Pembayaran')->get();
		$this->data['has_paid']		= Order::where('order_status', 'Telah Dibayar')->get();
		$this->data['paid'] 		= Order::where('order_status', 'Lunas')->get();
		$this->data['send']			= Order::where('order_status', 'Dikirim')->get();
		$this->data['visitors']		= Option::where('meta_key', 'visitors')->first();		
		$this->data['users']		= User::get();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/home', array('data' => $this->data));
	}

	public function list_user()
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
		$this->data['title']		= 'User | List';

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/user/user_list', array('data' => $this->data));
	}

	public function get_list_user()
	{
		$this->data['user']			= Datatables::of(User::all())->make(true);

		return $this->data['user'];
	}

	public function list_product()
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
		$this->data['title']		= 'Product | List';
		$this->data['category']		= Category::get();
		$this->data['distributor']	= Distributor::get();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/product/list_produk', array('data' => $this->data));
	}

	public function list_category()
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
		$this->data['title']		= 'Category | List';
		$this->data['category']		= Category::all();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/setting/list_category', array('data' => $this->data));
	}

	// public function create_subcategory()
	// {
	// 	$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins']);
	// 	$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick']);
	// 	$this->data['title']		= 'Subcategory | Create';
	// 	$this->data['category_list']= [' - Select - '] + Category::lists('name', 'id')->all();
	//     return view('admin_layout')->with('data', $this->data)
	// 							  ->nest('content', 'subcategory/form', array('data' => $this->data));
	// }

	public function list_subcategory()
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
		$this->data['title']		= 'Subcategory | List';
		$this->data['category']		= Category::all();
		$this->data['subcategory']	= Subcategory::all();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/setting/list_subcategory', array('data' => $this->data));
	}

	public function list_message()
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
		$this->data['title']		= 'Mailbox';
		$this->data['message']		= Ask::orderBy('id', 'DESC')->simplePaginate(25);
		$this->data['total_message']= new Ask;

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/message/list', array('data' => $this->data));
	}

	// ========== VIEW ===========

	public function view_category($id)
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Category | View';
		
		if(!Category::find($id)){
			return redirect('master/setting/category/list')->with('error', 'Data tidak ada');
		}
		
		$this->data['category']		= Category::find($id);
		
	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'category/view', array('data' => $this->data));
	}

	public function view_subcategory($id)
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Subcategory | View';
		
		if(!Subcategory::find($id)){
			return redirect('master/setting/subcategory/list')->with('error', 'Data tidak ada');
		}
		
		$this->data['category']		= Subcategory::find($id);

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'subcategory/view', array('data' => $this->data));
	}

	public function view_message($id)
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins', 
			'wysihtml'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js',
			'wysihtml', 
			'slimscroll', 
			'fastclick'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Message | View';
		
		if(!Ask::find($id)){
			return redirect('master/message/list')->with('error', 'Data tidak ada');;
		}

		$this->data['message']	= Ask::find($id);
		$message = Ask::find($id);
		$message->status = 1;
		$message->save();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/message/view', array('data' => $this->data));
	}

	// ========== CREATE ============

	public function add_user(Request $request)
	{
		if($request->all()){
			echo "Horeeeeee";
		}else{
			$css_assets = [
				'admin_bootstrap', 
				'admin_css', 
				'font-awesome', 
				'skins'
			];

			$js_assets = [
				'jquery', 
				'admin_js', 
				'admin_bootstrap-js', 
				'slimscroll', 
				'fastclick'
			];

			$this->data['css_assets'] 	= Assets::load('css', $css_assets);
			$this->data['js_assets'] 	= Assets::load('js', $js_assets);
			$this->data['title']		= 'User | Create';

		    return view('admin_layout')->with('data', $this->data)
									  ->nest('content', 'admin/user/form', array('data' => $this->data));
		}
	}

	public function add_category(Request $request)
	{
		$rules = array(
			'name' => 'required',
			'slug' => 'required', 
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('master/category/create')->with('error', 'Terdapat form kosong');
		}

	    $category = New Category;
	    $category->name =  $request->input('name');
	    $category->slug =  $request->input('slug');
	    $category->save();

	    return redirect('master/category/list');
	}

	public function add_subcategory(Request $request)
	{
		$rules = array(
			'subname' => 'required',
			'category' => 'required',
			'slug' => 'required', 
			);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('master/subcategory/create')->with('error', 'Terdapat form kosong');
		}

	    $category = New Subcategory;
	    $category->subname = $request->input('subname');
	    $category->category_id = $request->input('category');
	    $category->slug = $request->input('slug');
	    $category->properties = $request->input('properties');
	    $category->save();

	    return redirect('master/subcategory/list');
	}

	// ========== EDIT ============

	public function edit_category($id, Request $request)
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick'
		];

		if(!Category::find($id)) {
			return redirect('master/category/list');
		}

		if($request->all()) {
			$rules = array(
				'name' => 'required',
				'slug' => 'required', 
				);
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return redirect('master/category/edit/'.$id)->with('error', 'Terdapat form kosong');
			}

			$category = Category::find($id);
			$category->name = $request->input('name');
			$category->slug = str_replace(" ", "-", $request->input('name'));
			$category->save();
			
			return redirect('master/setting/category/list');
		}

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Category | Edit';
		$this->data['category']		= Category::find($id);

		return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'category/form', array('data' => $this->data));
	}

	public function edit_subcategory($id, Request $request)
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins'
		];

		$js_assets = [
			'jquery', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick'
		];

		if(!Subcategory::find($id)) {
			return redirect('master/subcategory/list');
		}

		if($request->all()) {
			$rules = array(
				'subname' => 'required',
				'category' => 'required',
				'slug' => 'required', 
				);
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return redirect('master/subcategory/edit/'.$id)->with('error', 'Terdapat form kosong');
			}

			$category = Subcategory::find($id);
			$category->subname = $request->input('subname');
		    $category->category_id = $request->input('category');
		    $category->slug = $request->input('slug');
		    $category->properties = $request->input('properties');
		    $category->save();
			
			return redirect('master/subcategory/list');
		}else{
			$this->data['css_assets'] 	= Assets::load('css', $css_assets);
			$this->data['js_assets'] 	= Assets::load('js', $js_assets);
			$this->data['title']		= 'Subcategory | Edit';
			$this->data['category']		= Subcategory::find($id);
			$this->data['category_list']= [' - Select - '] + Category::lists('name', 'id')->all();

		    return view('admin_layout')->with('data', $this->data)
									  ->nest('content', 'subcategory/form', array('data' => $this->data));
		}
		
	}

	// ========== DELETE ============

	public function delete_category($id)
	{
		Category::find($id)->delete();

		return redirect('master/setting/category/list');
	}

	public function delete_subcategory($id)
	{
		Subcategory::find($id)->delete();

		return redirect('master/setting/subcategory/list');
	}

	public function delete_message($id)
	{
		$message = Ask::find($id);
		if(!$message) {
			return redirect('master/message/list')->with('error', 'Data tidak ada');
		}
		
		if($message->status == 0) {
			return redirect('master/message/list')->with('error', 'Tidak dapat menghapus pesan yang belum dibaca');
		}

		$message->delete();

		return redirect('master/message/list')->with('success', 'Pesan telah dihapus');	
	}

	//mail
	public function mail_reply()
	{
		$data['email'] = $_POST['email'];
		$data['subject'] = $_POST['subject'];
		$data['message'] = $_POST['message'];

		Mail::send('email.reply', ['message' => $data['message'], 'email' => $data['email'], 'data' => $data['message']], function ($m) use ($data) {
            $m->from('sayour@shop.com', 'sayourshop.com');
            $m->to($data['email'])->subject($data['subject']);
        });

        return redirect('master/message/list')->with('success', 'Pesan telah dikirim');
	}

	public function order_month(Request $request)
	{
		$this->data['orders'] = Datatables::of(Order::where('order_date','LIKE', '%-'.str_pad($request->month, 2, "0", STR_PAD_LEFT).'-%'))->make(true);

		return $this->data['orders'];
	}
}
