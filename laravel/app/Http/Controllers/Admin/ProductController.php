<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Category;
use App\Http\Models\Subcategory;
use App\Http\Models\Product;
use App\Http\Models\OrderDetail;
use App\Http\Models\Distributor;
use App\Http\Models\Reviews;
use App\Http\Models\PaymentConfirmation;
use App\Http\Models\Order;
use Yajra\Datatables\Datatables;
use DB, Input, Validator, Storage, File, Image;

class ProductController extends AdminController
{
	
    public function create()
	{
		$this->data['css_assets'] 	= Assets::load('css', ['admin_bootstrap', 'admin_css', 'font-awesome', 'skins','ionicons']);
		$this->data['js_assets'] 	= Assets::load('js', ['jquery', 'admin_js', 'admin_bootstrap-js', 'slimscroll', 'fastclick']);
		$this->data['title']		= 'Product | Create';
		$this->data['category']		= Category::get();
		$this->data['distributor']	= Distributor::get();
		$this->data['array']		= '';

	    return view('admin_layout')->with('data', $this->data)
								  ->nest('content', 'admin/product_insert', array('data' => $this->data));
	}

	public function category_product(Request $request)
	{
		$this->data['product'] = Product::where('category_id', $request->category_id)->get();

		return view('admin/product/category_product')->with('data', $this->data);
	}

	public function get_list_product(Request $request)
	{
		$condition = [];
		//--------check condition---------//
		if (!empty($request->category_id)) {
			$condition['product.category_id'] = $request->category_id;
		}

		if (!empty($request->subcategory_id)) {
			$condition['product.subcategory_id'] = $request->subcategory_id;
		}

		if (!empty($request->status)) {
			$condition['product.status'] = $request->status;
		}

		if (!empty($request->distributor_id)) {
			$condition['product.distributor_id'] = $request->distributor_id;
		}
		//--------end check condition--------//

		//-----------define value--------------//
		$content = [
			'product.id',
			'product.name',
			'product.created_at',
			'product.category_id',
			'product.price',
			'product.quantity',
			'product.status',
			'subcategory.slug',
			'product.distributor_id',
		];
		//----------end define value-----------//

		$product = Product::select($content)
							->where($condition)
							->orderBy('product.created_at','DESC')
							->join('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
							->get();

		$data = Datatables::of($product)
				->addColumn('no', 
                            '{{$id}}')
				->addColumn('date', 
                            '{{date_format(date_create($created_at), "d M Y")}}')
				->addColumn('product_name',
							'{{ucwords($name)}}')
				->addColumn('category_name', 
							'{{ucwords(str_replace("-"," ", $slug))}}')
				->addColumn('price',
							'{{number_format($price, 0, ",", ".")}}')
				->addColumn('quantity',
							'<div id="update_qty_{{$id}}"><?= $quantity ?></div>')
				->addColumn('opt',
							'@if($status != "publish")
                        		<button type="button" class="btn btn-success btn-xs active_product" id="<?= $id ?>" name="<?= $name ?>">Aktifkan</button>
	                        @else
	                            <button class="btn btn-danger btn-xs active_product" id="<?= $id ?>">Non-Aktifkan</button>
	                        @endif

	                        <a href="#" id="detail_{{$id}}" name="detail_{{$id}}" class="detail"><i class="fa fa-eye"></i></a>
	                        <a href="#" id="delete" value="<?=$id?>" method="post"><font color="red"><i class="fa fa-remove"></i></font></a>
	                        <input type="hidden" class="product_status" id="status_{{$id}}" value="{{$status}}">')
				->make(true);

		return $data;
	}

	public function modal_product(Request $request)
	{
		$this->data['product'] = Product::where('id', $request->product_id)->first();
		$this->data['order']  = OrderDetail::where('product_id', $request->product_id)
											->orderBy('created_at','DESC')
											->get();

		return view('admin/product/modal_product')->with('data', $this->data);
	}

	public function add_product(Request $request)
	{
		$form = array(
			'name'	=> $request->prodname,   
			'desc'	=> $request->desc_input,
			'price'	=> $request->price_input,
			'category_id'	=> $request->category,
			'subcategory_id'	=> $request->subcategory,
			'weight'	=> $request->weight,
		);
		$rules = array(
			'name'	=> 'required', 
			'desc'	=> 'required',
			'price'	=> 'required',
			'category_id'	=> 'required',
			'subcategory_id'	=> 'required',
			'weight'	=> 'required',
		);
		$validator 	= Validator::make($form, $rules);

		if (!$validator->fails()) {
			$category = Category::where('id', $request->category)->first();
			$category->total_product += 1;
			$category->save();

			$subcategory = Subcategory::where('id', $request->subcategory)->first();
			$subcategory->total_product += 1;
			$subcategory->save();
			
			$product = new Product;
			$product->category_id = $request->category;
			$product->name = $request->prodname;
			$product->slug = str_replace(" ", "-", $request->prodname);
			$product->desc = $request->desc_input;
			$product->price = $request->price_input;
			$product->subcategory_id = $request->subcategory;
			$product->weight = $request->weight;
			$product->distributor_id = $request->distributor;
			$product->status = "unactive";
			$product->save();

			$insert_id = $product->id;
			$photos = array();

			for ($i=0; $i < 5; $i++) {
				$form = strval('tes_'.$i);

				if (!is_null($request->$form) ) {
					$file = Input::file($form);
				  	$img = Image::make($file);
				  	$filename = rand().'.jpg';
		      		$img->widen(370);
	     			$img->save(storage_path('photo_product/'.$filename),50);

	     			if ($i == 0) {
	     				$img->fit(220);
	     				$img->save(storage_path('photo_product/2_'.$filename),50);
	     			}

			    	array_push($photos, $filename);
				}

			}

			$product->image = serialize($photos);
			$product->save();

			return redirect('master/produk/create')->with('completed','Produk berhasil ditambahkan');
		}else{
			return redirect('master/produk/create')->with('failed','Produk gagal ditambahkan');
		};

	}
	
	public function check_variant(Request $request)
	{
		$product = Product::where('id', $request->id)->first();
		$unserialize = unserialize($product->properties);
		$this->data['variant_count'] = count($unserialize);
		$variant_product = array();

		if (!empty($unserialize)) {

			foreach ($unserialize as $key => $value) {
				array_push($variant_product, $key);
			}

			$this->data['variant'] = implode(",", $variant_product);

			return $this->data;
		}
		
	}

	public function add_variant(Request $request)
	{
		$product = Product::where('id', $request->id)->first();
		$size = explode(",", $request->size);
		$arraysize = array();

		foreach ($size as $key) {

			if ($key != '') {
				$qty = $key."_qty";
				$arraysize[$key] = $request->$qty;
			}

		}

		if ($product->properties) {
			$unserialize = unserialize($product->properties);

			if (array_key_exists(ucwords(strtolower($request->color)),$unserialize)) {
				return 'fail';
			} else {
				$unserialize[ucwords(strtolower($request->color))] = $arraysize;
				$color = serialize($unserialize);
				$product->properties = $color;
				$product->quantity += $request->total;
				$product->save();

				return count($unserialize);	
			}

		}else{
			$arraycolor = array(
				ucwords(strtolower($request->color)) => $arraysize
			);
			$color = serialize($arraycolor);
			$product->properties = $color;
			$product->quantity = $request->total;
			$product->save();

			return count($arraycolor);
		}
	}

	public function activated_product(Request $request)
	{
		$product = Product::where('id', $request->id)->first();

		if ($product->properties != '') {
			$product->status = "publish";
			$product->save();
			
			$subcategory = Subcategory::where('id', $product->subcategory_id)->first();
			$subcategory->is_publish += 1;
			$subcategory->save();

			return 'success';
		}else{
			return 'failed';
		}

	}

	public function unactivated_product(Request $request)
	{
		$product = Product::where('id', $request->id)->first();
		$product->status = "unactive";
		$product->save();

		$subcategory = Subcategory::where('id', $product->subcategory_id)->first();
		$subcategory->is_publish -= 1;
		$subcategory->save();
	}

	public function ajax_attr(Request $request)
	{
		$product = Product::where('id', $request->id)->first();
		$unserialize = unserialize($product->properties);
		echo "<pre>";
		print_r($unserialize[$request->color]);
	}

	public function edit_qty(Request $request)
	{
		$product = Product::where('id', $request->product_id)->first();
		$unserialize = unserialize($product->properties);
		$unserialize[$request->color][$request->id] = $request->qty;
		$properties = serialize($unserialize);
		$total = ($product->quantity - $request->qty_tmp) + $request->qty;

		$product->quantity = $total;
		$product->properties = $properties;
		$product->save();

		return $total;
	}

	public function delete($id)
	{
		$product = Product::find($id);
		$category = Category::where('id', $product->category_id)->first();
		$inOrder = OrderDetail::where('product_id', $id)
								->where('review', null)
								->get();
		$reviews = Reviews::where('product_id', $id)->get();
		$subcategory = Subcategory::where('id', $product->subcategory_id)->first();

		if(!$product) {
			return redirect('master/produk/list')->with('error', 'Data tidak ada');
		}else{
			$image = unserialize($product->image);
			$path = base_path().'/storage/photo_product/';

			if(count($inOrder) > 0) {
				$product->status = 'unactive';

				if($product->save()) {
					return redirect('master/produk/list')->with('success', 'Produk tidak bisa di hapus karena masih dalam order, produk sementara di non aktifkan');
				}else{
					return redirect('master/produk/list')->with('error', '404');					
				}

			}elseif(count($inOrder) == 0) {

				if($product->delete()){

					foreach ($reviews as $review) {
						$reviews->delete();
					}

					foreach ($inOrder as $order_product) {

							foreach ($order_product as $orders) {
								$PaymentConfirmation = PaymentConfirmation::where('order_id', $orders->id)->first();
								$PaymentConfirmation->delete();	
							}

						$orders = Order::where('id', $order_product->order_id)->get();
						$orders->delete();
					}

					$total_product = $category->total_product - 1;
					$category->total_product = $total_product;
					$category->save();
					$total_subcategory = $subcategory->total_product - 1;
					$subcategory->total_product = $total_subcategory;
					$subcategory->save();

					if(!$image == NULL){

						foreach($image as $image){
							File::delete($path.$image);
						}

					}

					return redirect('master/produk/list')->with('success', 'Produk '.$product->name.' Berhasil Dihapus');
				}else{
					return redirect('master/produk/list')->with('error', '404');	
				}

			}

		}
	}

	public function get_list_subcategory(Request $request)
	{
		$subcategory = Subcategory::where('category_id', $request->category_id)->get();
		
		return $subcategory;
	}

	public function get_product_subcategory(Request $request)
	{
		$this->data['product'] = Product::where('subcategory_id', $request->subcategory_id)->get();
		
		return view('admin/product/category_product')->with('data', $this->data);
	}

	public function get_product_by_status(Request $request)
	{
		$this->data['product'] = Product::where('status', $request->status);

		if($request->category_id) {
		    $this->data['product']->where('category_id', $request->category_id);
		};

		if($request->subcategory_id) {
		    $this->data['product']->where('subcategory_id', $request->subcategory_id);
		}

		$this->data['product'] = $this->data['product']->get();
		
		return view('admin/product/category_product')->with('data', $this->data);
	}

	public function ajax_datatable(Request $request)
	{
		
	}

}
