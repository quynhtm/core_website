<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class UserShopController extends BaseAdminController
{
    private $permission_view = 'user_shop_view';
    private $permission_full = 'user_shop_full';
    private $permission_delete = 'user_shop_delete';
    private $permission_create = 'user_shop_create';
    private $permission_edit = 'user_shop_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện', CGlobal::status_block => 'Khóa');
    private $arrIsShop = array(-1 => 'Tất cả', CGlobal::SHOP_FREE => 'Shop Free', CGlobal::SHOP_NOMAL => 'Shop thường', CGlobal::SHOP_VIP => 'Shop Vip');
    private $error = array();

    public function __construct()
    {
        parent::__construct();

        //Include style.
        FunctionLib::link_css(array(
            'lib/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/ckeditor/ckeditor.js',
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

        $search['shop_id'] = addslashes(Request::get('shop_id',''));
        $search['user_shop'] = addslashes(Request::get('user_shop',''));
        $search['shop_name'] = addslashes(Request::get('shop_name',''));
        $search['shop_status'] = (int)Request::get('shop_status',-1);
        //$search['field_get'] = 'category_id,category_name,category_status';//cac truong can lay

        $dataSearch = UserShop::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['shop_status']);
        $this->layout->content = View::make('admin.UserShop.view')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus)
            ->with('arrIsShop', $this->arrIsShop)

            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function getUserShop($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }
        $data = array();
        if($id > 0) {
            $item = UserShop::find($id);
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

        $optionIsShop = FunctionLib::getOption($this->arrIsShop, isset($data['is_shop'])? $data['is_shop'] : -1);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['shop_status'])? $data['shop_status'] : -1);
        $this->layout->content = View::make('admin.UserShop.add')
            ->with('id', $id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionIsShop', $optionIsShop)
            ->with('arrIsShop', $this->arrIsShop)
            ->with('arrStatus', $this->arrStatus);
    }

    public function postUserShop($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }

        $dataSave['shop_name'] = addslashes(Request::get('shop_name'));
        $dataSave['user_shop'] = addslashes(Request::get('user_shop'));
        $dataSave['shop_phone'] = addslashes(Request::get('shop_phone'));
        $dataSave['shop_email'] = addslashes(Request::get('shop_email'));
        $dataSave['shop_address'] = addslashes(Request::get('shop_address'));
        $dataSave['shop_about'] = addslashes(Request::get('shop_about'));
        $dataSave['shop_transfer'] = addslashes(Request::get('shop_transfer'));

        $dataSave['is_shop'] = (int)Request::get('is_shop', 0);
        $dataSave['shop_status'] = (int)Request::get('shop_status', 0);

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(UserShop::updateData($id, $dataSave)) {
                    return Redirect::route('admin.userShop_list');
                }
            } else {
                //them moi
                if(UserShop::addData($dataSave)) {
                    return Redirect::route('admin.userShop_list');
                }
            }
        }
        $optionIsShop = FunctionLib::getOption($this->arrIsShop, isset($dataSave['is_shop'])? $dataSave['is_shop'] : -1);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['shop_status'])? $dataSave['shop_status'] : -1);
        $this->layout->content =  View::make('admin.UserShop.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('optionIsShop', $optionIsShop)
            ->with('error', $this->error)
            ->with('arrStatus', $this->arrStatus);
    }

    private function valid($data=array()) {
        return true;
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

    //ajax
    public function deleteUserShop()
    {
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && UserShop::deleteData($id)) {
            $result['isIntOk'] = 1;
        }
        return Response::json($result);
    }

    //ajax
    public function updateStatusUserShop()
    {
        $id = (int)Request::get('id', 0);
        $shop_status = (int)Request::get('status', CGlobal::status_hide);
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }

        if ($id > 0) {
            $dataSave['shop_status'] = ($shop_status == CGlobal::status_hide)? CGlobal::status_show : CGlobal::status_hide;
            if(UserShop::updateData($id, $dataSave)) {
                $result['isIntOk'] = 1;
            }
        }
        return Response::json($result);
    }

}