<?php

class SiteHomeController extends BaseSiteController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->header();
        $dataShow = array();
        $this->layout->content = View::make('site.SiteLayouts.Home')
            ->with('data',$dataShow)
            ->with('user', $this->user);
        $this->footer();
    }

}

