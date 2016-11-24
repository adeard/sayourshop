<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Distributor;
use App\Http\Models\Category;
use App\Http\Models\Product;
use App\Http\Models\OrderDetail;
use Yajra\Datatables\Datatables;
use Input;
use DB;
use Redirect,Validator,Session;

class DistributorController extends AdminController
{
	
	public function list_distributor()
	{
		$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins', 'dataTables_css', 'datepicker', 'daterangepicker']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick', 'dataTables_js', 'dataTables_bootsjs', 'datepicker', 'daterangepicker']);
		$this->data['title']		= 'Distributor | List';

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/distributor/list_distributor', array('data' => $this->data));
	}

	public function get_list_distributor()
	{
		$distributor 	= Distributor::all();
		$this->data['distributor'] = Datatables::of($distributor)
									->addColumn('opt', 
                            			'<a href="{{url("/master/distributor/view")}}/<?=$id?>"><i class="fa fa-eye"></i></a>
				                        <a href="{{url("/master/distributor/edit")}}/<?=$id?>"><font color="orange"><i class="fa fa-pencil"></i></font></a>
				                        <a href="#" id="delete" value="<?=$id?>" method="post"><font color="red"><i class="fa fa-remove"></i></font></a>')
									->make(true); 

		return $this->data['distributor'];
	}

	public function view($id)
	{
		$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins', 'dataTables_css', 'ionicons']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick', 'dataTables_js', 'dataTables_bootsjs']);
		$this->data['title']		= 'Distributor | View';

		if(Distributor::find($id)){
			$this->data['distributor']		= Distributor::find($id);
			$this->data['category']			= Category::get();
			$this->data['product'] 			= count(Product::where('distributor_id', $id)->get());
		}else{
			return redirect('master/distributor/list');
		}

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/distributor/view', array('data' => $this->data));
	}

	public function create(Request $request)
	{
		if($request->all()){
			$rules = array(
				'name' => 'required',
				'email' => 'email',
				);
			$validator = Validator::make($request->all(), $rules);

			if (!$validator->fails()) {
				$distributor = New Distributor;
				$distributor->name = $request->input('name');
				$distributor->email = $request->input('email');
				$distributor->address = $request->input('address');
				$distributor->phone = $request->input('phone');

				if($distributor->save()){
					return redirect('master/distributor/list');
				}

			}else{
				return redirect('master/distributor/create')->with('error', 'Terdapat form kosong');
			}

		}else{
			$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins']);
			$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick']);
			$this->data['title']		= 'Distributor | Create';

		    return view('admin_layout')->with('data', $this->data)
									  ->nest('content', 'admin/distributor/form', array('data' => $this->data));
		}
	}

	public function edit($id, Request $request)
	{
		if(Distributor::find($id)){

			if($request->all()){
				$rules = array(
					'name' => 'required',
					'email' => 'email',
					);
				$validator = Validator::make($request->all(), $rules);

				if (!$validator->fails()) {
					$distributor =Distributor::find($id);
					$distributor->name = $request->input('name');
					$distributor->email = $request->input('email');
					$distributor->address = $request->input('address');
					$distributor->phone = $request->input('phone');

					if($distributor->save()){
						return redirect('master/distributor/list');
					}

				}else{
					return redirect('master/distributor/edit/'.$id)->with('error', 'Terdapat form kosong');
				}

			}else{
				$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins']);
				$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick']);
				$this->data['title']		= 'Distributor | Edit';
				$this->data['distributor']	= Distributor::find($id);

			    return view('admin_layout')->with('data', $this->data)
										  ->nest('content', 'admin/distributor/form', array('data' => $this->data));
			}

		}else{
			return redirect('master/distributor/list');
		}
	}

	public function delete($id)
	{
		$products = Product::where('distributor_id', $id)->where('status', 'publish')->get();

		if (count($products) > 0) {

			foreach ($products as $product) {
				$order = OrderDetail::where('product_id', $product->id)->where('review', '')->first();
				
				if ($order != '') {
					return redirect('master/distributor/list')->with('error', 'Maaf masih terdapat order dari produk distributor yang belum selesai');
				}

			}

		}else{
			Distributor::find($id)->delete();
			
			return redirect('master/distributor/list');
		}

	}
}