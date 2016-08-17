<?php

class SiteHomeController extends BaseSiteController
{
    public function __construct()
    {
        parent::__construct();
    }

    //trang ch?
    public function index(){
        $this->header();
        $dataShow = array();
        $user_shop = array();
        $this->layout->content = View::make('site.SiteLayouts.Home')
            ->with('data',$dataShow)
            ->with('data',$dataShow)
            ->with('user_shop', $user_shop);
        $this->footer();
    }
    //trang login
    public function shopLogin(){
        $this->header();
        $dataShow = array();
        $this->layout->content = View::make('site.ShopLayouts.ShopLogin')
            ->with('data',$dataShow)
            ->with('user', $this->user);
        $this->footer();
    }


}

