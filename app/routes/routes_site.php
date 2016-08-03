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

/*list*/
Route::get('danh-muc/c-{id}/{name}.html',array('as' => 'site.list','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*detail*/
Route::get('{cat}/d-{id}/{name}.html',array('as' => 'site.detail','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*search*/
Route::get('tim-kiem.html',array('as' => 'site.search','uses' => 'SiteHomeController@index'));
Route::get('site/search',array('as' => 'site.suggest_search','uses' => 'SiteHomeController@suggestSearch'));

/*thông báo*/
Route::get('thong-bao.html',array('as' => 'home.eventNote','uses' =>'SiteHomeController@eventNote'));


//trang chủ shop
Route::get('gian-hang/s-{shop_id}/{name}.html',array('as' => 'shop.home','uses' =>'ShopController@index'))->where('shop_id', '[0-9]+');
Route::get('gian-hang/s-{shop_id}/c-{cat_id}/{cat_name}.html',array('as' => 'shop.ShopListProduct','uses' =>'ShopController@ShopListProduct'))->where('shop_id', '[0-9]+')->where('cat_id', '[0-9]+');

/*
 * **********************************************************************************************************************************
 * Route shop
 * Phai login = account Shop với thao tác đc
 * **********************************************************************************************************************************
 * */
//login
Route::get('dang-nhap.html',array('as' => 'shop.login','uses' =>'ShopController@login'));
Route::get('dang-xuat.html',array('as' => 'shop.logut','uses' =>'ShopController@logout'));
//quan ly san pham
Route::get('quan-ly-san-pham.html',array('as' => 'shop.listProduct','uses' =>'ShopController@ShopListProduct'));

//quan ly don hang
Route::get('quan-ly-don-hang.html',array('as' => 'shop.listOrder','uses' =>'ShopController@ShopListOrder'));








