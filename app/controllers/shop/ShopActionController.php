<?php

class ShopActionController extends BaseShopController
{
    private $error = array();
    public function __construct()
    {
        parent::__construct();
    }

    /****************************************************************************************************************************
     * Thông tin shop
     * **************************************************************************************************************************
     */
    public function shopChangePass(){
        $data = array();
        $shop_id = 0;
        if($this->user_shop) {
            $shop_id = $this->user_shop->shop_id;
        }
        $this->layout->content = View::make('site.ShopAction.ChangePass')
            ->with('id', $shop_id)
            ->with('user', $this->user_shop)
            ->with('data', $data);
    }
    public function postChangePass(){
        $shop_id = $this->user_shop->shop_id;

        $dataSave['user_password'] = $user_password = addslashes(Request::get('user_password'));
        $dataSave['user_password_old'] = addslashes(Request::get('user_password_old'));
        $dataSave['user_password_reply'] = addslashes(Request::get('user_password_reply'));

        if ($this->validUserInforShop($dataSave) && empty($this->error)) {
            if ($shop_id > 0) {
                //cap nhat
                $userShopUpdate['user_password'] = User::encode_password(trim($user_password));
                if (UserShop::updateData($shop_id, $userShopUpdate)) {
                    //cập nhật lại thông tin user
                    Session::forget('user_shop');//xóa session
                    return Redirect::route('site.shopLogin');
                }
            }
        }
        $this->layout->content =  View::make('site.ShopAction.ChangePass')
            ->with('id', $shop_id)
            ->with('data', $dataSave)
            ->with('error', $this->error);
    }

    public function shopInfor(){
       
        //Include style.
        FunctionLib::link_css(array(
       		'lib/upload/cssUpload.css',
        ));
        
        //Include javascript.
        FunctionLib::link_js(array(
	        'lib/upload/jquery.uploadfile.js',
	        'lib/ckeditor/ckeditor.js',
	        'frontend/js/site.js',
	        'js/common.js',
        ));
        
        
        $data = array();
        if($this->user_shop) {
            $shop_id = $this->user_shop->shop_id;
            //$item = UserShop::find($id);
            $item = UserShop::getByID($shop_id);
            if($item){
            	$data['shop_id'] = $item->shop_id;
            	$data['shop_name'] = $item->shop_name;
                $data['user_shop'] = $item->user_shop;
                $data['shop_phone'] = $item->shop_phone;
                $data['shop_email'] = $item->shop_email;
                $data['shop_address'] = $item->shop_address;
                $data['shop_about'] = $item->shop_about;
                $data['shop_transfer'] = $item->shop_transfer;
                $data['shop_category'] = $item->shop_category;
                $data['shop_province'] = $item->shop_province;
                $data['is_shop'] = $item->is_shop;
                $data['shop_status'] = $item->shop_status;
                $data['shop_logo'] = $item->shop_logo;
            }
        }
        $arrCategory = Category::buildTreeCategory();
        $arrCateShop = isset($data['shop_category'])? explode(',',$data['shop_category']): array();
        //tỉnh thành
        $arrProvince = Province::getAllProvince();
        $optionProvince = FunctionLib::getOption(array(-1=>' ---Chọn tỉnh thành ----')+$arrProvince, isset($data['shop_province'])?$data['shop_province']:-1);

        $this->layout->content = View::make('site.ShopAction.EditUserShop')
            ->with('id', $shop_id)
            ->with('arrCategory', $arrCategory)
            ->with('arrCateShop', $arrCateShop)
            ->with('optionProvince', $optionProvince)
            ->with('user', $this->user_shop)
            ->with('data', $data);
    }
    public function updateShopInfor(){
        FunctionLib::link_js(array(
            'lib/ckeditor/ckeditor.js',
        ));
        $shop_id = $this->user_shop->shop_id;

        $dataSave['shop_name'] = addslashes(Request::get('shop_name'));
        $dataSave['shop_phone'] = addslashes(Request::get('shop_phone'));
        $dataSave['shop_email'] = addslashes(Request::get('shop_email'));
        $dataSave['shop_address'] = addslashes(Request::get('shop_address'));
        $dataSave['shop_about'] = addslashes(Request::get('shop_about'));
        $dataSave['shop_province'] = addslashes(Request::get('shop_province'));
        $dataSave['shop_transfer'] = addslashes(Request::get('shop_transfer'));
        $dataSave['shop_logo'] = addslashes(Request::get('image_primary'));
        
        
        $arrCateShop = Request::get('checkCategoryShop',array());
        $dataSave['shop_category'] = !empty($arrCateShop)? join(',',$arrCateShop): '';

        if ($this->validUserInforShop($dataSave) && empty($this->error)) {
            if ($shop_id > 0) {
                //cap nhat
                if (UserShop::updateData($shop_id, $dataSave)) {
                    //cập nhật lại thông tin user
                    $userShop = UserShop::getByID($shop_id);
                    Session::forget('user_shop');//xóa session
                    Session::put('user_shop', $userShop, 60*24);
                    return Redirect::route('shop.adminShop');
                }
            }
        }

        $arrCategory = Category::buildTreeCategory();
        $arrCateShop = isset($dataSave['shop_category'])? explode(',',$dataSave['shop_category']): array();
        //tỉnh thành
        $arrProvince = Province::getAllProvince();
        $optionProvince = FunctionLib::getOption(array(-1=>' ---Chọn tỉnh thành ----')+$arrProvince, isset($dataSave['shop_province'])?$dataSave['shop_province']:-1);

        $this->layout->content =  View::make('site.ShopAction.EditUserShop')
            ->with('id', $shop_id)
            ->with('arrCategory', $arrCategory)
            ->with('arrCateShop', $arrCateShop)
            ->with('optionProvince', $optionProvince)
            ->with('data', $dataSave)
            ->with('error', $this->error);
    }
    private function validUserInforShop($data=array()) {
        if(!empty($data)) {
            if(isset($data['shop_name']) && trim($data['shop_name']) == '') {
                $this->error[] = 'Tên shop không được trống';
            }
            if(isset($data['shop_email']) && trim($data['shop_email']) == '') {
                $this->error[] = 'Email shop không được trống';
            }
            if(isset($data['shop_phone']) && trim($data['shop_phone']) == '') {
                $this->error[] = 'Phone shop không được trống';
            }
            if(isset($data['shop_address']) && trim($data['shop_address']) == '') {
                $this->error[] = 'Địa chỉ shop không được trống';
            }
            if(isset($data['shop_category']) && trim($data['shop_category']) == '') {
                $this->error[] = 'Shop chưa chọn danh mục sản phẩm';
            }
            if(isset($data['shop_transfer']) && trim($data['shop_transfer']) == '') {
                $this->error[] = 'Chưa nhập thông tin vận chuyển';
            }
            if(isset($data['shop_about']) && trim($data['shop_about']) == '') {
                $this->error[] = 'Chưa nhập giới thiệu chi tiết về shop';
            }
            //thay doi pass
            if(isset($data['user_password_old'])) {
                if(trim($data['user_password_old']) == '') {
                    $this->error[] = 'Bạn chưa nhập mật khẩu cũ';
                }else{
                    if($this->user_shop->user_password !== User::encode_password(trim($data['user_password_old']))){
                        $this->error[] = 'Mật khẩu cũ không đúng';
                    }
                }
            }
            if(isset($data['user_password'])) {
                if(trim($data['user_password']) == '') {
                    $this->error[] = 'Bạn chưa nhập mật khẩu mới';
                }else{
                    if(isset($data['user_password_reply']) && $data['user_password_reply'] == '') {
                        $this->error[] = 'Bạn chưa nhập lại mật khẩu mới';
                    }elseif(strcmp($data['user_password'],$data['user_password_reply']) != 0){
                        $this->error[] = 'Bạn nhập lại mật khẩu mới chưa đúng';
                    }
                }
            }
            return true;
        }
        return false;
    }

    /****************************************************************************************************************************
     * Quản lý liên hệ với quản trị site của shop
     * **************************************************************************************************************************
     */
    public function shopLisContact(){
        CGlobal::$pageShopTitle = "Quản lý đơn hàng | ".CGlobal::web_name;
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['order_id'] = addslashes(Request::get('order_id',''));
        $search['order_product_name'] = addslashes(Request::get('order_product_name',''));
        $search['order_status'] = (int)Request::get('order_status',-1);
        $search['order_user_shop_id'] = (isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0)?(int)$this->user_shop->shop_id: 0;//tìm theo shop
        //$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $dataSearch = (isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0) ? Order::searchByCondition($search, $limit, $offset,$total): array();
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        //FunctionLib::debug($dataSearch);

        $arrStatusOrder = array(-1 => '---- Trạng thái đơn hàng ----',
            CGlobal::ORDER_STATUS_NEW => 'Đơn hàng mới',
            CGlobal::ORDER_STATUS_CHECKED => 'Đơn hàng đã xác nhận',
            CGlobal::ORDER_STATUS_SUCCESS => 'Đơn hàng thành công',
            CGlobal::ORDER_STATUS_CANCEL => 'Đơn hàng hủy');
        $optionStatus = FunctionLib::getOption($arrStatusOrder, $search['order_status']);

        $this->layout->content = View::make('site.ShopAction.ListContact')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $arrStatusOrder)
            ->with('user', $this->user_shop);
    }

}

