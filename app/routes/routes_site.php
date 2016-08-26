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
Route::get('t-{id}/{name}.html',array('as' => 'site.detailNew','uses' =>'SiteHomeController@detailNew'))->where('id', '[0-9]+');

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
//login, dang ky, logout shop
Route::get('dang-nhap.html',array('as' => 'site.shopLogin','uses' =>'SiteHomeController@shopLogin'));
Route::post('dang-nhap.html', array('as' => 'site.shopLogin','uses' => 'SiteHomeController@login'));
Route::post('thay-doi-pass.html', array('as' => 'site.user_shop_change_pass','uses' => 'SiteHomeController@shopChangePass'));
Route::get('dang-xuat.html',array('as' => 'site.shopLogout','uses' =>'SiteHomeController@shopLogout'));
Route::get('dang-ky.html',array('as' => 'site.shopRegister','uses' =>'SiteHomeController@shopRegister'));


//quan ly page shop admin
Route::get('shop.html',array('as' => 'shop.adminShop','uses' =>'ShopController@shopAdmin'));
Route::get('quan-ly-san-pham.html',array('as' => 'shop.listProduct','uses' =>'ShopController@shopListProduct'));
Route::get('thong-tin-shop.html',array('as' => 'shop.inforShop','uses' =>'ShopController@shopInfor'));
Route::get('quan-ly-don-hang.html',array('as' => 'shop.listOrder','uses' =>'ShopController@shopListOrder'));








