<?php

class ShopController extends BaseShopController
{
    private $arrStatusProduct = array(-1 => '---- Trạng thái sản phẩm----',CGlobal::status_show => 'Hiển thị',CGlobal::status_hide => 'Ẩn');
    private $arrTypePrice = array(CGlobal::TYPE_PRICE_NUMBER => 'Hiển thị giá bán', CGlobal::TYPE_PRICE_CONTACT => 'Liên hệ với shop');
    private $arrTypeProduct = array(-1 => '--Chọn loại sản phẩm--', CGlobal::PRODUCT_NOMAL => 'Sản phẩm bình thường', CGlobal::PRODUCT_HOT => 'Sản phẩm nổi bật', CGlobal::PRODUCT_SELLOFF => 'Sản phẩm giảm giá');
    private $arrIsSale = array(CGlobal::PRODUCT_IS_SALE => 'Còn hàng', CGlobal::PRODUCT_NOT_IS_SALE => 'Hết hàng');
    private $error = array();
    public function __construct()
    {
        parent::__construct();
    }
    /*
     * Trang shopAdmin
     */
    public function shopAdmin(){
        CGlobal::$pageShopTitle = "Quản trị Shop | ".CGlobal::web_name;
        $error = Request::get('error',0);
        if($error == 1){
            $this->error[] = 'Shop Vip mới có chức năng này.';
        }
        if(isset($this->user_shop->shop_status) && ($this->user_shop->shop_status == CGlobal::status_hide || $this->user_shop->shop_status == CGlobal::status_block)){
            return Redirect::route('site.shopLogout');
        }
        $urlShopShare = URL::route('shop.home',array('shop_id'=>$this->user_shop->shop_id,
            'shop_name'=>FunctionLib::safe_title($this->user_shop->shop_name),
            'shop_share'=>base64_encode(CGlobal::code_shop_share.'_'.$this->user_shop->shop_id.'_'.CGlobal::code_shop_share)));
        //echo $urlShopShare; die;
        $this->layout->content = View::make('site.ShopAdmin.ShopHome')
            ->with('error',$this->error)
            ->with('urlShopShare',$urlShopShare)
            ->with('user', $this->user_shop);
    }

    /**************************************************************************************************************************
     * Quản lý sản phẩm shop
     **************************************************************************************************************************
     */
    public function shopListProduct(){
        FunctionLib::link_js(array(
            'js/jquery.min.js',
            'frontend/js/site.js',
        ));
        //check shop con lươt up hay không
        $number_limit_product = $this->user_shop->number_limit_product;//lượt up
        $shop_up_product = $this->user_shop->shop_up_product;// total da up
        $checkAddProduct = 1;
        if($shop_up_product >= $number_limit_product){
            $checkAddProduct = 0;//het lượt up
            $this->error[] = 'Shop của bạn đã hết lượt up sản phẩm.';
            $this->error[] = 'Hãy chia sẻ, giới thiệu link sau để được thêm lượt up:';
            $this->error[] = $urlShopShare = URL::route('shop.home',array('shop_id'=>$this->user_shop->shop_id,
                'shop_name'=>FunctionLib::safe_title($this->user_shop->shop_name),
                'shop_share'=>base64_encode(CGlobal::code_shop_share.'_'.$this->user_shop->shop_id.'_'.CGlobal::code_shop_share)));
        }

        CGlobal::$pageShopTitle = "Quản lý sản phẩm | ".CGlobal::web_name;
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['product_name'] = addslashes(Request::get('product_name',''));
        $search['product_status'] = (int)Request::get('product_status',-1);
        $search['category_id'] = (int)Request::get('category_id',-1);
        $search['provider_id'] = (int)Request::get('provider_id',-1);
        $search['user_shop_id'] = (isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0)?(int)$this->user_shop->shop_id: 0;//tìm theo shop
        //$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $dataSearch = (isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0) ? Product::searchByCondition($search, $limit, $offset,$total): array();
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        //FunctionLib::debug($search);
        //danh muc san pham cua shop
        $arrCateShop = UserShop::getCategoryShopById($this->user_shop->shop_id);
        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop, $search['category_id']);
        //danh sach NCC cua shop
        $arrNCC = ($this->user_shop->is_shop == CGlobal::SHOP_VIP)? Provider::getListProviderByShopId($this->user_shop->shop_id): array();
        $optionNCC = FunctionLib::getOption(array(-1=>'---Chọn nhà cung cấp ----') + $arrNCC, $search['provider_id']);

        $optionStatus = FunctionLib::getOption($this->arrStatusProduct, $search['product_status']);
        $this->layout->content = View::make('site.ShopAdmin.ListProduct')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('checkAddProduct', $checkAddProduct)
            ->with('error', $this->error)
            ->with('optionStatus', $optionStatus)
            ->with('optionNCC', $optionNCC)
            ->with('arrNCC', $arrNCC)
            ->with('arrIsSale', $this->arrIsSale)
            ->with('optionCategory', $optionCategory)
            ->with('user', $this->user_shop);
    }
    public function getAddProduct($product_id = 0){
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

        //check shop con lươt up hay không
        $number_limit_product = $this->user_shop->number_limit_product;//lượt up
        $shop_up_product = $this->user_shop->shop_up_product;// total da up
        $checkAddProduct = 1;
        if($shop_up_product >= $number_limit_product){
            return Redirect::route('shop.listProduct');
        }
        CGlobal::$pageShopTitle = "Thêm sản phẩm | ".CGlobal::web_name;
        $product = array();
        $arrViewImgOther = array();
        $imagePrimary = $imageHover = '';

        //danh muc san pham cua shop
        $arrCateShop = UserShop::getCategoryShopById($this->user_shop->shop_id);
        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop, -1);

        //danh sach NCC cua shop
        $arrNCC = ($this->user_shop->is_shop == CGlobal::SHOP_VIP)? Provider::getListProviderByShopId($this->user_shop->shop_id): array();
        //FunctionLib::debug($arrNCC);
        $optionNCC = FunctionLib::getOption(array(-1=>'---Chọn nhà cung cấp ----') + $arrNCC, -1);

        $optionStatusProduct = FunctionLib::getOption($this->arrStatusProduct,CGlobal::status_hide);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,CGlobal::TYPE_PRICE_NUMBER);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,CGlobal::PRODUCT_NOMAL);
        $optionIsSale = FunctionLib::getOption($this->arrIsSale,CGlobal::PRODUCT_IS_SALE);

        $this->layout->content = View::make('site.ShopAdmin.EditProduct')
            ->with('error', $this->error)
            ->with('product_id', $product_id)
            ->with('user_shop', $this->user_shop)
            ->with('data', $product)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionNCC', $optionNCC)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionIsSale', $optionIsSale)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    public function getEditProduct($product_id = 0){
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
                        $url_thumb_content = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_600);
                        $arrViewImgOther[] = array('img_other'=>$val,'src_img_other'=>$url_thumb,'src_thumb_content'=>$url_thumb_content);
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
            'is_sale'=>$product->is_sale,
            'product_code'=>$product->product_code,
            'product_sort_desc'=>$product->product_sort_desc,
            'product_content'=>$product->product_content,
            'product_image'=>$product->product_image,
            'product_image_hover'=>$product->product_image_hover,
            'product_image_other'=>$product->product_image_other,
            'product_order'=>$product->product_order,
            'quality_input'=>$product->quality_input,
            'product_status'=>$product->product_status);


        //danh muc san pham cua shop
        $arrCateShop = UserShop::getCategoryShopById($this->user_shop->shop_id);
        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop,isset($product->category_id)? $product->category_id: -1);

        //danh sach NCC cua shop
        $arrNCC = ($this->user_shop->is_shop == CGlobal::SHOP_VIP)?Provider::getListProviderByShopId($this->user_shop->shop_id): array();
        $optionNCC = FunctionLib::getOption(array(-1=>'---Chọn nhà cung cấp ----') + $arrNCC, isset($product->provider_id)? $product->provider_id:-1);

        $optionStatusProduct = FunctionLib::getOption($this->arrStatusProduct,isset($product->product_status)? $product->product_status:CGlobal::status_hide);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,isset($product->product_type_price)? $product->product_type_price:CGlobal::TYPE_PRICE_NUMBER);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,isset($product->product_is_hot)? $product->product_is_hot:CGlobal::PRODUCT_NOMAL);
        $optionIsSale = FunctionLib::getOption($this->arrIsSale,isset($product->is_sale)? $product->is_sale:CGlobal::PRODUCT_IS_SALE);

        $this->layout->content = View::make('site.ShopAdmin.EditProduct')
            ->with('error', $this->error)
            ->with('product_id', $product_id)
            ->with('user_shop', $this->user_shop)
            ->with('data', $dataShow)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionNCC', $optionNCC)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionIsSale', $optionIsSale)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    public function postEditProduct($product_id = 0){
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

        $dataSave['product_type_price'] = addslashes(Request::get('product_type_price',CGlobal::TYPE_PRICE_NUMBER));
        $dataSave['is_sale'] = addslashes(Request::get('is_sale',CGlobal::PRODUCT_IS_SALE));
        $dataSave['product_code'] = addslashes(Request::get('product_code'));

        $dataSave['product_sort_desc'] = addslashes(Request::get('product_sort_desc'));
        $dataSave['product_content'] = Request::get('product_content');
        $dataSave['product_order'] = addslashes(Request::get('product_order'));
        $dataSave['quality_input'] = addslashes(Request::get('quality_input'));
        $dataSave['provider_id'] = addslashes(Request::get('provider_id'));

        $dataSave['product_price_sell'] = (int)str_replace('.','',Request::get('product_price_sell'));
        $dataSave['product_price_market'] = (int)str_replace('.','',Request::get('product_price_market'));
        $dataSave['product_price_input'] = (int)str_replace('.','',Request::get('product_price_input'));

        $dataSave['product_image'] = $imagePrimary = addslashes(Request::get('image_primary'));
        $dataSave['product_image_hover'] = $imageHover = addslashes(Request::get('product_image_hover'));

        //check lại xem SP co phai cua Shop nay ko
        $id_hiden = Request::get('id_hiden',0);
        $product_id = ($product_id >0)? $product_id: $id_hiden;

        //danh muc san pham cua shop
        $arrCateShop = UserShop::getCategoryShopById($this->user_shop->shop_id);

        //danh sach NCC cua shop
        $arrNCC = ($this->user_shop->is_shop == CGlobal::SHOP_VIP)?Provider::getListProviderByShopId($this->user_shop->shop_id): array();

        //lay lai vi tri sap xep cua anh khac
        $arrInputImgOther = array();
        $getImgOther = Request::get('img_other',array());
        if(!empty($getImgOther)){
            foreach($getImgOther as $k=>$val){
                if($val !=''){
                    $arrInputImgOther[] = $val;

                    //show ra anh da Upload neu co loi
                    $url_thumb = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_100);
                    $url_thumb_content = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_600);
                    $arrViewImgOther[] = array('img_other'=>$val,'src_img_other'=>$url_thumb,'src_thumb_content'=>$url_thumb_content);
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
                        $dataSave['user_shop_name'] = $this->user_shop->shop_name;
                        $dataSave['is_shop'] = $this->user_shop->is_shop;
                        $dataSave['shop_province'] = $this->user_shop->shop_province;
                        $dataSave['is_block'] = CGlobal::PRODUCT_NOT_BLOCK;

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
        $optionNCC = FunctionLib::getOption(array(-1=>'---Chọn nhà cung cấp ----') + $arrNCC, $dataSave['provider_id']);
        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCateShop,$dataSave['category_id']);
        $optionStatusProduct = FunctionLib::getOption($this->arrStatusProduct,$dataSave['product_status']);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,$dataSave['product_type_price']);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,$dataSave['product_is_hot']);
        $optionIsSale = FunctionLib::getOption($this->arrIsSale,$dataSave['is_sale']);

        $this->layout->content = View::make('site.ShopAdmin.EditProduct')
            ->with('error', $this->error)
            ->with('product_id', $product_id)
            ->with('user_shop', $this->user_shop)
            ->with('data', $dataSave)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionNCC', $optionNCC)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionIsSale', $optionIsSale)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    private function validInforProduct($data=array()) {
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
    public function setOnTopProduct(){
        $is_shop = (int)Request::get('is_shop',1);
        $product_id = (int)Request::get('product_id',0);
        $data = array('isIntOk' => 0);
        if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $product_id > 0 && $is_shop == CGlobal::SHOP_VIP){
            $product = Product::getProductByShopId($this->user_shop->shop_id, $product_id);
            if(sizeof($product) > 0){
                $dataSave['time_update'] = time();
                if(Product::updateData($product_id,$dataSave)){
                    $data['isIntOk'] = 1;
                    return Response::json($data);
                }
            }else{
                return Response::json($data);
            }
        }
        return Response::json($data);
    }
    public function getImageProductOther(){
        $product_id = (int)Request::get('product_id',0);
        $data = array('isIntOk' => 0);
        if(isset($this->user_shop->shop_id) && $this->user_shop->shop_id > 0 && $product_id > 0){
            $product = Product::getProductByShopId($this->user_shop->shop_id, $product_id);
            if(sizeof($product) > 0){
                if($product->product_image_other != ''){
                    $arrViewImgOther = array();
                    $arrImagOther = unserialize($product->product_image_other);
                    if(sizeof($arrImagOther) > 0){
                        foreach($arrImagOther as $k=>$val){
                            $url_thumb = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_100);
                            $url_thumb_content = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_id, $val, CGlobal::sizeImage_600);
                            $arrViewImgOther[] = array('product_name'=>$product->product_name,
                                'src_img_other'=>$url_thumb,
                                'src_thumb_content'=>$url_thumb_content);
                        }
                    }
                    $data['dataImage'] = $arrViewImgOther;
                    $data['isIntOk'] = 1;
                    return Response::json($data);
                }
            }else{
                return Response::json($data);
            }
        }
        return Response::json($data);
    }
    public function deleteProduct(){
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
    public function removeImage(){
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

        $this->layout->content = View::make('site.ShopAdmin.ListOrder')
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

