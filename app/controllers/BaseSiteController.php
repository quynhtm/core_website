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

        //m?ng menu danh muc
        $dataCategory = Category::getCategoriessAll();
        $arrCategory = $this->getTreeCategory($dataCategory);
        //FunctionLib::debug($arrCategory);

        $this->layout->header = View::make("site.BaseLayouts.header")
            ->with('arrCategory', $arrCategory)
            ->with('user', $this->user);
    }

    public function footer()
    {
        FunctionLib::site_js('v9/js/footer.js', CGlobal::$POS_END);
        $this->layout->footer = View::make("site.BaseLayouts.footer")
            ->with('user', $this->user);
    }

    /************************************************************************************************************************
     * Nh?ng hàm ph?
     * dùng cho các hàm chính trên
     ************************************************************************************************************************
     */
    public function getTreeCategory($data){
        $arrCategory = array();
        if(!empty($data)){
            foreach ($data as $k=>$value){
                if($value['category_parent_id'] > 0){
                    $arrCategory[$value['category_parent_id']]['arrSubCategory'][] = array(
                        'category_id'=>$value['category_id'],
                        'category_order'=>$value['category_order'],//hien th? th? t? s?p x?p
                        'category_name'=>$value['category_name']);
                }else{
                    //thong tin parent
                    $arrCategory[$value['category_id']]['category_parent_name'] = $value['category_name'];
                    $arrCategory[$value['category_id']]['category_id'] = $value['category_id'];
                    $arrCategory[$value['category_id']]['category_status'] = $value['category_status'];
                    $arrCategory[$value['category_id']]['category_image_background'] = $value['category_image_background'];
                    $arrCategory[$value['category_id']]['category_order'] = $value['category_order'];//hien th? th? t? s?p x?p
                }
            }
            if(!empty($arrCategory)){
                foreach($arrCategory as $key => $val){
                    if(!isset($val['category_id'])){
                        unset($arrCategory[$key]);
                    }
                }
            }
            FunctionLib::sortArrayASC($arrCategory,"category_order");
        }
        return $arrCategory;
    }
}