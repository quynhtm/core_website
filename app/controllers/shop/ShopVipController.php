<?php

class ShopVipController extends BaseShopController
{
    private $arrStatus = array(-1 => '--Chọn trạng thái--', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrTarget = array(-1 => '--Chọn target link--', CGlobal::BANNER_NOT_TARGET_BLANK => 'Link trên site', CGlobal::BANNER_TARGET_BLANK => 'Mở tab mới');
    private $arrRunTime = array(-1 => '--Chọn thời gian chạy--', CGlobal::BANNER_NOT_RUN_TIME => 'Chạy mãi mãi', CGlobal::BANNER_IS_RUN_TIME => 'Chạy theo thời gian');
    private $arrIsShop = array(-1 => '--Tất cả--', CGlobal::BANNER_NOT_SHOP => 'Banner của site', CGlobal::BANNER_IS_SHOP => 'Banner của shop');
    private $arrRel = array(CGlobal::LINK_NOFOLLOW => 'Nofollow', CGlobal::LINK_FOLLOW => 'Follow');
    private $arrTypeBanner = array(-1 => '---Chọn loại Banner--',
        CGlobal::BANNER_TYPE_HOME_BIG => 'Banner home slider to',
        CGlobal::BANNER_TYPE_HOME_RIGHT_1 => 'Banner home phải slider 1',
        CGlobal::BANNER_TYPE_HOME_RIGHT_2 => 'Banner home phải slider 2',
        CGlobal::BANNER_TYPE_HOME_SMALL => 'Banner home nhỏ',
        CGlobal::BANNER_TYPE_HOME_LEFT => 'Banner trái-phải',
        CGlobal::BANNER_TYPE_HOME_LIST => 'Banner trang list');

    private $arrPage = array(-1 => '--Chọn page--',
        CGlobal::BANNER_PAGE_HOME => 'Page trang chủ',
        CGlobal::BANNER_PAGE_LIST => 'Page danh sách',
        CGlobal::BANNER_PAGE_DETAIL=> 'Page chi tiết',
        CGlobal::BANNER_PAGE_CATEGORY => 'Page danh mục');

    private $error = array();
    public function __construct()
    {
        parent::__construct();
        //check khong phai shop VIP
        if(isset($this->user_shop->is_shop) && $this->user_shop->is_shop != CGlobal::SHOP_VIP){
            Redirect::route('shop.adminShop',array('error'=>1))->send();
        }
    }

    /**************************************************************************************************************************
     * Quản lý Banner shop
     **************************************************************************************************************************
     */
    public function listBanner(){
        FunctionLib::link_js(array(
            'js/jquery.min.js',
            'frontend/js/site.js',
        ));

        CGlobal::$pageShopTitle = "QL banner quảng cáo | ".CGlobal::web_name;
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
    public function getAddBanner($product_id = 0){
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

        CGlobal::$pageShopTitle = "Thêm quảng cáo | ".CGlobal::web_name;
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

        $this->layout->content = View::make('site.ShopVip.EditBanner')
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
    public function getEditBanner($product_id = 0){
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

        CGlobal::$pageShopTitle = "Sửa sản phẩm | ".CGlobal::web_name;
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

        $this->layout->content = View::make('site.ShopVip.EditBanner')
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
    public function postEditBanner($product_id = 0){
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

        CGlobal::$pageShopTitle = "Sửa sản phẩm | ".CGlobal::web_name;
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

        $this->layout->content = View::make('site.ShopVip.EditBanner')
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
    private function validBanner($data=array()) {
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
    public function deleteBanner(){
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
    public function removeImageBanner(){
        $item_id = Request::get('id',0);
        $name_img = Request::get('nameImage','');
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Error";
        $aryData['nameImage'] = $name_img;
        if($item_id > 0 && $name_img != ''){
            //get mang anh other
            $shop_id = $this->user_shop->shop_id;
            $inforPro = Product::getProductByShopId($shop_id,$item_id);
            if($inforPro) {
                $arrImagOther = unserialize($inforPro->product_image_other);
                foreach($arrImagOther as $ki => $img){
                    if(strcmp($img,$name_img) == 0){
                        unset($arrImagOther[$ki]);
                        break;
                    }
                }
                $proUpdate['product_image_other'] = serialize($arrImagOther);
                Product::updateData($item_id,$proUpdate);
            }
            //anh upload
            FunctionLib::deleteFileUpload($name_img,$item_id,CGlobal::FOLDER_PRODUCT);
            //xoa anh thumb
            $arrSizeThumb = CGlobal::$arrSizeImage;
            foreach($arrSizeThumb as $k=>$size){
                $sizeThumb = $size['w'].'x'.$size['h'];
                FunctionLib::deleteFileThumb($name_img,$item_id,CGlobal::FOLDER_PRODUCT,$sizeThumb);
            }
            $aryData['intIsOK'] = 1;
        }
        return Response::json($aryData);
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

        CGlobal::$pageShopTitle = "QL banner quảng cáo | ".CGlobal::web_name;
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

        CGlobal::$pageShopTitle = "Thêm quảng cáo | ".CGlobal::web_name;
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

        CGlobal::$pageShopTitle = "Sửa sản phẩm | ".CGlobal::web_name;
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

        CGlobal::$pageShopTitle = "Sửa sản phẩm | ".CGlobal::web_name;
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

