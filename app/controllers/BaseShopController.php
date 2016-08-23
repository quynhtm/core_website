<?php

/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 17/04/2016
 * Time: 3:29 CH
 */
class BaseShopController extends BaseController
{
    protected $layout = 'site.ShopLayouts.index';
    protected $user_shop = array();
    public function __construct()
    {
        if (!UserShop::isLogin()) {
            Redirect::route('site.shopLogin',array('url'=>self::buildUrlEncode(URL::current())))->send();
        }
        $this->user_shop = UserShop::user_login();

        View::share('user_shop',$this->user_shop);
    }
}