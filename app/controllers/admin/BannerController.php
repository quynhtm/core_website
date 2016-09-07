<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class BannerController extends BaseAdminController
{
    private $permission_view = 'banner_view';
    private $permission_full = 'banner_full';
    private $permission_delete = 'banner_delete';
    private $permission_create = 'banner_create';
    private $permission_edit = 'banner_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $error = array();
    private $arrCategoryNew = array();
    private $arrTypeNew = array();

    public function __construct()
    {
        parent::__construct();

        $this->arrCategoryNew = CGlobal::$arrCategoryNew;
        $this->arrTypeNew = CGlobal::$arrTypeNew;

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
        ));
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard');
        }
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

        if(!empty($dataSearch)){
            foreach($dataSearch as $k=> $val){
                $url_image = ($val->banner_image != '')?ThumbImg::getImageThumb(CGlobal::FOLDER_BANNER, $val->banner_id, $val->banner_image, CGlobal::sizeImage_100):'';
                $data[] = array('banner_id'=>$val->banner_id,
                    'banner_name'=>$val->banner_name,
                    'banner_status'=>$val->banner_status,
                    'url_image'=>$url_image,
                );
            }
        }
        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['banner_status']);
        $this->layout->content = View::make('admin.Banner.view')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $data)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus)

            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function getBanner($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }
        $data = array();
        $arrViewImgOther = array();
        $imageOrigin = $urlImageOrigin = '';
        if($id > 0) {
            $data = Banner::getBannerByID($id);
            if(sizeof($data) > 0){
                //ảnh sản phẩm chính
               $imageOrigin = $data->banner_image;
               $urlImageOrigin = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_BANNER, $data->banner_id, $data->banner_image,CGlobal::sizeImage_200);
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['banner_status'])? $data['banner_status'] : CGlobal::status_show);
        $optionCategory = FunctionLib::getOption($this->arrCategoryNew, isset($data['banner_category_id'])? $data['banner_category_id'] : 0);
        $optionType = FunctionLib::getOption($this->arrTypeNew, isset($data['banner_type'])? $data['banner_type'] : 0);

        $this->layout->content = View::make('admin.Banner.add')
            ->with('id', $id)
            ->with('data', $data)
            ->with('imageOrigin', $imageOrigin)
            ->with('urlImageOrigin', $urlImageOrigin)
            ->with('arrViewImgOther', $arrViewImgOther)
            ->with('optionStatus', $optionStatus)
            ->with('optionCategory', $optionCategory)
            ->with('optionType', $optionType)
            ->with('arrStatus', $this->arrStatus);
    }
    public function postBanner($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }
        $dataSave['news_title'] = addslashes(Request::get('news_title'));
        $dataSave['news_desc_sort'] = addslashes(Request::get('news_desc_sort'));
        $dataSave['news_content'] = addslashes(Request::get('news_content'));
        $dataSave['news_type'] = addslashes(Request::get('news_type'));
        $dataSave['news_category'] = addslashes(Request::get('news_category'));
        $dataSave['news_status'] = (int)Request::get('news_status', 0);
        $id_hiden = (int)Request::get('id_hiden', 0);

        //ảnh chính
        $image_primary = addslashes(Request::get('image_primary'));
        //ảnh khác
        $getImgOther = Request::get('img_other',array());
        if(!empty($getImgOther)){
            foreach($getImgOther as $k=>$val){
                if($val !=''){
                    $arrInputImgOther[] = $val;
                }
            }
        }
        if (!empty($arrInputImgOther) && count($arrInputImgOther) > 0) {
            //nếu không chọn ảnh chính, lấy ảnh chính là cái đầu tiên
            $dataSave['news_image'] = ($image_primary != '')? $image_primary: $arrInputImgOther[0];
            $dataSave['news_image_other'] = serialize($arrInputImgOther);
        }

        //FunctionLib::debug($dataSave);
        if($this->valid($dataSave) && empty($this->error)) {
            $id = ($id == 0)?$id_hiden: $id;
            if($id > 0) {
                //cap nhat
                if(Banner::updateData($id, $dataSave)) {
                    return Redirect::route('admin.news_list');
                }
            } else {
                //them moi
                if(Banner::addData($dataSave)) {
                    return Redirect::route('admin.news_list');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['category_status'])? $dataSave['category_status'] : -1);
        $this->layout->content =  View::make('admin.Banner.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('error', $this->error)
            ->with('arrStatus', $this->arrStatus);
    }

    public function deleteBanner()
    {
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Banner::deleteData($id)) {
            $data['isIntOk'] = 1;
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


        $pid = (int)Request::get('pid');
        $psize = addslashes(Request::get('psize'));
        $pnum = (int)Request::get('pnum');

        if($pid > 0 && $pnum > 0){
            $result = Product::getProductByID($pid);
            $num_cart = 0;
            if(sizeof($result) != 0){
                $data = array();
                if(Session::has('shopcart')){
                    $data = Session::get('shopcart');
                    $data[$pid][$psize] += 1;
                }else{
                    $data[$pid][$psize] = 1;
                }
                Session::put('shopcart', $data, 60*24);//2gio
                echo 1;
            }else{
                if(Session::has('shopcart')){
                    Session::forget('shopcart');
                }
                echo 0;
            }
        }
        exit();
    }

}