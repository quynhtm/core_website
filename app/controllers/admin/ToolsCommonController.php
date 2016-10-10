<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class ToolsCommonController extends BaseAdminController
{
    private $permission_view = 'toolsCommon_view';
    private $permission_full = 'toolsCommon_full';
    private $permission_delete = 'toolsCommon_delete';
    private $permission_create = 'toolsCommon_create';
    private $permission_edit = 'toolsCommon_edit';

    private $permission_full_content_email = 'toolsCommon_full_content_email';

    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $error = array();

    public function __construct()
    {
        parent::__construct();
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

    /************************************************************************************************************************************
     * @return mixed
     * Quản lý lượt share của shop
     * **********************************************************************************************************************************
     */
    public function viewShopShare() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['shop_name'] = addslashes(Request::get('shop_name',''));
        $search['shop_id'] = (int)Request::get('shop_id',0);

        $dataSearch = ShopShare::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($dataSearch);
        $arrShop = UserShop::getShopAll();
        //$optionShop = FunctionLib::getOption(array(0=>'-- Chọn Shop ---') + $arrShop, $search['shop_id']);
        $this->layout->content = View::make('admin.ToolsCommon.viewShopShare')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('arrShop', $arrShop)

            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    /************************************************************************************************************************************
     * @return mixed
     * Quản lý nội dung gửi email quảng cáo
     * **********************************************************************************************************************************
     */
    public function viewContentSendEmail() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full_content_email,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['mail_send_title'] = addslashes(Request::get('mail_send_title',''));
        $search['mail_send_status'] = (int)Request::get('mail_send_status',-1);

        $dataSearch = EmailContent::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['mail_send_status']);
        $this->layout->content = View::make('admin.ToolsCommon.viewContentSendEmail')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)

            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }
    public function getContentSendEmail($id=0) {
        if(!$this->is_root && !in_array($this->permission_full_content_email,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $data = array();
        if($id > 0) {
            $item = EmailContent::getByID($id);
            if($item){
                $data['mail_send_id'] = $item->mail_send_id;
                $data['mail_send_title'] = $item->mail_send_title;
                $data['mail_send_content'] = $item->mail_send_content;
                $data['mail_send_str_product_id'] = $item->mail_send_str_product_id;
                $data['mail_send_link'] = $item->mail_send_link;
                $data['mail_send_img'] = $item->mail_send_img;
                $data['mail_send_status'] = $item->mail_send_status;
                $data['mail_send_time_creater'] = $item->mail_send_time_creater;
                $data['mail_send_time_update'] = $item->mail_send_time_update;
            }
        }
        //FunctionLib::debug($data);
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['mail_send_status'])? $data['mail_send_status'] : -1);
        $this->layout->content = View::make('admin.ToolsCommon.editContentSendEmail')
            ->with('id', $id)
            ->with('error', $this->error)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus);
    }
    public function postContentSendEmail($id=0) {
        if(!$this->is_root && !in_array($this->permission_full_content_email,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $dataSave['mail_send_title'] = addslashes(Request::get('mail_send_title'));
        $dataSave['mail_send_content'] = Request::get('mail_send_content');
        $dataSave['mail_send_str_product_id'] = addslashes(Request::get('mail_send_str_product_id'));
        $dataSave['mail_send_link'] = addslashes(Request::get('mail_send_link'));
        $dataSave['mail_send_img'] = addslashes(Request::get('mail_send_img'));
        $dataSave['mail_send_time_creater'] = time();
        $dataSave['mail_send_status'] = (int)Request::get('mail_send_status', CGlobal::status_hide);

        if($this->valid($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                $dataSave['mail_send_time_update'] = time();
                if(EmailContent::updateData($id, $dataSave)) {
                    return Redirect::route('admin.contentSendEmail_list');
                }
            } else {
                //them moi
                $dataSave['mail_send_time_creater'] = time();
                if(EmailContent::addData($dataSave)) {
                    return Redirect::route('admin.contentSendEmail_list');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['mail_send_status'])? $dataSave['mail_send_status'] : -1);
        $this->layout->content =  View::make('admin.ToolsCommon.editContentSendEmail')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('error', $this->error)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['mail_send_link']) && $data['mail_send_link'] == '') {
                $this->error[] = 'Link view không được trống';
            }
            if(isset($data['mail_send_title']) && $data['mail_send_title'] == '') {
                $this->error[] = 'Tiêu đề mail không được trống';
            }
            if(isset($data['mail_send_str_product_id']) && $data['mail_send_str_product_id'] == '') {
                $this->error[] = 'Sản phẩm liên quan không được trống';
            }
            return true;
        }
        return false;
    }
    public function deleteContentSendEmail(){
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full_content_email,$this->permission)){
            return Response::json($result);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && EmailContent::deleteData($id)) {
            $result['isIntOk'] = 1;
        }
        return Response::json($result);
    }
    public function sendEmailContentToCustomer(){
    	
    	$dataId = Request::get('dataId', array());
    	$emailId = Request::get('emailId', 0);
    	
    	if(!empty($dataId) && $emailId > 0){
    		//Get Email Content
    		$dataContentEmail = EmailContent::getByID($emailId);
    		//Get List Email
    		$offset = $total = 0;
    		$limit = count($dataId);
    		$dataSearch['customer_id'] = $dataId;
    		$listEmail = CustomerEmail::searchByCondition($dataSearch, $limit, $offset, $total);
    		//Get List Product
    		$listProduct = array();
    		$strProduct = $dataContentEmail->mail_send_str_product_id;
    		$arrProduct = array();
    		if($strProduct != ''){
    			$arrProduct = explode(',', $strProduct);
    		}
    		if(!empty($arrProduct)){
    			$limit1 = count($arrProduct);
    			$total1 = 0;
    			$dataSearch1['product_id'] = $arrProduct;
    			$listProduct = Product::searchByCondition($dataSearch1, $limit1, $offset, $total1);
    		}
    		
    		if(sizeof($listEmail) > 0 && sizeof($dataContentEmail) > 0){
    			$emails = array();
    			$emails[] = 'nguyenduypt86@gmail.com';
    			$emails[] = 'shoponlinecuatui@gmail.com';
    			
    			foreach($listEmail as $email){
    				$emails[] = $email->customer_master_email;
    			}
    			
    			$data['textMail'] = $dataContentEmail->mail_send_content;
    			$data['listProduct'] = $listProduct;
    			$subjects = $dataContentEmail->mail_send_title;
    			if(!empty($emails)){
	    			Mail::send('emails.SendProductToCustomer', array('data'=>$data), function($message) use ($emails, $subjects){
	    					$message->to($emails, 'SendMailToCustomer')
	    							->subject($subjects);
	    			});
    			}
    			
    		}
    	}
    	echo 'Ok';die;
    }
}