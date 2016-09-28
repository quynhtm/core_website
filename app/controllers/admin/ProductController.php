<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class ProductController extends BaseAdminController
{
    private $permission_view = 'product_view';
    private $permission_full = 'product_full';
    private $permission_delete = 'product_delete';
    private $permission_create = 'product_create';
    private $permission_edit = 'product_edit';
    private $arrStatusUpdate = array(-1 => 'Trạng thái chuyển đổi',
        CGlobal::status_hide => 'Ẩn',
        CGlobal::status_show => 'Hiện',
        2 => 'Khóa SP',
        3 => 'Mở khóa SP',
        4 => 'Set top SP',
        //product_is_hot: loại sản phẩm
        5 => 'Sản phẩm bình thường',
        6 => 'Sản phẩm nổi bật',
        7 => 'Sản phẩm giảm giá',
        );
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrBlock = array(-1 => 'Chọn kiểu khóa SP', CGlobal::PRODUCT_NOT_BLOCK => 'Đang mở', CGlobal::PRODUCT_BLOCK => 'Đang khóa');
    private $arrTypePrice = array(CGlobal::TYPE_PRICE_NUMBER => 'Hiển thị giá bán', CGlobal::TYPE_PRICE_CONTACT => 'Liên hệ với shop');
    private $arrTypeProduct = array(-1 => '--Chọn loại sản phẩm--', CGlobal::PRODUCT_NOMAL => 'Sản phẩm bình thường', CGlobal::PRODUCT_HOT => 'Sản phẩm nổi bật', CGlobal::PRODUCT_SELLOFF => 'Sản phẩm giảm giá');
    private $error =  array();
    private $arrShop =  array();
    public function __construct()
    {
        parent::__construct();
        $this->arrShop = UserShop::getShopAll();
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
            'lib/dragsort/jquery.dragsort.js',
            'lib/number/autoNumeric.js',
            //'frontend/js/site.js',
        ));
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        CGlobal::$pageShopTitle = "Quản lý sản phẩm | ".CGlobal::web_name;
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['product_name'] = addslashes(Request::get('product_name',''));
        $search['product_status'] = (int)Request::get('product_status',-1);
        $search['product_is_hot'] = (int)Request::get('product_is_hot',-1);
        $search['category_id'] = (int)Request::get('category_id',-1);
        $search['user_shop_id'] = (int)Request::get('user_shop_id',-1);
        $search['is_block'] = (int)Request::get('is_block',-1);
        //$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $dataSearch = Product::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        //FunctionLib::debug($search);

        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['product_status']);
        $optionType = FunctionLib::getOption($this->arrTypeProduct, $search['product_is_hot']);
        $optionBlock = FunctionLib::getOption($this->arrBlock, $search['is_block']);
        $optionStatusUpdate = FunctionLib::getOption($this->arrStatusUpdate, -1);
        $this->layout->content = View::make('admin.Product.view')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('arrShop', $this->arrShop)
            ->with('arrTypeProduct', $this->arrTypeProduct)
            ->with('optionStatus', $optionStatus)
            ->with('optionType', $optionType)
            ->with('optionBlock', $optionBlock)

            ->with('optionStatusUpdate', $optionStatusUpdate)
            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function getProduct($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
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

        $product = array();
        $arrViewImgOther = array();
        $imagePrimary = $imageHover = '';
        $product = Product::getProductByID($id);
        if(empty($product)){
            return Redirect::route('admin.product_list');
        }

        //lấy ảnh show
        if(sizeof($product) > 0){
            //lay ảnh khác của san phẩm
            if(!empty($product->product_image_other)){
                $arrImagOther = unserialize($product->product_image_other);
                if(sizeof($arrImagOther) > 0){
                    foreach($arrImagOther as $k=>$val){
                        $url_thumb = ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $id, $val, CGlobal::sizeImage_100);
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
        $arrCategory = array();
        $arrCategoryAll = Category::buildTreeCategory();
        foreach($arrCategoryAll as $k =>$cat){
            $arrCategory[$cat['category_id']] = $cat['padding_left'].$cat['category_name'];
        }
        //FunctionLib::debug($arrCategoryAll);

        $optionCategory = FunctionLib::getOption(array(-1=>'---Chọn danh mục----') + $arrCategory,isset($product->category_id)? $product->category_id: -1);
        $optionStatusProduct = FunctionLib::getOption($this->arrStatus,isset($product->product_status)? $product->product_status:CGlobal::status_hide);
        $optionTypePrice = FunctionLib::getOption($this->arrTypePrice,isset($product->product_type_price)? $product->product_type_price:CGlobal::TYPE_PRICE_NUMBER);
        $optionTypeProduct = FunctionLib::getOption($this->arrTypeProduct,isset($product->product_is_hot)? $product->product_is_hot:CGlobal::PRODUCT_NOMAL);

        $this->layout->content = View::make('admin.Product.add')
            ->with('error', $this->error)
            ->with('id', $id)
            ->with('data', $dataShow)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('imagePrimary', $imagePrimary)
            ->with('imageHover', $imageHover)
            ->with('optionCategory', $optionCategory)
            ->with('optionStatusProduct', $optionStatusProduct)
            ->with('optionTypePrice', $optionTypePrice)
            ->with('optionTypeProduct', $optionTypeProduct);
    }
    public function postProduct($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        die('Không có chức năng này trong admin');
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
        $dataSave['category_name'] = addslashes(Request::get('category_name'));
        $dataSave['category_icons'] = addslashes(Request::get('category_icons'));
        $dataSave['category_image_background'] = addslashes(Request::get('category_image_background'));
        $dataSave['category_status'] = (int)Request::get('category_status', 0);
        $dataSave['category_parent_id'] = (int)Request::get('category_parent_id', 0);
        $dataSave['category_content_front'] = (int)Request::get('category_content_front', 0);
        $dataSave['category_content_front_order'] = (int)Request::get('category_content_front_order', 0);
        $dataSave['category_order'] = (int)Request::get('category_order', 0);

        $file = Input::file('image');
        if($file){
            $destinationPath = public_path().'/images/category/';
            $filename = $file->getClientOriginalName();
            $upload  = Input::file('image')->move($destinationPath, $filename);
            //FunctionLib::debug($filename);
            $dataSave['category_image_background'] = $filename;
        }else{
            $dataSave['category_image_background'] = Request::get('category_image_background', '');
        }

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(Product::updateData($id, $dataSave)) {
                    return Redirect::route('admin.category_list');
                }
            } else {
                //them moi
                if(Product::addData($dataSave)) {
                    return Redirect::route('admin.category_list');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['category_status'])? $dataSave['category_status'] : -1);
        $this->layout->content =  View::make('admin.Product.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('error', $this->error)
            ->with('arrStatus', $this->arrStatus);
    }

    //ajax
    public function deleteMultiProduct(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $dataId = Request::get('dataId',array());
        $arrData['isIntOk'] = 0;
        if(empty($dataId)) {
            return Response::json($data);
        }
        if(sizeof($dataId) > 0){
            foreach($dataId as $k =>$id){
                if ($id > 0 && Product::deleteData($id)) {
                    $data['isIntOk'] = 1;
                }
            }
        }
        return Response::json($data);
    }
    public function setStastusBlockProduct(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $valueInput = (int)Request::get('valueInput',-1);
        $dataId = Request::get('dataId',array());
        $arrData['isIntOk'] = 0;
        if(empty($dataId)) {
            return Response::json($data);
        }
        if(sizeof($dataId) > 0 && $valueInput > -1){
            $arrUpdate = array();
            switch( $valueInput ) {
                case 0://ẩn sản phẩm
                case 1://hiển thị sản phẩm
                    $arrUpdate['product_status'] = $valueInput;
                    break;
                case 2://Khóa sản phẩm
                case 3://Mở khóa sản phẩm
                    $arrUpdate['is_block'] = ($valueInput == 2)? CGlobal::PRODUCT_BLOCK : CGlobal::PRODUCT_NOT_BLOCK;
                    break;
                case 4://Set top san phẩm
                    $arrUpdate['time_update'] = time();
                    break;
                /**
                 * product_is_hot
                 *  5 => 'Sản phẩm bình thường',
                    6 => 'Sản phẩm nổi bật',
                    7 => 'Sản phẩm giảm giá',
                 */
                case 5:
                case 6:
                case 7:
                    $product_is_hot = CGlobal::PRODUCT_NOMAL;
                    if($valueInput == 6){
                        $product_is_hot = CGlobal::PRODUCT_HOT;
                    }elseif($valueInput == 7){
                        $product_is_hot = CGlobal::PRODUCT_SELLOFF;
                    }
                    $arrUpdate['product_is_hot'] = $product_is_hot;
                    break;
                default:
                    break;
            }
            if(sizeof($arrUpdate) > 0){
                foreach($dataId as $k =>$id){
                    if ($id > 0 && Product::updateData($id,$arrUpdate)) {
                        $data['isIntOk'] = 1;
                    }
                }
            }
        }
        return Response::json($data);
    }

    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['category_name']) && $data['category_name'] == '') {
                $this->error[] = 'Tên danh mục không được trống';
            }
            if(isset($data['category_status']) && $data['category_status'] == -1) {
                $this->error[] = 'Bạn chưa chọn trạng thái cho danh mục';
            }
            return true;
        }
        return false;
    }

}