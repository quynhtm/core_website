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
        
        $search['supplier_email'] = Request::get('supplier_email', '');
        $search['supplier_name'] = addslashes(Request::get('supplier_name',''));
        $search['supplier_phone'] = addslashes(Request::get('supplier_phone',''));
        $search['field_get'] = '';
        
        $dataSearch = Supplier::searchByCondition($search, $limit, $offset,$total);
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

    //ajax
    public function deleteProviderEmail(){
    	$result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Supplier::deleteData($id)) {
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
				if ($id > 0 && Supplier::deleteData($id)) {
					$data['isIntOk'] = 1;
				}
			}
		}
		return Response::json($data);
	}
}