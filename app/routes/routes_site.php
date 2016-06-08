<?php
/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
/*home*/
Route::any('/', array('as' => 'site.home','uses' => 'SiteHomeController@index'));

/*list*/
Route::get('danh-muc/c-{id}/{name}.html',array('as' => 'site.list','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*detail*/
Route::get('{cat}/d-{id}/{name}.html',array('as' => 'site.detail','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*search*/
Route::get('tim-kiem.html',array('as' => 'site.search','uses' => 'SiteHomeController@index'));
Route::get('site/search',array('as' => 'site.suggest_search','uses' => 'SiteHomeController@suggestSearch'));

/*shop*/
Route::get('cua-hang/{name}/s-{id}/danh-sach-san-pham.html',array('as' => 'site.shop','uses' =>'SiteHomeController@index'))->where('id', '[0-9]+');

/*thông báo*/
Route::get('thong-bao.html',array('as' => 'home.eventNote','uses' =>'SiteHomeController@eventNote'));




