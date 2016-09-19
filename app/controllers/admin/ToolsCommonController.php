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
    private $error = array();

    public function __construct()
    {
        parent::__construct();
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

}