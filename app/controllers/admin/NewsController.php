<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class NewsController extends BaseAdminController
{
    private $permission_view = 'news_view';
    private $permission_full = 'news_full';
    private $permission_delete = 'news_delete';
    private $permission_create = 'news_create';
    private $permission_edit = 'news_edit';
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

        $search['news_title'] = addslashes(Request::get('news_title',''));
        $search['news_status'] = (int)Request::get('news_status',-1);
        //$search['field_get'] = 'category_id,news_title,news_status';//cac truong can lay

        $dataSearch = News::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        if(!empty($dataSearch)){
            foreach($dataSearch as $k=> $val){
                $data[] = array('news_id'=>$val->news_id,
                    'news_title'=>$val->news_title,
                    'news_status'=>$val->news_status,
                );
            }
        }
        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['news_status']);
        $this->layout->content = View::make('admin.News.view')
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

    public function getNews($id=0) {

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }
        $data = array();
        if($id > 0) {
            $data = News::find($id);
            if(isset($data['category_image_background']) && $data['category_image_background'] != ''){
                $data['url_src_icon'] = URL::to('/').'/images/category/'.$data['category_image_background'];
            }
        }

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['news_status'])? $data['news_status'] : CGlobal::status_show);
        $optionCategory = FunctionLib::getOption($this->arrCategoryNew, isset($data['news_category'])? $data['news_category'] : CGlobal::NEW_CATEGORY_TIN_TUC_CHUNG);
        $optionType = FunctionLib::getOption($this->arrTypeNew, isset($data['news_type'])? $data['news_type'] : CGlobal::NEW_TYPE_TIN_TUC);

        $this->layout->content = View::make('admin.News.add')
            ->with('id', $id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionCategory', $optionCategory)
            ->with('optionType', $optionType)
            ->with('arrStatus', $this->arrStatus);
    }

    public function postNews($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }

        $dataSave['news_title'] = addslashes(Request::get('news_title'));
        $dataSave['news_desc_sort'] = addslashes(Request::get('news_desc_sort'));
        $dataSave['news_content'] = addslashes(Request::get('news_content'));
        $dataSave['news_image'] = addslashes(Request::get('news_image'));
        $dataSave['news_image_other'] = addslashes(Request::get('news_image_other'));
        $dataSave['news_type'] = addslashes(Request::get('news_type'));
        $dataSave['news_category'] = addslashes(Request::get('news_category'));
        $dataSave['news_status'] = (int)Request::get('news_status', 0);

        /*$file = Input::file('image');
        if($file){
            $destinationPath = public_path().'/images/category/';
            $filename = $file->getClientOriginalName();
            $upload  = Input::file('image')->move($destinationPath, $filename);
            //FunctionLib::debug($filename);
            $dataSave['category_image_background'] = $filename;
        }else{
            $dataSave['category_image_background'] = Request::get('category_image_background', '');
        }*/

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(News::updateData($id, $dataSave)) {
                    return Redirect::route('admin.news_list');
                }
            } else {
                //them moi
                if(News::addData($dataSave)) {
                    return Redirect::route('admin.news_list');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['category_status'])? $dataSave['category_status'] : -1);
        $this->layout->content =  View::make('admin.News.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('error', $this->error)
            ->with('arrStatus', $this->arrStatus);
    }

    public function deleteItem()
    {
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && News::deleteData($id)) {
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
    }

    function sendEmail(){
         // test gửi email
         Mail::send('emails.test_email', array('firstname'=>'Trương Mạnh Quỳnh'), function($message){
             $message->to('manhquynh1984@gmail.com', 'Trương Mạnh Quỳnh')
                 ->subject('Welcome to the Laravel 4 Auth App!');
         });

         die();
    }

}