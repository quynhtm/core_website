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
    protected $user = array();
    public function __construct()
    {
        //$this->user = Session::has('user_shop') ? Session::get('user_shop') : array();
        if (!User::isLogin()) {
            Redirect::route('admin.login',array('url'=>self::buildUrlEncode(URL::current())))->send();
        }

        $this->user = User::user_login();
        if($this->user && sizeof($this->user['user_permission']) > 0){
            $this->permission = $this->user['user_permission'];
        }
        if(in_array('root',$this->permission)){
            $this->is_root = true;
        }

        //View::share('aryPermission',$this->permission);
        View::share('user',$this->user);
        //View::share('is_root',$this->is_root);
    }

}