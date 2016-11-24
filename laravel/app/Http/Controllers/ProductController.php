<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Assets;
use App\Http\Models\Category;
use App\Http\Models\Subcategory;
use App\Http\Models\Product;
use App\Http\Models\Option;
use App\Http\Models\Reviews;
use App\Http\Models\UserMeta;
use DB, Input, Validator, Storage, File, Sentinel, Response;

class ProductController extends HomeController
{
	public function search(Request $request)
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

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', [$js_assets]);
		$this->data['title']		= ucwords('Pencarian');
		$this->data['name']			= $request->search;
		$this->data['category']		= Category::get();
		$this->data['query']		= Product::where('name','like', '%'.$request->search.'%')
												->orderBy('DESC')
												->Paginate(20);

	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'search', array('data' => $this->data));
	}

    public function product($slug)
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

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['slugcategory']	= Category::where('slug',$slug)->first();
		$this->data['title']	= ucwords($this->data['slugcategory']->name);
		$this->data['banner']	= Option::where('meta_key','banner_'.$slug)->first();
		$this->data['slugsubcategory']	='';

		if($this->data['slugcategory'] == NULL){
			$this->data['title'] 	= '404 - not found';
			$this->data['message']	= 'Category that you looking for is not found or has been removed';

			return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'error/404_product', array('data' => $this->data));
		}
		
		return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'product/product', array('data' => $this->data));
	}

	public function subproduct($slug, $subcategory)
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

		$this->data['css_assets'] 		= Assets::load('css', $css_assets);
		$this->data['js_assets'] 		= Assets::load('js', $js_assets);
		$this->data['slugcategory']		= Category::where('slug',$slug)->first();
		$this->data['slugsubcategory']	= Subcategory::where('slug',$subcategory)->first();
		$this->data['banner']			= Option::where('meta_key','banner_'.$slug)->first();
		$this->data['title']			= ucwords($this->data['slugcategory']->name) . ' - ' .ucwords($this->data['slugsubcategory']->subname);
		
		if($this->data['slugcategory'] == NULL || $this->data['slugsubcategory'] == NULL){
			$this->data['title'] 	= '404 - not found';
			$this->data['message']	= 'Subcategory that you looking for is not found or has been removed';
			
			return view('main_layout')->with('data', $this->data)
										->nest('content', 'error/404_product', array('data' => $this->data));
		}
	    
	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'product/product', array('data' => $this->data));
	}

	public function detail($slug, $subcategory, $id)
	{
		$css_assets = [
			'lib-bootstrap', 
			'font-awesome', 
			'font-awesome-min', 
			'flexslider', 
			'color-schemes-core', 
			'color-schemes-turquoise', 
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

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['count']		= Reviews::where('product_id',$id)->get();
		$this->data['product']		= Product::where('id',$id)->first();
		$this->data['title']		= ucwords($this->data['product']->name);
		$this->data['related']		= Product::where('subcategory_id', $this->data['product']->subcategory_id)
												->where('status', 'publish')
												->orderByRaw("RAND()")
												->limit(4)
												->get();
		
		if($this->data['product'] == NULL){
			$this->data['title']	= '404 - not found';
			$this->data['message']	= 'Product that you looking for is not found or has been removed';
			
			return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'error/404_product', array('data' => $this->data));
		}
	    
	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'product/product_detail', array('data' => $this->data));
	}

	public function detail_cart($id)
	{
		$css_assets = [
			'lib-bootstrap', 
			'font-awesome', 
			'font-awesome-min', 
			'flexslider', 
			'color-schemes-core', 
			'color-schemes-turquoise', 
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

		$this->data['css_assets'] 	= Assets::load('css', $css_assets);
		$this->data['js_assets'] 	= Assets::load('js', $js_assets);
		$this->data['title']		= 'Produk';
		$this->data['product']		= Product::where('id',$id)->first();
		$this->data['count']		= Reviews::where('product_id',$id)->get();
		$this->data['related']		= Product::where('subcategory_id', $this->data['product']->subcategory_id)
												->where('status', 'publish')
												->orderByRaw("RAND()")
												->limit(4)
												->get();
	    
	    return view('main_layout')->with('data', $this->data)
								  ->nest('content', 'product/product_detail', array('data' => $this->data));
	}

	public function size_product(Request $request)
	{
		$this->data['product'] = Product::where('id', $request->id)->first();
		$properties = unserialize($this->data['product']->properties);
		
		$this->data['color'] = $request->color;
		$this->data['properties'] = unserialize($this->data['product']->properties);
		$this->data['reverse'] = array_reverse($properties[$request->color]);
		
		return view('product/size_content')->with('data', $this->data);
	}

	public function subproduct_content(Request $request)
	{
		$this->data['product']	= Product::where('category_id', $request->category_id)
											->where('subcategory_id', $request->subcategory_id)
											->where('status', 'publish')
											->Paginate(20);
		
		return view('product/product_content')->with('data', $this->data);
	}

	public function product_content(Request $request)
	{
		$this->data['product']	= Product::where('category_id', $request->category_id)
											->where('status', 'publish')
											->orderBy('DESC')
											->Paginate(20);
		
		return view('product/product_content')->with('data', $this->data);
	}

	public function review_content(Request $request)
	{
		$this->data['review']		= Reviews::where('product_id',$request->product_id)->Paginate(5);
		
		return view('product/review_content')->with('data', $this->data);
	}

	public function wishlist(Request $request)
	{
		$product = Product::where('id', $request->product_id)->first();
		$user = Sentinel::getUser();
		$wishlist = UserMeta::where('meta_key','wishlist')
							->where('user_id', $user->id)
							->first();
		
		if ($wishlist == '') {
			$wish = array();
			array_push($wish, $product->slug);
			$wishlist = new UserMeta;
			$wishlist->user_id = $user->id;
			$wishlist->meta_key = 'wishlist';
		}else{
			$wish = unserialize($wishlist->meta_value);
			array_push($wish, $product->slug);
		}

		$wishlist->meta_value = serialize($wish);
		$wishlist->save();
	}

	public function del_wishlist(Request $request)
	{
		$user = Sentinel::getUser();
		$product = Product::where('id', $request->product_id)
							->where('status', 'publish')
							->first();
		$wishlist = UserMeta::where('meta_key','wishlist')
							->where('user_id', $user->id)
							->first();

		$wish = unserialize($wishlist->meta_value);
		foreach ($wish as $value) {
			if ($value == $product->slug) {
				$wish = array_diff($wish, array($product->slug));
			}

		}

		$new = array_values($wish);
		if (count($new) == 0) {
			$delete = UserMeta::where('meta_key','wishlist')
								->where('user_id', $user->id)
								->delete();
		}else{
			$wishlist->meta_value = serialize($new);
			$wishlist->save();
		}

	}

	public function ajax_search(Request $request)
	{
		$this->data['query']	= Product::where('name','like', '%'.$request->search.'%')
											->where('status', 'publish')
											->orderBy('DESC')
											->Paginate(20);
		
		return view('search_content')->with('data', $this->data);
	}

	public function ajax_category_search(Request $request)
	{
		$this->data['query']	= Product::where('name','like', '%'.$request->search.'%')
											->where('category_id', $request->category_id)
											->where('status', 'publish')
											->orderBy('DESC')
											->Paginate(20);
		
		return view('search_content')->with('data', $this->data);
	}

	public function sort_product(Request $request)
	{
		if ($request->sortby == 'price' || $request->sortby == 'name') {
			$sort = 'ASC';
		}else{
			$sort = 'DESC';
		}

		if ($request->sortby == 'pricedesc') {
			$request->sortby = 'price';
		}
		
		if ($request->category_id != '' && $request->subcategory_id != '') {
			$this->data['product']	= Product::where('category_id', $request->category_id)
												->where('subcategory_id', $request->subcategory_id)
												->where('status', 'publish')
												->orderBy($request->sortby, $sort)
												->Paginate(20);
		}elseif ($request->category_id != '') {
			$this->data['product']	= Product::where('category_id', $request->category_id)
												->where('status', 'publish')
												->orderBy($request->sortby, $sort)
												->Paginate(20);
		}else{
			$this->data['product']	= Product::orderBy($request->sortby, $sort)
												->where('status', 'publish')
												->Paginate(20);
		};

		return view('product/product_content')->with('data', $this->data);
	}

	public function sort_search(Request $request)
	{
		if ($request->sortby == 'price' || $request->sortby == 'name') {
			$sort = 'ASC';
		}else{
			$sort = 'DESC';
		}

		if ($request->sortby == 'pricedesc') {
			$request->sortby = 'price';
		}
		
		if ($request->category_id != '') {
			$this->data['query']	= Product::where('name','like', '%'.$request->search.'%')
												->where('category_id', $request->category_id)
												->where('status', 'publish')
												->orderBy($request->sortby, $sort)
												->Paginate(20);
		}else{
			$this->data['query']	= Product::where('name','like', '%'.$request->search.'%')
												->where('status', 'publish')
												->orderBy($request->sortby, $sort)
												->Paginate(20);
		};

		return view('search_content')->with('data', $this->data);
	}

	public function image($image)
	{
		$path = storage_path(). '/photo_product/' . $image;

	    if(!File::exists($path)) abort(404);

	    $file = File::get($path);
	    $type = File::mimeType($path);
	    $response = Response::make($file, 200);
	    $response->header("Content-Type", $type);

	    return $response;
	}
}
