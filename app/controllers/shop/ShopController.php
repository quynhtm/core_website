<?php

class ShopController extends BaseShopController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function shopAdmin(){
        //$this->header();
        $dataShow = array();
        //FunctionLib::debug($this->user_shop);
        $this->layout->content = View::make('site.ShopLayouts.ShopHome')
            ->with('data',$dataShow)
            ->with('user', $this->user_shop);
        //$this->footer();
    }

}

