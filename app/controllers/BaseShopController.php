<?php

/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 17/04/2016
 * Time: 3:29 CH
 */
class BaseShopController extends BaseController
{
    protected $layout = 'site.ShopAdmin.index';
    protected $user_shop = array();
    protected $supperAdmin = array();
    public function __construct()
    {
        if (!UserShop::isLogin()) {
            Redirect::route('site.shopLogin',array('url'=>self::buildUrlEncode(URL::current())))->send();
        }
        $this->user_shop = UserShop::user_login();
        if(empty($this->user_shop)){
            return Redirect::route('site.shopLogin');
        }

        if(Session::has('user')){
            $this->supperAdmin = Session::get('user');
        }

        View::share('user_shop',$this->user_shop);//user shop
        View::share('supperAdmin',$this->supperAdmin);//user admin
    }
}