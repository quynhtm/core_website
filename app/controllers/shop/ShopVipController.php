<?php

class ShopVipController extends BaseShopController
{
    private $arrStatus = array(-1 => '--Chọn trạng thái--', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrTarget = array(-1 => '--Chọn target link--', CGlobal::BANNER_NOT_TARGET_BLANK => 'Link trên site', CGlobal::BANNER_TARGET_BLANK => 'Mở tab mới');
    private $arrRunTime = array(-1 => '--Chọn thời gian chạy--', CGlobal::BANNER_NOT_RUN_TIME => 'Chạy mãi mãi', CGlobal::BANNER_IS_RUN_TIME => 'Chạy theo thời gian');
    private $arrIsShop = array(-1 => '--Tất cả--', CGlobal::BANNER_NOT_SHOP => 'Banner của site', CGlobal::BANNER_IS_SHOP => 'Banner của shop');
    private $arrRel = array(CGlobal::LINK_NOFOLLOW => 'Nofollow', CGlobal::LINK_FOLLOW => 'Follow');
    private $arrTypeBanner = array(-1 => '---Chọn loại Banner--',
        CGlobal::BANNER_TYPE_HOME_BIG => 'Banner shop home ',
        CGlobal::BANNER_TYPE_HOME_LEFT => 'Banner trái-phải',
        CGlobal::BANNER_TYPE_HOME_LIST => 'Banner trang list');

    private $arrPage = array(-1 => '--Chọn page--',
        CGlobal::BANNER_PAGE_HOME => 'Page trang chủ',
        CGlobal::BANNER_PAGE_LIST => 'Page danh sách');

    private $error = array();
    private $shop_id = 0;
    public function __construct()
    {
        parent::__construct();
        //check khong phai shop VIP
        if(isset($this->user_shop->is_shop) && $this->user_shop->is_shop != CGlobal::SHOP_VIP){
            Redirect::route('shop.adminShop',array('error'=>1))->send();
        }
        $this->shop_id = isset($this->user_shop->shop_id)?$this->user_shop->shop_id:0;
    }

    /**************************************************************************************************************************
     * Quản lý Banner shop
     **************************************************************************************************************************
     */
    public function listBanner(){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'frontend/js/site.js',
            'js/common.js',
        ));

        CGlobal::$pageShopTitle = "QL banner quảng cáo | ".CGlobal::web_name;
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['banner_name'] = addslashes(Request::get('banner_name',''));
        $search['banner_status'] = (int)Request::get('banner_status',-1);
        $search['banner_shop_id'] = $this->shop_id;
        //$search['field_get'] = 'category_id,news_title,news_status';//cac truong can lay

        $dataSearch = Banner::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['banner_status']);
        $this->layout->content = View::make('site.ShopVip.ListBanner')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus)
            ->with('arrTypeBanner', $this->arrTypeBanner)
            ->with('arrPage', $this->arrPage)
            ->with('arrIsShop', $this->arrIsShop);
    }
    public function getAddBanner($banner_id = 0){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'frontend/js/site.js',
            'js/common.js',
        ));
        $data = array();
        CGlobal::$pageShopTitle = "Thêm quảng cáo | ".CGlobal::web_name;
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['banner_status'])? $data['banner_status']: CGlobal::status_show);
        $optionRunTime = FunctionLib::getOption($this->arrRunTime, isset($data['banner_is_run_time'])? $data['banner_is_run_time']: CGlobal::BANNER_NOT_RUN_TIME);
        $optionTypeBanner = FunctionLib::getOption($this->arrTypeBanner, isset($data['banner_type'])? $data['banner_type']: -1);
        $optionPage = FunctionLib::getOption($this->arrPage, isset($data['banner_page'])? $data['banner_page']: -1);
        $optionTarget = FunctionLib::getOption($this->arrTarget, isset($data['banner_is_target'])? $data['banner_is_target']: CGlobal::BANNER_TARGET_BLANK);
        $optionRel = FunctionLib::getOption($this->arrRel, isset($data['banner_is_rel'])? $data['banner_is_rel']: CGlobal::LINK_NOFOLLOW);

        $this->layout->content = View::make('site.ShopVip.EditBanner')
            ->with('id', $banner_id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionRunTime', $optionRunTime)
            ->with('optionTypeBanner', $optionTypeBanner)
            ->with('optionTarget', $optionTarget)
            ->with('optionRel', $optionRel)
            ->with('optionPage', $optionPage)
            ->with('arrStatus', $this->arrStatus);
    }
    public function getEditBanner($banner_id = 0){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'frontend/js/site.js',
            'js/common.js',
        ));
        CGlobal::$pageShopTitle = "Sửa quảng cáo | ".CGlobal::web_name;
        $data = array();
        if($banner_id > 0) {
            $banner = Banner::getBannerShopByID($banner_id,$this->shop_id);
            $data = array('banner_id'=>$banner->banner_id,
                'banner_name'=>$banner->banner_name,
                'banner_image'=>$banner->banner_image,
                'banner_link'=>$banner->banner_link,
                'banner_order'=>$banner->banner_order,
                'banner_is_target'=>$banner->banner_is_target,
                'banner_is_rel'=>$banner->banner_is_rel,
                'banner_type'=>$banner->banner_type,
                'banner_page'=>$banner->banner_page,
                'banner_category_id'=>$banner->banner_category_id,
                'banner_is_run_time'=>$banner->banner_is_run_time,
                'banner_start_time'=>$banner->banner_start_time,
                'banner_end_time'=>$banner->banner_end_time,
                'banner_is_shop'=>$banner->banner_is_shop,
                'banner_shop_id'=>$banner->banner_shop_id,
                'banner_status'=>$banner->banner_status);
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['banner_status'])? $data['banner_status']: CGlobal::status_show);
        $optionRunTime = FunctionLib::getOption($this->arrRunTime, isset($data['banner_is_run_time'])? $data['banner_is_run_time']: CGlobal::BANNER_IS_RUN_TIME);
        $optionTypeBanner = FunctionLib::getOption($this->arrTypeBanner, isset($data['banner_type'])? $data['banner_type']: -1);
        $optionPage = FunctionLib::getOption($this->arrPage, isset($data['banner_page'])? $data['banner_page']: -1);
        $optionTarget = FunctionLib::getOption($this->arrTarget, isset($data['banner_is_target'])? $data['banner_is_target']: CGlobal::BANNER_TARGET_BLANK);
        $optionRel = FunctionLib::getOption($this->arrRel, isset($data['banner_is_rel'])? $data['banner_is_rel']: CGlobal::LINK_NOFOLLOW);

        $this->layout->content = View::make('site.ShopVip.EditBanner')
            ->with('id', $banner_id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionRunTime', $optionRunTime)
            ->with('optionTypeBanner', $optionTypeBanner)
            ->with('optionTarget', $optionTarget)
            ->with('optionRel', $optionRel)
            ->with('optionPage', $optionPage)
            ->with('arrStatus', $this->arrStatus);
    }
    public function postEditBanner($banner_id = 0){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'frontend/js/site.js',
            'js/common.js',
        ));

        CGlobal::$pageShopTitle = "Sửa quảng cáo | ".CGlobal::web_name;
        $data['banner_name'] = addslashes(Request::get('banner_name'));
        $data['banner_link'] = addslashes(Request::get('banner_link'));
        $data['banner_image'] = addslashes(Request::get('image_primary'));//ảnh chính
        $data['banner_order'] = addslashes(Request::get('banner_order'));

        $data['banner_is_target'] = (int)Request::get('banner_is_target');
        $data['banner_is_rel'] = (int)Request::get('banner_is_rel');
        $data['banner_type'] = (int)Request::get('banner_type');
        $data['banner_page'] = (int)Request::get('banner_page');
        $data['banner_category_id'] = (int)Request::get('banner_category_id');
        $data['banner_start_time'] = Request::get('banner_start_time');
        $data['banner_end_time'] = Request::get('banner_end_time');
        $data['banner_status'] = (int)Request::get('banner_status');
        $data['banner_is_run_time'] = CGlobal::BANNER_IS_RUN_TIME;
        $data['banner_is_shop'] = CGlobal::BANNER_IS_SHOP;
        $data['banner_shop_id'] = $this->shop_id;
        $id_hiden = (int)Request::get('id_hiden', 0);

        if($this->validBanner($data) && empty($this->error)) {
            $id = ($banner_id == 0)?$id_hiden: $banner_id;
            if($id > 0) {
                //cap nhat
                $data['banner_start_time'] = strtotime($data['banner_start_time']);
                $data['banner_end_time'] = strtotime($data['banner_end_time']);
                if(Banner::updateData($id, $data)) {
                    return Redirect::route('shop.listBanner');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['banner_status'])? $data['banner_status']: CGlobal::STASTUS_HIDE);
        $optionRunTime = FunctionLib::getOption($this->arrRunTime, isset($data['banner_is_run_time'])? $data['banner_is_run_time']: CGlobal::BANNER_NOT_RUN_TIME);
        $optionTypeBanner = FunctionLib::getOption($this->arrTypeBanner, isset($data['banner_type'])? $data['banner_type']: -1);
        $optionPage = FunctionLib::getOption($this->arrPage, isset($data['banner_page'])? $data['banner_page']: -1);
        $optionTarget = FunctionLib::getOption($this->arrTarget, isset($data['banner_is_target'])? $data['banner_is_target']: CGlobal::BANNER_TARGET_BLANK);
        $optionRel = FunctionLib::getOption($this->arrRel, isset($data['banner_is_rel'])? $data['banner_is_rel']: CGlobal::LINK_NOFOLLOW);

        //để hien thi loi
        $data['banner_start_time'] = strtotime($data['banner_start_time']);
        $data['banner_end_time'] = strtotime($data['banner_end_time']);

        $this->layout->content =  View::make('site.ShopVip.EditBanner')
            ->with('id', $banner_id)
            ->with('error', $this->error)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionRunTime', $optionRunTime)
            ->with('optionTypeBanner', $optionTypeBanner)
            ->with('optionTarget', $optionTarget)
            ->with('optionRel', $optionRel)
            ->with('optionPage', $optionPage)
            ->with('arrStatus', $this->arrStatus);
    }
    public function validBanner($data=array()) {
        if(!empty($data)) {
            if(isset($data['banner_name']) && trim($data['banner_name']) == '') {
                $this->error[] = 'Tên banner không được bỏ trống';
            }
            if(isset($data['banner_link']) && trim($data['banner_link']) == '') {
                $this->error[] = 'Chưa có link view cho banner';
            }
            if(isset($data['banner_image']) && trim($data['banner_image']) == '') {
                $this->error[] = 'Chưa up ảnh banner quảng cáo';
            }
            if(isset($data['banner_is_run_time']) && $data['banner_is_run_time'] == 1) {
                if(isset($data['banner_start_time']) && $data['banner_start_time'] == '' ) {
                   $this->error[] = 'Chưa chọn thời gian bắt đầu chạy cho banner';
                }
                if(isset($data['banner_end_time']) && $data['banner_end_time'] == '') {
                    $this->error[] = 'Chưa chọn thời gian kết thúc cho banner';
                }
                if(isset($data['banner_end_time']) && isset($data['banner_start_time'])  && (strtotime($data['banner_start_time']) > strtotime($data['banner_end_time']))) {
                    $this->error[] = 'Thời gian bắt đầu lớn hơn thời gian kết thúc';
                }
            }
        }
        return true;
    }
    //Ajax
    public function deleteBanner(){
        $banner_id = (int)Request::get('banner_id',0);
        $data = array('isIntOk' => 0);
        if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $banner_id > 0){
            $banner = Banner::getBannerShopByID($banner_id,$this->user_shop->shop_id);
            if(sizeof($banner) > 0){
                if(Banner::deleteData($banner_id)){
                    $data['isIntOk'] = 1;
                    return Response::json($data);
                }
            }else{
                return Response::json($data);
            }
        }
        return Response::json($data);
    }

    /**************************************************************************************************************************
     * Quản lý Nhà cung cấp
     **************************************************************************************************************************
     */
    public function listProvider(){
        FunctionLib::link_js(array(
            'js/jquery.min.js',
            'frontend/js/site.js',
        ));

        CGlobal::$pageShopTitle = "QL nhà cung cấp | ".CGlobal::web_name;
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['banner_name'] = addslashes(Request::get('banner_name',''));
        $search['banner_status'] = (int)Request::get('banner_status',-1);
        //$search['field_get'] = 'category_id,news_title,news_status';//cac truong can lay

        $dataSearch = Banner::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['banner_status']);
        $this->layout->content = View::make('site.ShopVip.ListProvider')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus)
            ->with('arrTypeBanner', $this->arrTypeBanner)
            ->with('arrPage', $this->arrPage)
            ->with('arrIsShop', $this->arrIsShop);
    }
    public function getAddProvider($product_id = 0){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'lib/dragsort/jquery.dragsort.js',
            //'js/common.js',
            'lib/number/autoNumeric.js',
            'frontend/js/site.js',
        ));

        CGlobal::$pageShopTitle = "Thêm nhà cung cấp | ".CGlobal::web_name;
        $product = array();
        $arrViewImgOther = array();
        $imagePrimary = $imageHover = '';
        //danh muc san pham cua shop
        $arrCateShop = array();
        if(isset($this->user_shop->shop_category) && $this->user_shop->shop_category !=''){
            $arrCateId = explode(',',$this->user_shop->shop_category);
            $arrCateShop = Category::getCategoryByArrayId($arrCateId);
        }

        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop, -1);

        $optionStatusProduct = FunctionLib::getOption($this->arrStatusProduct,CGlobal::status_hide);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,CGlobal::TYPE_PRICE_NUMBER);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,CGlobal::PRODUCT_NOMAL);

        $this->layout->content = View::make('site.ShopVip.EditProvider')
            ->with('error', $this->error)
            ->with('product_id', $product_id)
            ->with('data', $product)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    public function getEditProvider($product_id = 0){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'lib/dragsort/jquery.dragsort.js',
            //'js/common.js',
            'lib/number/autoNumeric.js',
            'frontend/js/site.js',
        ));

        CGlobal::$pageShopTitle = "Sửa nhà cung cấp | ".CGlobal::web_name;
        $product = array();
        $arrViewImgOther = array();
        $imagePrimary = $imageHover = '';
        if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $product_id > 0){
            $product = Product::getProductByShopId($this->user_shop->shop_id,$product_id);
        }
        if(empty($product)){
            return Redirect::route('shop.listProduct');
        }

        //lấy ảnh show
        if(sizeof($product) > 0){
            //lay ảnh khác của san phẩm
            if(!empty($product->product_image_other)){
                $arrImagOther = unserialize($product->product_image_other);
                if(sizeof($arrImagOther) > 0){
                    foreach($arrImagOther as $k=>$val){
                        $url_thumb = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_100);
                        $arrViewImgOther[] = array('img_other'=>$val,'src_img_other'=>$url_thumb);
                    }
                }
            }
            //ảnh sản phẩm chính
            $imagePrimary = $product->product_image;
            $imageHover = $product->product_image_hover;
        }

        $dataShow = array('product_id'=>$product->product_id,
            'product_name'=>$product->product_name,
            'category_id'=>$product->category_id,
            'provider_id'=>$product->provider_id,
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


        //danh muc san pham cua shop
        $arrCateShop = array();
        if(isset($this->user_shop->shop_category) && $this->user_shop->shop_category !=''){
            $arrCateId = explode(',',$this->user_shop->shop_category);
            $arrCateShop = Category::getCategoryByArrayId($arrCateId);
        }

        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop,isset($product->category_id)? $product->category_id: -1);
        $optionStatusProduct = FunctionLib::getOption($this->arrStatusProduct,isset($product->product_status)? $product->product_status:CGlobal::status_hide);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,isset($product->product_type_price)? $product->product_type_price:CGlobal::TYPE_PRICE_NUMBER);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,isset($product->product_is_hot)? $product->product_is_hot:CGlobal::PRODUCT_NOMAL);

        $this->layout->content = View::make('site.ShopVip.EditProvider')
            ->with('error', $this->error)
            ->with('product_id', $product_id)
            ->with('data', $dataShow)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    public function postEditProvider($product_id = 0){
        //Include style.
        FunctionLib::link_css(array(
            'lib/upload/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/upload/jquery.uploadfile.js',
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'lib/dragsort/jquery.dragsort.js',
            //'js/common.js',
            'lib/number/autoNumeric.js',
            'frontend/js/site.js',
        ));

        CGlobal::$pageShopTitle = "Sửa nhà cung cấp | ".CGlobal::web_name;
        $product = array();
        $arrViewImgOther = array();
        $imagePrimary = $imageHover = '';

        $dataSave['product_name'] = addslashes(Request::get('product_name'));
        $dataSave['category_id'] = addslashes(Request::get('category_id'));
        $dataSave['product_selloff'] = addslashes(Request::get('product_selloff'));
        $dataSave['product_is_hot'] = addslashes(Request::get('product_is_hot'));
        $dataSave['product_status'] = addslashes(Request::get('product_status'));
        $dataSave['product_type_price'] = addslashes(Request::get('product_type_price'));
        $dataSave['product_sort_desc'] = addslashes(Request::get('product_sort_desc'));
        $dataSave['product_content'] = addslashes(Request::get('product_content'));
        $dataSave['product_order'] = addslashes(Request::get('product_order'));
        $dataSave['quality_input'] = addslashes(Request::get('quality_input'));

        $dataSave['product_price_sell'] = (int)str_replace('.','',Request::get('product_price_sell'));
        $dataSave['product_price_market'] = (int)str_replace('.','',Request::get('product_price_market'));
        $dataSave['product_price_input'] = (int)str_replace('.','',Request::get('product_price_input'));

        $dataSave['product_image'] = $imagePrimary = addslashes(Request::get('image_primary'));
        $dataSave['product_image_hover'] = $imageHover = addslashes(Request::get('product_image_hover'));

        //check lại xem SP co phai cua Shop nay ko
        $id_hiden = Request::get('id_hiden',0);
        $product_id = ($product_id >0)? $product_id: $id_hiden;

        //danh muc san pham cua shop
        $arrCateShop = array();
        if(isset($this->user_shop->shop_category) && $this->user_shop->shop_category !=''){
            $arrCateId = explode(',',$this->user_shop->shop_category);
            $arrCateShop = Category::getCategoryByArrayId($arrCateId);
        }

        //lay lai vi tri sap xep cua anh khac
        $arrInputImgOther = array();
        $getImgOther = Request::get('img_other',array());
        if(!empty($getImgOther)){
            foreach($getImgOther as $k=>$val){
                if($val !=''){
                    $arrInputImgOther[] = $val;

                    //show ra anh da Upload neu co loi
                    $url_thumb = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_100);
                    $arrViewImgOther[] = array('img_other'=>$val,'src_img_other'=>$url_thumb);
                }
            }
        }
        if (!empty($arrInputImgOther) && count($arrInputImgOther) > 0) {
            //neu ko co anh chinh, lay anh chinh la cai anh dau tien
            if($dataSave['product_image'] == ''){
                $dataSave['product_image'] = $arrInputImgOther[0];
            }
            //neu ko co anh hove, lay anh hove la cai anh dau tien
            if($dataSave['product_image_hover'] == ''){
                $dataSave['product_image_hover'] = (isset($arrInputImgOther[1]))?$arrInputImgOther[1]:$arrInputImgOther[0];
            }
            $dataSave['product_image_other'] = serialize($arrInputImgOther);
        }

        //FunctionLib::debug($dataSave);
        $this->validInforProduct($dataSave);
        if(empty($this->error)){
            if($product_id > 0){
                if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $product_id > 0){
                    $product = Product::getProductByShopId($this->user_shop->shop_id, $product_id);
                }
                if(!empty($product)){
                    if($product_id > 0){//cap nhat
                        if($id_hiden == 0){
                            $dataSave['time_created'] = time();
                            $dataSave['time_update'] = time();
                        }else{
                            $dataSave['time_update'] = time();
                        }
                        //lay tên danh mục
                        $dataSave['category_name'] = isset($arrCateShop[$dataSave['category_id']])?$arrCateShop[$dataSave['category_id']]: '';
                        $dataSave['user_shop_id'] = $this->user_shop->shop_id;
                        $dataSave['user_shop_name'] = $this->user_shop->user_shop_name;
                        $dataSave['is_shop'] = $this->user_shop->is_shop;
                        $dataSave['shop_province'] = $this->user_shop->shop_province;

                        if(Product::updateData($product_id,$dataSave)){
                            return Redirect::route('shop.listProduct');
                        }
                    }
                }else{
                    return Redirect::route('shop.listProduct');
                }
            }
            else{
                return Redirect::route('shop.listProduct');
            }
        }
        //FunctionLib::debug($dataSave);

        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop,$dataSave['category_id']);
        $optionStatusProduct = FunctionLib::getOption($this->arrStatusProduct,$dataSave['product_status']);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,$dataSave['product_type_price']);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,$dataSave['product_is_hot']);

        $this->layout->content = View::make('site.ShopVip.EditProvider')
            ->with('error', $this->error)
            ->with('product_id', $product_id)
            ->with('data', $dataSave)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    private function validProvider($data=array()) {
        if(!empty($data)) {
            if(isset($data['product_name']) && trim($data['product_name']) == '') {
                $this->error[] = 'Tên sản phẩm không được bỏ trống';
            }
            if(isset($data['product_image']) && trim($data['product_image']) == '') {
                $this->error[] = 'Chưa up ảnh sản phẩm';
            }
            if(isset($data['category_id']) && $data['category_id'] == -1) {
                $this->error[] = 'Chưa chọn danh mục';
            }
            if(isset($data['product_type_price']) && $data['product_type_price'] == CGlobal::TYPE_PRICE_NUMBER) {
                if(isset($data['product_price_sell']) && $data['product_price_sell'] <= 0) {
                    $this->error[] = 'Chưa nhập giá bán';
                }
            }
            return true;
        }
        return false;
    }
    //Ajax
    public function deleteProvider(){
        $product_id = (int)Request::get('product_id',0);
        $data = array('isIntOk' => 0);
        if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $product_id > 0){
            $product = Product::getProductByShopId($this->user_shop->shop_id, $product_id);
            if(sizeof($product) > 0){
                if(Product::deleteData($product_id)){
                    $data['isIntOk'] = 1;
                    return Response::json($data);
                }
            }else{
                return Response::json($data);
            }
        }
        return Response::json($data);
    }
}

