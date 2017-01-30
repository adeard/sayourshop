<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//ADE
Route::match(['get', 'post'], 'login', 'UserController@login');
Route::match(['get', 'post'], 'hapus_alamat/{no_alamat}', 'UserController@delete_address');

Route::get('/', 'HomeController@index');
Route::get('cari', 'ProductController@search');
Route::get('ajax_cari', 'ProductController@ajax_search');
Route::get('ajax_category_search', 'ProductController@ajax_category_search');
Route::get('produk/{slug}', 'ProductController@product');
Route::get('produk/{slug}/{subcategory}', 'ProductController@subproduct');
Route::get('produk/{slug}/{subcategory}/{id}', 'ProductController@detail');
Route::get('detail/{id}', 'ProductController@detail_cart');
Route::get('login_form', 'UserController@login_form');
Route::get('logout', 'UserController@logout');
Route::get('daftar', 'UserController@register_form');
Route::get('dashboard', 'UserController@dashboard');
Route::get('form_ubah_pass', 'UserController@change_pass_form');
Route::get('cek_order_form', 'OrderController@check_order_form');
// Route::get('tambah_rek', 'UserController@add_bank_acc');
// Route::get('hapus_rek/{no_rek}', 'UserController@delete_bank_acc');
Route::get('checkout_order', 'OrderController@checkout_order');
Route::get('konten_kota', 'OrderController@city_content');
Route::get('konten_kecamatan', 'OrderController@district_content');
Route::get('order_validate/[no_invoice]', 'OrderController@order_validate');
Route::get('discount', 'OrderController@discount');
Route::get('order_review/{id}', 'OrderController@order_review');
Route::get('order_detail', 'UserController@modal_detail');
Route::get('konfirmasi_pembayaran', 'PaymentController@payment_form');
Route::get('check_order', 'OrderController@check_order');
Route::get('konten_alamat', 'UserController@address_content');
Route::get('terms_conditions', 'HomeController@terms_conditions');
Route::get('privacy_policy', 'HomeController@privacy_policy');
Route::get('mail', 'UserController@mail');
Route::get('update_order', 'UserController@update_order');
Route::get('sort_product', 'ProductController@sort_product');
Route::get('sort_search', 'ProductController@sort_search');
Route::get('address_user', 'UserController@address_user');

Route::post('save_photo', 'ProductController@save_photo');
Route::post('order', 'OrderController@order');
Route::post('delete_order', 'OrderController@delete_order');
Route::post('update_order_quantity', 'OrderController@update_order_quantity');
Route::post('shipping_new_address', 'OrderController@check_shipping_new');
Route::post('size_product', 'ProductController@size_product');
Route::post('modal_review', 'OrderController@modal_review');
Route::post('add_review', 'OrderController@add_review');
Route::post('product_content', 'ProductController@product_content');
Route::post('subproduct_content', 'ProductController@subproduct_content');
Route::post('review_content', 'ProductController@review_content');
Route::post('wishlist', 'ProductController@wishlist');
Route::post('del_wishlist', 'ProductController@del_wishlist');
Route::post('check_invoice', 'PaymentController@check_invoice');
Route::post('check_paid', 'PaymentController@check_paid');
Route::post('send_message', 'UserController@ask_product');
Route::post('register', 'UserController@register');
Route::post('update', 'UserController@update');
Route::post('get_shipping', 'OrderController@get_cost');
Route::post('cek_ongkir', 'OrderController@check_shipping');
Route::post('upload_photopic', 'UserController@upload');
Route::post('pembayaran', 'PaymentController@payment');
Route::post('ubah_pass', 'UserController@change_pass');
Route::post('tambah_alamat', 'UserController@add_address');
Route::post('checkout', 'OrderController@checkout');
Route::post('subscribe', 'HomeController@subscribe');
//END ADE

//UDIN
Route::get('keranjang', 'OrderController@cart_form');
Route::get('lupa_pass', 'UserController@forgot_pass_form');

Route::match(['get', 'post'], 'contact', 'HomeController@contact_us');
//END UDIN

//ADMIN
Route::get('master/login',function(){
	return view('admin.login');
})->middleware('isLoggedIn');

Route::get('master','Admin\AdminController@home');
Route::get('master/produk/list','Admin\AdminController@list_product');
Route::get('master/message/list', 'Admin\AdminController@list_message');
Route::get('modal_variant', 'Admin\ProductController@modal_variant');
// Route::post('add_variant', 'Admin\ProductController@modal_variant');
Route::get('month_order', 'Admin\AdminController@order_month');

Route::post('master/produk/tambah','Admin\ProductController@add_product');
Route::post('master/login','UserController@admin_login');

//ADMIN VIEW (Detail)
Route::get('master/category/view/{id}','Admin\AdminController@view_category');
Route::get('master/subcategory/view/{id}','Admin\AdminController@view_subcategory');
Route::get('master/distributor/view/{id}','Admin\DistributorController@view');
Route::get('master/message/view/{id}','Admin\AdminController@view_message');
Route::get('ajax_modal_attr','Admin\ProductController@ajax_attr');

//ADMIN LIST
Route::get('master/setting/coupon','Admin\CouponController@list_coupon');
Route::get('master/distributor/list','Admin\DistributorController@list_distributor');
Route::get('master/setting/bank_account','Admin\SettingController@bank_account_form');
Route::get('master/setting/category/list','Admin\SettingController@list_category');
Route::get('master/setting/subcategory/list','Admin\SettingController@list_subcategory');
Route::get('master/transaction/order','Admin\TransactionController@order');
Route::get('master/transaction/pembayaran','Admin\TransactionController@payment_list');
Route::get('master/user/list','Admin\AdminController@list_user');
Route::get('master/produk_detail','Admin\ProductController@modal_product');
Route::get('banner_list', 'Admin\SettingController@list_banner');
Route::get('home_banner', 'Admin\SettingController@home_banner');
Route::get('list_order', 'Admin\TransactionController@list_order');
Route::get('category_product', 'Admin\ProductController@category_product');
Route::get('get_list_subcategory', 'Admin\ProductController@get_list_subcategory');
Route::get('get_product_subcategory', 'Admin\ProductController@get_product_subcategory');
Route::get('get_product_by_status', 'Admin\ProductController@get_product_by_status');
Route::get('get_list_user', 'Admin\AdminController@get_list_user');
Route::get('get_list_distributor', 'Admin\DistributorController@get_list_distributor');
Route::get('get_list_product', 'Admin\ProductController@get_list_product');

//ADMIN ADD
Route::match(['get', 'post'], 'master/user/create', 'Admin\AdminController@add_user');
Route::match(['get', 'post'], 'master/distributor/create', 'Admin\DistributorController@create');

Route::get('master/produk/create', 'Admin\ProductController@create');
Route::get('master/setting/bank_account/add', 'Admin\SettingController@add_bank_account');
Route::get('master/setting/category/create', 'Admin\SettingController@create_category');
Route::get('master/setting/subcategory/create', 'Admin\SettingController@create_subcategory');

Route::post('add_variant', 'Admin\ProductController@add_variant');
Route::post('insert_banner', 'Admin\SettingController@add_home_banner');
Route::post('master/transaction/payment', 'Admin\TransactionController@payment');
Route::post('master/category/add', 'Admin\AdminController@add_category');
Route::post('master/subcategory/add', 'Admin\AdminController@add_subcategory');
Route::post('master/setting/coupon/create', 'Admin\CouponController@create');

//ADMIN EDIT
Route::post('master/setting/coupon/edit/{id}', 'Admin\CouponController@edit');
Route::post('activated_product', 'Admin\ProductController@activated_product');
Route::post('unactivated_product', 'Admin\ProductController@unactivated_product');
Route::post('edit_qty', 'Admin\ProductController@edit_qty');

Route::match(['get', 'post'], 'master/category/edit/{id}', 'Admin\AdminController@edit_category');
Route::match(['get', 'post'], 'master/subcategory/edit/{id}', 'Admin\AdminController@edit_subcategory');
Route::match(['get', 'post'], 'master/distributor/edit/{id}', 'Admin\DistributorController@edit');

//ADMIN DELETE
Route::get('master/category/delete/{id}', 'Admin\AdminController@delete_category');
Route::get('master/subcategory/delete/{id}', 'Admin\AdminController@delete_subcategory');
Route::get('master/setting/bank_account/{id}', 'Admin\SettingController@del_bank_account');
Route::get('master/setting/coupon/{id}', 'Admin\CouponController@delete');
Route::get('master/distributor/delete/{id}', 'Admin\DistributorController@delete');
Route::get('master/message/delete/{id}', 'Admin\AdminController@delete_message');
Route::get('master/produk/delete/{id}', 'Admin\ProductController@delete');

Route::post('delete_banner', 'Admin\SettingController@delete_category_banner');
Route::post('delete_home_banner', 'Admin\SettingController@delete_home_banner');

//ADMIN AJAX
Route::get('category_banner', 'Admin\SettingController@category_banner');
Route::get('konten_kategori', 'Admin\SettingController@category_content');

Route::post('insert_shipping', 'Admin\TransactionController@insert_shipping');
Route::post('send', 'Admin\TransactionController@send');
Route::post('insert_category_banner', 'Admin\SettingController@insert_category_banner');
Route::post('check_variant', 'Admin\ProductController@check_variant');

//email
Route::get('account-activation/{id}&key={code}', 'UserController@account_activation');

Route::post('master/message/reply', 'Admin\AdminController@mail_reply');

//image
Route::get('photo_banner/{imagefile}', function ($imagefile){
    return Image::make(storage_path() . '/photo_banner/' . $imagefile)->response();
});
Route::get('photo_product/{imagefile}', function ($imagefile){
    return Image::make(storage_path() . '/photo_product/' . $imagefile)->response();
});
Route::get('photo_profile/{imagefile}', function ($imagefile){
    return Image::make(storage_path() . '/photo_profile/' . $imagefile)->response();
});

//Datatables Server-Side
Route::controller('datatables', 'Admin\AdminController', [
    'order_month'  => 'datatables.data',
    'home' => 'datatables',
]);
Route::controller('datatables', 'Admin\TransactionController', [
    'list_order'  => 'datatables.data',
]);
Route::controller('datatables', 'Admin\AdminController', [
    'list_user'  => 'datatables.data',
]);
Route::controller('datatables', 'Admin\DistributorController', [
    'list_distributor'  => 'datatables.data',
]);

Route::get('manageMailChimp', 'MailChimpController@manageMailChimp');

// Route::post('subscribe', ['as'=>'subscribe','uses'=>'MailChimpController@subscribe']);
Route::post('sendCompaign', ['as'=>'sendCompaign','uses'=>'MailChimpController@sendCompaign']);