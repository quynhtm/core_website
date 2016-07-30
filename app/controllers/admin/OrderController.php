<?php
/*
* @Created by: HSS
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
class OrderController extends BaseAdminController
{
    private $permission_view = 'order_view';
    private $permission_full = 'order_full';
    private $permission_delete = 'order_delete';
    private $permission_create = 'order_create';
    private $permission_edit = 'order_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');

    public function __construct()
    {
        parent::__construct();

        //Include style.
        FunctionLib::link_css(array(
            'lib/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/jquery.uploadfile.js',
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

        $search['order_id'] = addslashes(Request::get('order_id',''));
        $search['order_product_name'] = addslashes(Request::get('order_product_name',''));
        $search['order_status'] = (int)Request::get('order_status',-1);
        $search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $dataSearch = Order::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        if(!empty($dataSearch)){
            foreach($dataSearch as $k=> $val){
                $data[] = array('order_id'=>$val->order_id,
                    'order_product_name'=>$val->order_product_name,
                    'order_status'=>$val->order_status,
                );
            }
        }
        //FunctionLib::debug($dataSearch);
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['order_status']);
        $this->layout->content = View::make('admin.Order.view')
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

    public function getOrder($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }
        $data = array();
        if($id > 0) {
            $data = Order::find($id);
        }

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['order_status'])? $data['order_status'] : -1);
        $this->layout->content = View::make('admin.Order.add')
            ->with('id', $id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus);
    }

    public function postProduct($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard');
        }

        $dataSave['order_product_name'] = addslashes(Request::get('order_product_name'));
        $dataSave['order_status'] = (int)Request::get('order_status', 0);
        

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(Order::updateData($id, $dataSave)) {
                    return Redirect::route('admin.order_list');
                }
            } else {
                //them moi
                if(Order::addData($dataSave)) {
                    return Redirect::route('admin.order_list');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['order_status'])? $dataSave['order_status'] : -1);
        $this->layout->content =  View::make('admin.Order.add')
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
        if ($id > 0 && Order::deleteData($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['order_product_name']) && $data['order_product_name'] == '') {
                $this->error[] = 'Tên đơn hàng không được trống';
            }
            if(isset($data['order_status']) && $data['order_status'] == -1) {
                $this->error[] = 'Bạn chưa chọn trạng thái cho đơn hàng';
            }
            return true;
        }
        return false;
    }

}