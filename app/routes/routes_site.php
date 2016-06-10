<?php
/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
/*
 * **********************************************************************************************************************************
 * Route site
 * **********************************************************************************************************************************
 * */
/*home*/
Route::any('/', array('as' => 'site.home','uses' => 'SiteHomeController@index'));

/*list*/
Route::get('danh-muc/c-{id}/{name}.html',array('as' => 'site.list','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*detail*/
Route::get('{cat}/d-{id}/{name}.html',array('as' => 'site.detail','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*search*/
Route::get('tim-kiem.html',array('as' => 'site.search','uses' => 'SiteHomeController@index'));
Route::get('site/search',array('as' => 'site.suggest_search','uses' => 'SiteHomeController@suggestSearch'));

/*thông báo*/
Route::get('thong-bao.html',array('as' => 'home.eventNote','uses' =>'SiteHomeController@eventNote'));




/*
 * **********************************************************************************************************************************
 * Route shop
 * **********************************************************************************************************************************
 * */
//login
Route::get('dang-nhap.html',array('as' => 'shop.login','uses' =>'ShopController@login'));
Route::get('dang-xuat.html',array('as' => 'shop.logut','uses' =>'ShopController@logout'));

//trang chủ shop
Route::get('gian-hang/s-{shop_id}/{name}.html',array('as' => 'shop.home','uses' =>'ShopController@index'))->where('shop_id', '[0-9]+');
Route::get('gian-hang/s-{shop_id}/c-{cat_id}/{cat_name}.html',array('as' => 'shop.ShopListProduct','uses' =>'ShopController@ShopListProduct'))->where('shop_id', '[0-9]+')->where('cat_id', '[0-9]+');

//quan ly san pham
Route::get('quan-ly-san-pham.html',array('as' => 'shopAdmin.listProduct','uses' =>'ShopController@ShopAdminListProduct'));

//quan ly don hang
Route::get('quan-ly-don-hang.html',array('as' => 'shopAdmin.listOrder','uses' =>'ShopController@ShopAdminOrder'));




