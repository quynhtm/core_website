<?php

/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 17/04/2016
 * Time: 3:29 CH
 */
class BaseSiteController extends BaseController
{
    protected $layout = 'site.BaseLayouts.index';
    protected $user = array();
    public function __construct()
    {
        $this->user = Session::has('user_shop') ? Session::get('user_shop') : array();
    }

    public function header(){
        FunctionLib::site_js('v9/js/site.js', CGlobal::$POS_HEAD);
        $this->layout->header = View::make("site.BaseLayouts.header")
            ->with('user', $this->user);
    }

    public function footer()
    {
        FunctionLib::site_js('v9/js/footer.js', CGlobal::$POS_END);
        $this->layout->footer = View::make("site.BaseLayouts.footer")
            ->with('user', $this->user);
    }
}