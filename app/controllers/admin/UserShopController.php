<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class UserShopController extends BaseAdminController
{
    private $permission_view = 'user_shop_view';
    private $permission_full = 'user_shop_full';
    private $permission_delete = 'user_shop_delete';
    private $permission_create = 'user_shop_create';
    private $permission_edit = 'user_shop_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện', CGlobal::status_block => 'Khóa');
    private $arrIsShop = array(-1 => 'Tất cả', CGlobal::SHOP_FREE => 'Shop Free', CGlobal::SHOP_NOMAL => 'Shop thường', CGlobal::SHOP_VIP => 'Shop Vip');
    private $error = array();

    public function __construct()
    {
        parent::__construct();

        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'js/common.js',
            'admin/js/admin.js',
            //'lib/dragsort/jquery.dragsort.js',
            //'lib/number/autoNumeric.js',
            //'frontend/js/site.js',
        ));
        CGlobal::$pageAdminTitle = 'Quản lý User Shop';
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        UserShop::updateShopLogout();//cap nhat shop login mà chưa logout

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['shop_id'] = addslashes(Request::get('shop_id',''));
        $search['user_shop'] = addslashes(Request::get('user_shop',''));
        $search['shop_name'] = addslashes(Request::get('shop_name',''));
        $search['shop_status'] = (int)Request::get('shop_status',-1);
        //$search['field_get'] = 'category_id,category_name,category_status';//cac truong can lay

        $dataSearch = UserShop::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['shop_status']);
        $this->layout->content = View::make('admin.UserShop.view')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus)
            ->with('arrIsShop', $this->arrIsShop)

            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function getUserShop($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $data = array();
        if($id > 0) {
            //$item = UserShop::find($id);
            $item = UserShop::getByID($id);
            if($item){
                $data['shop_name'] = $item->shop_name;
                $data['user_shop'] = $item->user_shop;
                $data['shop_phone'] = $item->shop_phone;
                $data['shop_email'] = $item->shop_email;
                $data['shop_address'] = $item->shop_address;
                $data['shop_about'] = $item->shop_about;
                $data['shop_transfer'] = $item->shop_transfer;
                $data['shop_category'] = $item->shop_category;
                $data['shop_status'] = $item->shop_status;
                $data['is_shop'] = $item->is_shop;
                $data['time_start_vip'] = $item->time_start_vip;
                $data['time_end_vip'] = $item->time_end_vip;
            }
        }
        //FunctionLib::debug($data);

        $optionIsShop = FunctionLib::getOption($this->arrIsShop, isset($data['is_shop'])? $data['is_shop'] : -1);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['shop_status'])? $data['shop_status'] : -1);
        $this->layout->content = View::make('admin.UserShop.add')
            ->with('id', $id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionIsShop', $optionIsShop)
            ->with('arrIsShop', $this->arrIsShop)
            ->with('arrStatus', $this->arrStatus);
    }

    public function postUserShop($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }

        $dataSave['shop_name'] = addslashes(Request::get('shop_name'));
        $dataSave['user_shop'] = addslashes(Request::get('user_shop'));
        $dataSave['shop_phone'] = addslashes(Request::get('shop_phone'));
        $dataSave['shop_email'] = addslashes(Request::get('shop_email'));
        $dataSave['shop_address'] = addslashes(Request::get('shop_address'));
        $dataSave['shop_about'] = addslashes(Request::get('shop_about'));
        $dataSave['shop_transfer'] = addslashes(Request::get('shop_transfer'));

        $dataSave['time_start_vip'] = strtotime(Request::get('time_start_vip',''));
        $dataSave['time_end_vip'] = strtotime(Request::get('time_end_vip',''));

        $dataSave['is_shop'] = (int)Request::get('is_shop', 0);
        $dataSave['shop_status'] = (int)Request::get('shop_status', 0);

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(UserShop::updateData($id, $dataSave)) {
                    return Redirect::route('admin.userShop_list');
                }
            } else {
                //them moi
                if(UserShop::addData($dataSave)) {
                    return Redirect::route('admin.userShop_list');
                }
            }
        }
        $optionIsShop = FunctionLib::getOption($this->arrIsShop, isset($dataSave['is_shop'])? $dataSave['is_shop'] : -1);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['shop_status'])? $dataSave['shop_status'] : -1);
        $this->layout->content =  View::make('admin.UserShop.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('optionIsShop', $optionIsShop)
            ->with('error', $this->error)
            ->with('arrStatus', $this->arrStatus);
    }

    public function loginToShop($shop_id=0) {
        if(!$this->is_root){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        if($shop_id > 0){
            $userShop = UserShop::find($shop_id);
            if($userShop){
                Session::put('user_shop', $userShop, 60*24);
                return Redirect::route('shop.adminShop');
            }
        }
        return Redirect::route('admin.userShop_list');
    }

    private function valid($data=array()) {
        return true;
        if(!empty($data)) {
            if(isset($data['shop_name']) && $data['shop_name'] == '') {
                $this->error[] = 'Tên danh mục không được trống';
            }
            if(isset($data['shop_status']) && $data['shop_status'] == -1) {
                $this->error[] = 'Bạn chưa chọn trạng thái cho danh mục';
            }
            return true;
        }
        return false;
    }

    //ajax
    public function deleteUserShop(){
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }
        $shop_id = (int)Request::get('id', 0);
        if ($shop_id > 0 && UserShop::deleteData($shop_id)) {
            //xóa các sản phẩm của shop nay
            $deleteProduct = false;
            $array_product = Product::getListProductOfShopId($shop_id,array('product_id','user_shop_id'));
            if($array_product && sizeof($array_product) > 0){
                foreach($array_product as $product){
                    if(Product::deleteData($product->product_id)){
                        $deleteProduct = true;
                    }
                }
            }
            $result['isIntOk'] = ($deleteProduct)? 1: 0;
        }
        return Response::json($result);
    }

    //ajax
    public function setIsShop(){
        $shop_id = (int)Request::get('shop_id', 0);
        $is_shop = (int)Request::get('is_shop', CGlobal::SHOP_FREE);
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)){
            return Response::json($result);
        }
        if ($shop_id > 0) {
            $dataSave['is_shop'] = $is_shop;
            if($is_shop == CGlobal::SHOP_VIP){
                $dataSave['time_start_vip'] = time();
                $dataSave['time_end_vip'] =  mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1);
            }else{
                $dataSave['time_start_vip'] = 0;
                $dataSave['time_end_vip'] = 0;
            }
            if(UserShop::updateData($shop_id, $dataSave)) {
                //cap nhật sản phẩm theo is_shop
                $array_product = Product::getListProductOfShopId($shop_id,array('product_id','user_shop_id'));
                $updateProduct = false;
                if($array_product && sizeof($array_product) > 0){
                    $inforShop = UserShop::getByID($shop_id);
                    if($inforShop){
                        $arryUpdatePro = array(
                            'user_shop_id'=>$inforShop->shop_id,
                            'is_shop'=>$is_shop,
                            'user_shop_name'=>$inforShop->shop_name,
                            'shop_province'=>$inforShop->shop_province,);
                        foreach($array_product as $product){
                            if(Product::updateData($product->product_id,$arryUpdatePro)){
                                $updateProduct = true;
                            }
                        }
                    }
                }
                $result['isIntOk'] = ($updateProduct)? 1: 0;
            }
        }
        return Response::json($result);
    }
    //ajax
    public function updateStatusUserShop(){
        $shop_id = (int)Request::get('shop_id', 0);
        $shop_status = (int)Request::get('shop_status', CGlobal::SHOP_FREE);
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)){
            return Response::json($result);
        }
        if ($shop_id > 0) {
            $dataSave['shop_status'] = $shop_status;
            if(UserShop::updateData($shop_id, $dataSave)) {
                //cap nhật sản phẩm theo trang thái của shop
                $array_product = Product::getListProductOfShopId($shop_id,array('product_id','user_shop_id'));
                $updateProduct = false;
                if($array_product && sizeof($array_product) > 0){
                    $inforShop = UserShop::getByID($shop_id);
                    if($inforShop){
                        $arryUpdatePro = array(
                            'user_shop_id'=>$inforShop->shop_id,
                            'product_status'=>$shop_status,
                            'user_shop_name'=>$inforShop->shop_name,
                            'shop_province'=>$inforShop->shop_province,);
                        foreach($array_product as $product){
                            if(Product::updateData($product->product_id,$arryUpdatePro)){
                                $updateProduct = true;
                            }
                        }
                    }
                }
                $result['isIntOk'] = ($updateProduct)? 1: 0;
            }
        }
        return Response::json($result);
    }

}