<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class ProviderController extends BaseAdminController
{
    private $permission_view = 'provider_view';
    private $permission_full = 'provider_full';
    private $permission_delete = 'provider_delete';
    private $permission_create = 'provider_create';
    private $permission_edit = 'provider_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện', CGlobal::status_block => 'Khóa');
    private $arrIsShop = array(-1 => 'Tất cả', CGlobal::SHOP_FREE => 'Shop Free', CGlobal::SHOP_NOMAL => 'Shop thường', CGlobal::SHOP_VIP => 'Shop Vip');
    private $error = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['provider_id'] = addslashes(Request::get('provider_id',''));
        $search['provider_name'] = addslashes(Request::get('provider_name',''));
        $search['provider_phone'] = addslashes(Request::get('provider_phone',''));
        $search['provider_email'] = addslashes(Request::get('provider_email',''));
        $search['provider_status'] = (int)Request::get('provider_status',-1);
        //$search['field_get'] = 'category_id,category_name,category_status';//cac truong can lay

        $dataSearch = Provider::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['provider_status']);
        $this->layout->content = View::make('admin.Provider.view')
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
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $data = array();
        if($id > 0) {
            $item = Provider::getByID($id);
            if($item){
                $data['provider_id'] = $item->provider_id;
                $data['provider_name'] = $item->provider_name;
                $data['provider_phone'] = $item->provider_phone;
                $data['provider_address'] = $item->provider_address;
                $data['provider_email'] = $item->provider_email;
                $data['provider_shop_id'] = $item->provider_shop_id;
                $data['provider_shop_name'] = $item->provider_shop_name;
                $data['provider_status'] = $item->provider_status;
                $data['provider_note'] = $item->provider_note;
                $data['provider_time_creater'] = $item->provider_time_creater;
            }
        }
        //FunctionLib::debug($data);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['provider_status'])? $data['provider_status'] : -1);
        $this->layout->content = View::make('admin.Provider.add')
            ->with('id', $id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('optionIsShop', array())
            ->with('arrIsShop', $this->arrIsShop)
            ->with('arrStatus', $this->arrStatus);
    }

    public function postUserShop($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $dataSave['provider_name'] = addslashes(Request::get('provider_name'));
        $dataSave['provider_phone'] = addslashes(Request::get('provider_phone'));
        $dataSave['provider_address'] = addslashes(Request::get('provider_address'));
        $dataSave['provider_email'] = addslashes(Request::get('provider_email'));
        $dataSave['provider_shop_id'] = addslashes(Request::get('provider_shop_id'));
        $dataSave['provider_shop_name'] = addslashes(Request::get('provider_shop_name'));
        $dataSave['provider_note'] = addslashes(Request::get('provider_note'));
        $dataSave['provider_time_creater'] = time();
        $dataSave['provider_status'] = (int)Request::get('provider_status', CGlobal::status_hide);

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(Provider::updateData($id, $dataSave)) {
                    return Redirect::route('admin.provider_list');
                }
            } else {
                //them moi
                if(Provider::addData($dataSave)) {
                    return Redirect::route('admin.provider_list');
                }
            }
        }
        $optionIsShop = FunctionLib::getOption($this->arrIsShop, isset($dataSave['is_shop'])? $dataSave['is_shop'] : -1);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['shop_status'])? $dataSave['shop_status'] : -1);
        $this->layout->content =  View::make('admin.Provider.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('optionIsShop', $optionIsShop)
            ->with('error', $this->error)
            ->with('arrStatus', $this->arrStatus);
    }

    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['provider_name']) && $data['provider_name'] == '') {
                $this->error[] = 'Tên NCC không được trống';
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
        if ($id > 0 && Provider::deleteData($id)) {
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
            if(Provider::updateData($id, $dataSave)) {
                $result['isIntOk'] = 1;
            }
        }
        return Response::json($result);
    }

}