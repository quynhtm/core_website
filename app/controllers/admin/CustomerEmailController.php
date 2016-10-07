<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class CustomerEmailController extends BaseAdminController
{
    private $permission_view = 'customerEmail_view';
    private $permission_full = 'customerEmail_full';
    private $permission_delete = 'customerEmail_delete';
    private $permission_create = 'customerEmail_create';
    private $permission_edit = 'customerEmail_edit';
    private $error = array();

    public function __construct(){
        parent::__construct();
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_show_40;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;
        
        $search['customer_phone'] = (int)Request::get('customer_phone', '');
        $search['customer_full_name'] = addslashes(Request::get('customer_full_name',''));
        $search['customer_master_email'] = addslashes(Request::get('customer_master_email',''));
        
        $dataSearch = CustomerEmail::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        $this->layout->content = View::make('admin.CustomerEmail.view')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function getCustomerEmail($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        
        $data = array();
        if($id > 0) {
            $item = CustomerEmail::getByID($id);
            if($item){
            	$data['customer_id'] = $item->customer_id;
            	$data['customer_full_name'] = $item->customer_full_name;
                $data['customer_master_email'] = $item->customer_master_email;
                $data['customer_phone'] = $item->customer_phone;
                $data['customer_address'] = $item->customer_address;
            }
        }
        $this->layout->content = View::make('admin.CustomerEmail.add')
            ->with('id', $id)
            ->with('error', $this->error)
            ->with('data', $data);
    }

    public function postCustomerEmail($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $dataSave['customer_full_name'] = addslashes(Request::get('customer_full_name'));
        $dataSave['customer_master_email'] = addslashes(Request::get('customer_master_email'));
        $dataSave['customer_phone'] = addslashes(Request::get('customer_phone'));
        $dataSave['customer_address'] = addslashes(Request::get('customer_address'));
       
        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
            	if(CustomerEmail::updateData($id, $dataSave)) {
                    return Redirect::route('admin.customeremail_list');
                }
            } else {
                //them moi
                if(CustomerEmail::addData($dataSave)) {
                    return Redirect::route('admin.customeremail_list');
                }
            }
        }
        $this->layout->content =  View::make('admin.CustomerEmail.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('error', $this->error);
    }

    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['customer_master_email']) && $data['customer_master_email'] == '') {
                $this->error[] = 'Email khách hàng không được trống!';
            }
            return true;
        }
        return false;
    }

    //ajax
    public function deleteCustomerEmail(){
    	$result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && CustomerEmail::deleteData($id)) {
            $result['isIntOk'] = 1;
        }
        return Response::json($result);
    }
	public function deleteMultiCustomerEmail(){
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
				if ($id > 0 && CustomerEmail::deleteData($id)) {
					$data['isIntOk'] = 1;
				}
			}
		}
		return Response::json($data);
	}
}