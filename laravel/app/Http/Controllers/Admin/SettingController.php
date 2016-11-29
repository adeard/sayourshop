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
use DB, Validator, Input, Image, File, Response;

class SettingController extends AdminController
{
	
    public function bank_account_form()
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins', 
			'icheck', 
			'morris_chart', 
			'jvectormap', 
			'dataTables_css'
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
			'dataTables_js'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'SayourShop | Rekening';
		$this->data['bank_account'] = Option::where('meta_key','bank_account')->first();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/setting/bank_account', array('data' => $this->data));
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

	public function create_category(Request $request)
	{
		$rules = array('category' => 'required');
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {	
			return redirect('master/setting/category/list')->with('failed','Silahkan isi Nama Kategori');
		}

		$value = strtolower($request->category);
		$db_cat = Category::where('name', $value)->first();
		if ($db_cat) {
			return redirect('master/setting/category/list')->with('failed','Maaf kategori telah tersedia');
		}

		$category = new Category;
		$category->name = $value;
		$category->slug = str_replace(" ", "-", $value);
		$category->save();

		return redirect('master/setting/category/list')->with('success','Kategori baru berhasil ditambahkan');
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

	public function create_subcategory(Request $request)
	{
		$rules = array(
			'subcategory' => 'required'
		);

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('master/setting/subcategory/list')->with('failed','Silahkan isi nama subkategori');
		}

		$value = strtolower($request->subcategory);
		$category = Category::where('id',$request->category)->first();
		$subcategory = Subcategory::where('category_id',$category->id)
									->where('subname',$value)
									->first();
		if($subcategory) {
			return redirect('master/setting/subcategory/list')->with('failed','Maaf subkategori telah tersedia');
		}
			
		$subcategory = new Subcategory;
		$subcategory->subname = $value;
		$subcategory->slug = str_replace(" ", "-", $value);
		$subcategory->category_id = $category->id;
		$subcategory->save();

		return redirect('master/setting/subcategory/list')->with('success','Subkategori baru berhasil ditambahkan');
	}

	public function list_subcategory()
	{
		$css_assets = [
			'admin_bootstrap', 
			'admin_css', 
			'font-awesome', 
			'skins', 
			'dataTables_css',
			'ionicons'
		];

		$js_assets = [
			'jquery-adm', 
			'admin_js', 
			'admin_bootstrap-js', 
			'slimscroll', 
			'fastclick', 
			'dataTables_js', 
			'dataTables_bootsjs',
			'raphael',
			'moment'
		];

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Subcategory | List';
		$this->data['category']		= Category::get();
		$this->data['subcategory']	= Subcategory::all();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/setting/list_subcategory', array('data' => $this->data));
	}

	public function category_content(Request $request)
	{
		$this->data['subcategory'] = Subcategory::where('category_id', $request->id)->get();

		return view('admin/setting/subcategory_content')->with('data', $this->data);
	}

	public function list_banner()
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
		$this->data['title']		= 'Banner | List';
		$this->data['categories']	= Category::get();

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/setting/list_banner', array('data' => $this->data));
	}

	public function home_banner()
	{
		$this->data['title'] = "Homepage";
		$this->data['banner'] = Option::where('meta_key','banner_home')->first();

		return view('admin/setting/banner_content')->with('data', $this->data);
	}

	public function add_home_banner(Request $request)
	{
		$banner = Option::where('meta_key','banner_home')->first();
		$unserialize = unserialize($banner->meta_value);
		$key_banner = array();
		$key = $request->key;
		$files = Input::file('images');
	    $file_count = count($files);
	    $uploadcount = 0;

	    foreach($files as $file) {
		    $rules = array('file' => 'mimes:jpeg,jpg,png|required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
		    $validator = Validator::make(array('file'=> $file), $rules);
		    if($validator->fails()) {
		    	return redirect('banner_list')->with('fail','Banner gagal ditambahkan');
		    }

		    $filename= $key.'_'.rand().'.jpg';
		    $img = Image::make($file);

		    if ($key == 'slider1') {
		    	$img->resize(1165,null,function ($constraint) {$constraint->aspectRatio();});

		    	if ($unserialize['slider1'] == '') {
		    		array_push($key_banner, $filename);
		    		$unserialize[$key] = $key_banner;
		    	}else{
		    		array_push($unserialize['slider1'], $filename);
		    	};

		    }else {
		    	$filename= $key.'.jpg';
		    	$img->resize(1155,null,function ($constraint) {$constraint->aspectRatio();});
		    	$key_banner = $filename;
		    	$unserialize[$key] = $key_banner;
		    }

		    $img->save(storage_path('photo_banner/'.$filename),50);
		    $uploadcount ++;
		}

	    $serialize = serialize($unserialize);
	    $banner->meta_value=$serialize;
	    $banner->save();

	    return redirect('banner_list')->with('success','Banner berhasil ditambahkan');
	}

	public function delete_home_banner(Request $request)
	{
		$banner = Option::where('meta_key','banner_home')->first();
		$unserialize = unserialize($banner->meta_value);

		$key_banner = substr($request->name,0,7);

		if (is_array($unserialize[$key_banner])) {
			foreach ($unserialize[$key_banner] as $key => $value) {
				if ($value == $request->name) {
					$val = $key;
				}
			}

			unset($unserialize[$key_banner][$val]);
			$slider = array_values($unserialize[$key_banner]);
			$unserialize[$key_banner] = $slider;
		}else{
			$unserialize[$key_banner] = '';
		};

		File::delete(storage_path('photo_banner/'.$request->name));
		$serialize = serialize($unserialize);
		$banner->meta_value = $serialize;
		$banner->save();
	}

	public function category_banner(Request $request)
	{
		$this->data['category'] = Category::where('slug', $request->name)->first();
		$this->data['title'] = $this->data['category']->name;
		$this->data['banner'] = Option::where('meta_key','banner_'.$request->name)->first();

		return view('admin/setting/banner_category_content')->with('data', $this->data);
	}

	public function delete_category_banner(Request $request)
	{
		$banner = Option::where('meta_key', $request->category)->first();
		$unserialize = unserialize($banner->meta_value);
		$key_banner = array_keys($unserialize, $request->name);
		$unserialize[$key_banner[0]] = '';

		File::delete(storage_path('photo_banner/'.$request->name));

		$serialize = serialize($unserialize);
		$banner->meta_value = $serialize;
		$banner->save();
	}

	public function insert_category_banner(Request $request)
	{
		$banner = Option::where('meta_key', 'banner_'.$request->submit)->first();
		$unserialize = unserialize($banner->meta_value);
		$key = $request->key;
		$file = Input::file('images');
		$rules = array('file' => 'mimes:jpeg,jpg,png|required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
		$validator = Validator::make(array('file'=> $file), $rules);
		
		if(!$validator->passes()){
			return redirect('banner_list')->with('fail','Banner gagal ditambahkan');
		}
		
		$filename= $request->submit.'_'.$key.'.jpg';

		$img = Image::make($file);
		$img->resize(1170,300);
		$img->save(storage_path('photo_banner/'.$filename),50);

		$unserialize[$key] = $filename;
		$serialize = serialize($unserialize);
	    $banner->meta_value=$serialize;
	    $banner->save();

	    return redirect('banner_list')->with('success','Banner berhasil ditambahkan');
	}

	public function image($image)
	{
	    return Image::make(storage_path() . '/photo_banner/' . $image)->response();
	}
}