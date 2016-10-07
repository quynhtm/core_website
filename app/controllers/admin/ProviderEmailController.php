<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class ProviderEmailController extends BaseAdminController
{
    private $permission_view = 'providerEmail_view';
    private $permission_full = 'providerEmail_full';
    private $permission_delete = 'providerEmail_delete';
    private $permission_create = 'providerEmail_create';
    private $permission_edit = 'providerEmail_edit';
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
        
        $search['provider_email'] = (int)Request::get('provider_email', '');
        $search['provider_name'] = addslashes(Request::get('provider_name',''));
        $search['provider_phone'] = addslashes(Request::get('provider_phone',''));
        $search['field_get'] = '';
        
        $dataSearch = ProviderEmail::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
		
        $this->layout->content = View::make('admin.ProviderEmail.view')
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

    public function getProviderEmail($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        
        $data = array();
        if($id > 0) {
            $item = ProviderEmail::getByID($id);
            if($item){
            	$data['provider_id'] = $item->provider_id;
            	$data['provider_name'] = $item->provider_name;
                $data['provider_email'] = $item->provider_email;
                $data['provider_phone'] = $item->provider_phone;
                $data['provider_email'] = $item->provider_email;
            }
        }
        $this->layout->content = View::make('admin.ProviderEmail.add')
            ->with('id', $id)
            ->with('error', $this->error)
            ->with('data', $data);
    }

    public function postProviderEmail($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $dataSave['provider_name'] = addslashes(Request::get('provider_name'));
        $dataSave['provider_email'] = addslashes(Request::get('provider_email'));
        $dataSave['provider_phone'] = addslashes(Request::get('provider_phone'));
        $dataSave['provider_address'] = addslashes(Request::get('provider_address'));
       
        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
            	if(ProviderEmail::updateData($id, $dataSave)) {
                    return Redirect::route('admin.provideremail_list');
                }
            } else {
                //them moi
                if(ProviderEmail::addData($dataSave)) {
                    return Redirect::route('admin.provideremail_list');
                }
            }
        }
        $this->layout->content =  View::make('admin.ProviderEmail.add')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('error', $this->error);
    }

    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['provider_email']) && $data['provider_email'] == '') {
                $this->error[] = 'Email khách hàng không được trống!';
            }
            return true;
        }
        return false;
    }

    //ajax
    public function deleteProviderEmail(){
    	$result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && ProviderEmail::deleteData($id)) {
            $result['isIntOk'] = 1;
        }
        return Response::json($result);
    }
	public function deleteMultiProviderEmail(){
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
				if ($id > 0 && ProviderEmail::deleteData($id)) {
					$data['isIntOk'] = 1;
				}
			}
		}
		return Response::json($data);
	}
}