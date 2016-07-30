<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
// used for dev by Quynh
$isDev = Request::get('is_debug','');
if($isDev == 'tech_code'){
    Session::put('is_debug_of_tech', '13031984');
    Config::set('compile.debug',true);
}
if(Session::has('is_debug_of_tech')){
    Config::set('compile.debug',true);
}

/*
 * router cho phan Site
 *
 * */
Route::group(array('prefix' => '/', 'before' => ''), function () {
    require app_path() . '/routes/routes_site.php';
});

/*
 * router cho Ajax
 *
 * */
Route::group(array('prefix' => 'ajax', 'before' => ''), function () {
    Route::post('uploadImage', array('as' => 'ajax.uploadImage','uses' => 'AjaxCommonController@uploadImage'));
});

/*
 * router cho phan Admin
 *
 * */
Route::group(array('prefix' => 'admin', 'before' => ''), function()
{
    /*login logout*/
    Route::get('login/{url?}', array('as' => 'admin.login','uses' => 'LoginController@loginInfo'));
    Route::post('login/{url?}', array('as' => 'admin.login','uses' => 'LoginController@login'));
    Route::get('logout', array('as' => 'admin.logout','uses' => 'LoginController@logout'));

    /*màn hình chính*/
    Route::get('dashboard', array('as' => 'admin.dashboard','uses' => 'DashBoardController@dashboard'));
    Route::get('convert', array('as' => 'admin.convert','uses' => 'BaseAdminController@convert'));

    /*thông tin tài khoản*/
    Route::get('user/view',array('as' => 'admin.user_view','uses' => 'UserController@view'));
    Route::get('user/create',array('as' => 'admin.user_create','uses' => 'UserController@createInfo'));
    Route::post('user/create',array('as' => 'admin.user_create','uses' => 'UserController@create'));
    Route::get('user/edit/{id}',array('as' => 'admin.user_edit','uses' => 'UserController@editInfo'))->where('id', '[0-9]+');
    Route::post('user/edit/{id}',array('as' => 'admin.user_edit','uses' => 'UserController@edit'))->where('id', '[0-9]+');
    Route::get('user/change/{id}',array('as' => 'admin.user_change','uses' => 'UserController@changePassInfo'));
    Route::post('user/change/{id}',array('as' => 'admin.user_change','uses' => 'UserController@changePass'));
    Route::post('user/remove/{id}',array('as' => 'admin.user_remove','uses' => 'UserController@remove'));

    /*thông tin quyền*/
    Route::get('permission/view',array('as' => 'admin.permission_view','uses' => 'PermissionController@view'));
    Route::get('permission/create',array('as' => 'admin.permission_create','uses' => 'PermissionController@createInfo'));
    Route::post('permission/create',array('as' => 'admin.permission_create','uses' => 'PermissionController@create'));
    Route::get('permission/edit/{id}',array('as' => 'admin.permission_edit','uses' => 'PermissionController@editInfo'))->where('id', '[0-9]+');
    Route::post('permission/edit/{id}',array('as' => 'admin.permission_edit','uses' => 'PermissionController@edit'))->where('id', '[0-9]+');

    /*thông tin nhóm quyền*/
    Route::get('groupUser/view',array('as' => 'admin.groupUser_view','uses' => 'GroupUserController@view'));
    Route::get('groupUser/create',array('as' => 'admin.groupUser_create','uses' => 'GroupUserController@createInfo'));
    Route::post('groupUser/create',array('as' => 'admin.groupUser_create','uses' => 'GroupUserController@create'));
    Route::get('groupUser/edit/{id}',array('as' => 'admin.groupUser_edit','uses' => 'GroupUserController@editInfo'))->where('id', '[0-9]+');
    Route::post('groupUser/edit/{id}',array('as' => 'admin.groupUser_edit','uses' => 'GroupUserController@edit'))->where('id', '[0-9]+');

    /*Quản lý danh mục SP*/
    Route::get('category/view',array('as' => 'admin.category_list','uses' => 'CategoryController@view'));
    Route::get('category/getCategroy/{id?}', array('as' => 'admin.category_edit','uses' => 'CategoryController@getCategroy'))->where('id', '[0-9]+');
    Route::post('category/postCategory/{id?}', array('as' => 'admin.category_edit_post','uses' => 'CategoryController@postCategory'))->where('id', '[0-9]+');
    Route::post('category/deleteCategory', array('as' => 'admin.deltete_category_post','uses' => 'CategoryController@deleteCategory'));//ajax
    Route::post('category/updateStatusCategory', array('as' => 'admin.status_category_post','uses' => 'CategoryController@updateStatusCategory'));//ajax

    /*Quản lý San Pham*/
    Route::get('product/view',array('as' => 'admin.product_list','uses' => 'ProductController@view'));
    Route::get('product/getProduct/{id?}', array('as' => 'admin.product_edit','uses' => 'ProductController@getProduct'))->where('id', '[0-9]+');
    Route::post('product/postProduct/{id?}', array('as' => 'admin.product_edit_post','uses' => 'ProductController@postProduct'))->where('id', '[0-9]+');
    Route::post('product/deleteItem', array('as' => 'admin.deltete_product_post','uses' => 'ProductController@deleteItem'));//ajax

    /*Quản lý tin tức*/
    Route::get('news/view',array('as' => 'admin.news_list','uses' => 'NewsController@view'));
    Route::get('news/getNews/{id?}', array('as' => 'admin.news_edit','uses' => 'NewsController@getNews'))->where('id', '[0-9]+');
    Route::post('news/postNews/{id?}', array('as' => 'admin.news_edit_post','uses' => 'NewsController@postNews'))->where('id', '[0-9]+');
    Route::post('news/deleteItem', array('as' => 'admin.deltete_news_post','uses' => 'NewsController@deleteItem'));//ajax


    /*Quan Ly Don Hang*/
    Route::get('order/view',array('as' => 'admin.order_list','uses' => 'OrderController@view'));
    Route::get('order/getOrder/{id?}', array('as' => 'admin.order_edit','uses' => 'OrderController@getOrder'))->where('id', '[0-9]+');
    Route::post('order/postOrder/{id?}', array('as' => 'admin.order_edit_post','uses' => 'OrderController@postOrder'))->where('id', '[0-9]+');
    Route::post('order/deleteItem', array('as' => 'admin.deltete_order_post','uses' => 'OrderController@deleteItem'));

});
