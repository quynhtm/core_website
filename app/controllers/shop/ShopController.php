<?php

class ShopController extends BaseShopController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function shopAdmin(){
        $dataShow = array();
        $this->layout->content = View::make('site.ShopLayouts.ShopHome')
            ->with('data',$dataShow)
            ->with('user', $this->user_shop);
    }

    /**************************************************************************************************************************
     * Quản lý sản phẩm shop
     **************************************************************************************************************************
     */
    public function shopListProduct(){
        CGlobal::$pageShopTitle = "Quản lý sản phẩm | ".CGlobal::web_name;
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['product_name'] = addslashes(Request::get('product_name',''));
        $search['product_status'] = (int)Request::get('product_status',-1);
        $search['category_id'] = (int)Request::get('category_id',-1);
        $search['user_shop_id'] = (isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0)?(int)$this->user_shop->shop_id: 0;//tìm theo shop
        //$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $dataSearch = (isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0) ? Product::searchByCondition($search, $limit, $offset,$total): array();
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        //FunctionLib::debug($search);

        $arrStatusOrder = array(-1 => '---- Trạng thái đơn hàng ----',
            CGlobal::ORDER_STATUS_NEW => 'Đơn hàng mới',
            CGlobal::ORDER_STATUS_CHECKED => 'Đơn hàng đã xác nhận',
            CGlobal::ORDER_STATUS_SUCCESS => 'Đơn hàng thành công',
            CGlobal::ORDER_STATUS_CANCEL => 'Đơn hàng hủy');
        $optionStatus = FunctionLib::getOption($arrStatusOrder, $search['product_status']);

        $this->layout->content = View::make('site.ShopLayouts.ListProduct')
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

    public function getEditProduct($product_id = 0){
        FunctionLib::link_js(array(
            'lib/ckeditor/ckeditor.js',
        ));
        CGlobal::$pageShopTitle = "Sửa sản phẩm | ".CGlobal::web_name;
        $product = array();
        if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $product_id > 0){
            $product = Product::getProductByShopId($this->user_shop->shop_id,$product_id);
        }
        $error = array();
        if(empty($product)){
            return Redirect::route('shop.listProduct');
        }

        $dataShow = array('product_id'=>$product->product_id,
            'product_name'=>$product->product_name,
            'category_id'=>$product->category_id,
            'product_price_sell'=>$product->product_price_sell,
            'product_price_market'=>$product->product_price_market,
            'product_price_input'=>$product->product_price_input,
            'product_type_price'=>$product->product_type_price,
            'product_selloff'=>$product->product_selloff,
            'product_is_hot'=>$product->product_is_hot,
            'product_sort_desc'=>$product->product_sort_desc,
            'product_content'=>$product->product_content,
            'product_image'=>$product->product_image,
            'product_image_hover'=>$product->product_image_hover,
            'product_image_other'=>$product->product_image_other,
            'product_order'=>$product->product_order,
            'quality_input'=>$product->quality_input,
            'product_status'=>$product->product_status);

        //FunctionLib::debug($product);

        $arrStatusOrder = array(-1 => '---- Trạng thái đơn hàng ----',
            CGlobal::ORDER_STATUS_NEW => 'Đơn hàng mới',
            CGlobal::ORDER_STATUS_CHECKED => 'Đơn hàng đã xác nhận',
            CGlobal::ORDER_STATUS_SUCCESS => 'Đơn hàng thành công',
            CGlobal::ORDER_STATUS_CANCEL => 'Đơn hàng hủy');
        $optionStatus = FunctionLib::getOption($arrStatusOrder,isset($product->product_status)? $product->product_status:CGlobal::status_hide);

        $this->layout->content = View::make('site.ShopLayouts.EditProduct')
            ->with('error', $error)
            ->with('product_id', $product_id)
            ->with('data', $dataShow)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $arrStatusOrder)
            ->with('user', $this->user_shop);
    }

    /****************************************************************************************************************************
     * Thông tin shop
     * **************************************************************************************************************************
     */
    public function shopInfor(){
        FunctionLib::link_js(array(
            'lib/ckeditor/ckeditor.js',
        ));
        $data = array();
        if($this->user_shop) {
            $shop_id = $this->user_shop->shop_id;
            //$item = UserShop::find($id);
            $item = UserShop::getByID($shop_id);
            if($item){
                $data['shop_name'] = $item->shop_name;
                $data['user_shop'] = $item->user_shop;
                $data['shop_phone'] = $item->shop_phone;
                $data['shop_email'] = $item->shop_email;
                $data['shop_address'] = $item->shop_address;
                $data['shop_about'] = $item->shop_about;
                $data['shop_transfer'] = $item->shop_transfer;
                $data['shop_category'] = $item->shop_category;
                $data['is_shop'] = $item->is_shop;
                $data['shop_status'] = $item->shop_status;
            }
        }
        //FunctionLib::debug($data);
        $this->layout->content = View::make('site.ShopLayouts.EditUserShop')
            ->with('id', $shop_id)
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
        $dataSave['shop_transfer'] = addslashes(Request::get('shop_transfer'));

        if ($this->validUserInforShop($dataSave) && empty($this->error)) {
            if ($shop_id > 0) {
                //cap nhat
                if (UserShop::updateData($shop_id, $dataSave)) {
                    return Redirect::route('shop.adminShop');
                }
            }
        }

        $this->layout->content =  View::make('site.ShopLayouts.EditUserShop')
            ->with('id', $shop_id)
            ->with('data', $dataSave)
            ->with('error', $this->error);
    }
    private function validUserInforShop($data=array()) {
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

    /****************************************************************************************************************************
     * Quản lý đơn hàng của shop
     * **************************************************************************************************************************
     */
    public function shopListOrder(){
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

        $this->layout->content = View::make('site.ShopLayouts.ListOrder')
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

