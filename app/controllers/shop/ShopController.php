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
        $dataShow = array();
        //FunctionLib::debug($this->user_shop);
        $this->layout->content = View::make('site.ShopLayouts.ShopListProduct')
            ->with('data',$dataShow)
            ->with('user', $this->user_shop);
    }

    /****************************************************************************************************************************
     * Thông tin shop
     * **************************************************************************************************************************
     */
    public function shopInfor(){
        $dataShow = array();
        //FunctionLib::debug($this->user_shop);
        $this->layout->content = View::make('site.ShopLayouts.ShopEditInfor')
            ->with('data',$dataShow)
            ->with('user', $this->user_shop);
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
        //$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $dataSearch = Order::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        //FunctionLib::debug($dataSearch);

        $arrStatusOrder = array(-1 => '---- Trạng thái đơn hàng ----',
            CGlobal::ORDER_STATUS_NEW => 'Đơn hàng mới',
            CGlobal::ORDER_STATUS_CHECKED => 'Đơn hàng đã xác nhận',
            CGlobal::ORDER_STATUS_SUCCESS => 'Đơn hàng thành công',
            CGlobal::ORDER_STATUS_CANCEL => 'Đơn hàng hủy');
        $optionStatus = FunctionLib::getOption($arrStatusOrder, $search['order_status']);

        $this->layout->content = View::make('site.ShopLayouts.ShopListOrder')
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

