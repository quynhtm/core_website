<?php
/**
 * Created by PhpStorm.
 * User: Tuan
 * Date: 29/05/2015
 * Time: 8:24 CH
 */

class DashBoardController extends BaseAdminController{

    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        $this->layout->content = View::make('admin.DashBoard.index');
    }

}