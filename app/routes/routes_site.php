<?php
/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
/*
 * **********************************************************************************************************************************
 * Route site
 * không cần đăng nhập vẫn xem đc
 * **********************************************************************************************************************************
 * */
/*home*/
Route::any('/', array('as' => 'site.home','uses' => 'SiteHomeController@index'));

/*product*/
Route::get('tim-kiem.html',array('as' => 'site.search','uses' => 'SiteHomeController@searchProduct'));
Route::get('{cat}/{id}-{name}.html',array('as' => 'site.detailProduct','uses' =>'SiteHomeController@detailProduct'))->where('id', '[0-9]+');
Route::get('c-{id}/{name}.html',array('as' => 'site.listProduct','uses' =>'SiteHomeController@listProduct'))->where('id', '[0-9]+');

/*tin tưc*/
Route::get('n-{id}/{name}.html',array('as' => 'site.listNewSearch','uses' =>'SiteHomeController@listNewSearch'))->where('id', '[0-9]+');
Route::get('tin-tuc.html',array('as' => 'site.listNew','uses' =>'SiteHomeController@homeNew'));
Route::get('tin-tuc-{id}/{name}.html',array('as' => 'site.detailNew','uses' =>'SiteHomeController@detailNew'))->where('id', '[0-9]+');

//Duy them page danh sách sản phẩm trong giỏ hàng
Route::get('gio-hang.html',array('as' => 'site.listCartOrder','uses' =>'SiteHomeController@listCartOrder'));
Route::get('gui-don-hang.html',array('as' => 'site.sendCartOrder','uses' =>'SiteHomeController@sendCartOrder'));

Route::get('404.html',array('as' => 'site.page404','uses' =>'SiteHomeController@page404'));
Route::get('cam-on-da-mua-hang.html',array('as' => 'site.thanksBuy','uses' =>'SiteHomeController@thanksBuy'));

Route::post('load-product-with-category.html',array('as' => 'site.ajaxLoadItemSubCategory','uses' =>'SiteHomeController@ajaxLoadItemSubCategory'));//ajax

//trang chủ shop
Route::get('shop-{shop_id}/{shop_name}.html',array('as' => 'shop.home','uses' =>'SiteHomeController@shopIndex'))->where('shop_id', '[0-9]+');
Route::get('shop-{shop_id}/c-{cat_id}/{cat_name}.html',array('as' => 'shop.shopListProduct','uses' =>'SiteHomeController@shopListProduct'))->where('shop_id', '[0-9]+')->where('cat_id', '[0-9]+');

/*
 * **********************************************************************************************************************************
 * Route shop
 * Phai login = account Shop với thao tác đc
 * **********************************************************************************************************************************
 * */
//login, dang ky, logout shop
Route::get('dang-nhap.html',array('as' => 'site.shopLogin','uses' =>'SiteHomeController@shopLogin'));
Route::post('dang-nhap.html', array('as' => 'site.shopLogin','uses' => 'SiteHomeController@login'));
Route::get('dang-xuat.html',array('as' => 'site.shopLogout','uses' =>'SiteHomeController@shopLogout'));

Route::get('dang-ky.html',array('as' => 'site.shopRegister','uses' =>'SiteHomeController@shopRegister'));
Route::post('dang-ky.html',array('as' => 'site.shopRegister','uses' =>'SiteHomeController@postShopRegister'));

//quan ly page shop admin
Route::get('shop-cua-tui.html',array('as' => 'shop.adminShop','uses' =>'ShopController@shopAdmin'));
//thong tin shop
Route::get('thong-tin-shop.html',array('as' => 'shop.inforShop','uses' =>'ShopController@shopInfor'));
Route::post('thong-tin-shop.html',array('as' => 'shop.inforShop','uses' =>'ShopController@updateShopInfor'));

//dôi pass
Route::get('thay-doi-pass.html', array('as' => 'site.shopChangePass','uses' => 'ShopController@shopChangePass'));
Route::post('thay-doi-pass.html', array('as' => 'site.shopChangePass','uses' => 'ShopController@postChangePass'));

//san phẩm của shop
Route::get('quan-ly-san-pham.html',array('as' => 'shop.listProduct','uses' =>'ShopController@shopListProduct'));
Route::get('them-san-pham.html',array('as' => 'shop.addProduct','uses' =>'ShopController@getAddProduct'));
Route::get('cap-nhat-san-pham/p-{product_id}-{product_name}.html',array('as' => 'shop.editProduct','uses' =>'ShopController@getEditProduct'))->where('product_id', '[0-9]+');
Route::post('cap-nhat-san-pham/p-{product_id}-{product_name}.html',array('as' => 'shop.editProduct','uses' =>'ShopController@postEditProduct'))->where('product_id', '[0-9]+');
Route::post('shop/setOntop',array('as' => 'shop.setOntop','uses' =>'ShopController@setOnTopProduct'));//ajax
Route::post('shop/deleteProduct',array('as' => 'shop.deleteProduct','uses' =>'ShopController@deleteProduct'));//ajax
Route::post('shop/removeImage',array('as' => 'shop.removeImage','uses' =>'ShopController@removeImage'));//ajax

//don hàng của shop
Route::get('quan-ly-don-hang.html',array('as' => 'shop.listOrder','uses' =>'ShopController@shopListOrder'));

//quan ly banner của shop VIP
Route::get('quan-ly-quang-cao.html',array('as' => 'shop.listBanner','uses' =>'ShopVipController@listBanner'));
Route::get('them-quang-cao.html',array('as' => 'shop.addBanner','uses' =>'ShopVipController@getAddBanner'));
Route::get('cap-nhat-quang-cao/b-{banner_id}-{banner_name}.html',array('as' => 'shop.editBanner','uses' =>'ShopVipController@getEditBanner'))->where('banner_id', '[0-9]+');
Route::post('cap-nhat-quang-cao/b-{banner_id}-{banner_name}.html',array('as' => 'shop.editBanner','uses' =>'ShopVipController@postEditBanner'))->where('banner_id', '[0-9]+');
Route::post('shop/deleteBanner',array('as' => 'shop.deleteBanner','uses' =>'ShopVipController@deleteBanner'));//ajax
Route::post('shop/removeImageBanner',array('as' => 'shop.removeImageBanner','uses' =>'ShopVipController@removeImageBanner'));//ajax

//quan ly NCC của shop VIP
Route::get('quan-ly-nha-cung-cap.html',array('as' => 'shop.listProvider','uses' =>'ShopVipController@listProvider'));
Route::get('them-nha-cung-cap.html',array('as' => 'shop.addProvider','uses' =>'ShopVipController@getAddProvider'));
Route::get('cap-nhat-nha-cung-cap/ncc-{provider_id}-{provider_name}.html',array('as' => 'shop.editProvider','uses' =>'ShopVipController@getEditProvider'))->where('provider_id', '[0-9]+');
Route::post('cap-nhat-nha-cung-cap/ncc-{provider_id}-{provider_name}.html',array('as' => 'shop.editProvider','uses' =>'ShopVipController@postEditProvider'))->where('provider_id', '[0-9]+');
Route::post('shop/deleteProvider',array('as' => 'shop.deleteProvider','uses' =>'ShopVipController@deleteProvider'));//ajax
