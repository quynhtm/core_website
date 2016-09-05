<?php

class ShopController extends BaseShopController
{
    private $arrStatusProduct = array(-1 => '---- Trạng thái sản phẩm----',CGlobal::status_show => 'Hiển thị',CGlobal::status_hide => 'Ẩn');
    private $arrTypePrice = array(CGlobal::TYPE_PRICE_NUMBER => 'Hiển thị giá bán', CGlobal::TYPE_PRICE_CONTACT => 'Liên hệ với shop');
    private $arrTypeProduct = array(-1 => '--Chọn loại sản phẩm--', CGlobal::PRODUCT_NOMAL => 'Sản phẩm bình thường', CGlobal::PRODUCT_HOT => 'Sản phẩm nổi bật', CGlobal::PRODUCT_SELLOFF => 'Sản phẩm giảm giá');
    private $error = array();
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
        FunctionLib::link_js(array(
            'js/jquery.min.js',
            'frontend/js/site.js',
        ));

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

        $optionStatus = FunctionLib::getOption($this->arrStatusProduct, $search['product_status']);
        $this->layout->content = View::make('site.ShopLayouts.ListProduct')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
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
            'js/common.js',
            'lib/number/autoNumeric.js',
        ));

        CGlobal::$pageShopTitle = "Thêm sản phẩm | ".CGlobal::web_name;
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

        $this->layout->content = View::make('site.ShopLayouts.EditProduct')
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
            'js/common.js',
            'lib/number/autoNumeric.js',
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

        $this->layout->content = View::make('site.ShopLayouts.EditProduct')
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
            'js/common.js',
            'lib/number/autoNumeric.js',
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

        $this->layout->content = View::make('site.ShopLayouts.EditProduct')
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
                $data['shop_province'] = $item->shop_province;
                $data['is_shop'] = $item->is_shop;
                $data['shop_status'] = $item->shop_status;
            }
        }
        $arrCategory = Category::buildTreeCategory();
        $arrCateShop = isset($data['shop_category'])? explode(',',$data['shop_category']): array();
        //tỉnh thành
        $arrProvince = Province::getAllProvince();
        $optionProvince = FunctionLib::getOption(array(-1=>' ---Chọn tỉnh thành ----')+$arrProvince, isset($data['shop_province'])?$data['shop_province']:-1);

        $this->layout->content = View::make('site.ShopLayouts.EditUserShop')
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

        $this->layout->content =  View::make('site.ShopLayouts.EditUserShop')
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

