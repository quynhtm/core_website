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
Route::get('{cat}/d-{id}/{name}.html',array('as' => 'site.detailProduct','uses' =>'SiteHomeController@detailProduct'))->where('id', '[0-9]+');
Route::get('danh-muc/c-{id}/{name}.html',array('as' => 'site.listProduct','uses' =>'SiteHomeController@listProduct'))->where('id', '[0-9]+');

/*tin tưc*/
Route::get('c-{id}/{name}.html',array('as' => 'site.list','uses' =>'SiteHomeController@listNew'))->where('id', '[0-9]+');
Route::get('tin-tuc.html',array('as' => 'site.list','uses' =>'SiteHomeController@listNew'))->where('id', '[0-9]+');
Route::get('{cat}/t-{id}/{name}.html',array('as' => 'site.detailNew','uses' =>'SiteHomeController@detailNew'))->where('id', '[0-9]+');

/*page 404*/
Route::get('thong-bao-tim-kiem.html',array('as' => 'site.Error','uses' => 'SiteHomeController@pageError'));

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
Route::get('dang-nhap.html',array('as' => 'site.shopLogin','uses' =>'SiteHomeController@shopLogin'));
Route::get('dang-xuat.html',array('as' => 'site.shopLogin','uses' =>'SiteHomeController@logout'));

//quan ly san pham
Route::get('quan-ly-san-pham.html',array('as' => 'shop.listProduct','uses' =>'ShopController@ShopListProduct'));

//quan ly don hang
Route::get('quan-ly-don-hang.html',array('as' => 'shop.listOrder','uses' =>'ShopController@ShopListOrder'));








