<?php

/**
 * Created by PhpStorm.
 * User: MT969
 * Date: 5/30/2015
 * Time: 4:22 PM
 */
class PermissionController extends BaseAdminController
{

    private $permission_view = 'permission_view';
    private $permission_create = 'permission_create';
    private $permission_edit = 'permission_edit';
    private $arrStatus = array(-1 => 'Xóa', 0 => 'Tất cả', 1 => 'Hoạt động');

    public function __construct()
    {
        parent::__construct();
    }

    public function view()
    {
//        if (!in_array($this->permission_view, $this->permission)) {
//            return Redirect::route('admin.dashboard');
//        }

        $dataSearch = $dataResponse = $data = array();
        $page_no = Request::get('page_no', 1);//phan trang

        $dataSearch['permission_code'] = Request::get('permission_code', '');
        $dataSearch['permission_name'] = Request::get('permission_name', '');
        $dataSearch['permission_status'] = Request::get('permission_status', 0);
        $limit = 30;
        $offset = ($page_no - 1) * $limit;
        $total = 0;
        //call api
        $aryPermission = Permission::searchPermission($dataSearch, $limit, $offset, $total);
        if (!empty($aryPermission)) {
            $aryPermissionId = array();
            foreach ($aryPermission as $val) {
                $aryPermissionId[] = $val->permission_id;
            }
//            if(!empty($aryPermissionId)) {
//                $aryGroupUser = GroupUserPermission::getListGroupByPermissionId($aryPermissionId);
//                if(!empty($aryGroupUser)) {
//                    foreach($aryPermission as $k => $v) {
//                        $items = $v;
//                        foreach($aryGroupUser as $val) {
//                            if($v->permission_id == $val->permission_id) {
//                                $item = isset($v->groups) ? $v->groups : array();
//                                $count = isset($v->countGroup) ? $v->countGroup : 0;
//                                $item[] = $val;
//                                $count++;
//                                $items->groups = $item;
//                                $items->countGroup = $count;
//                            }
//                        }
//                        $aryPermission[$k] = $items;
//                    }
//                }
//            }
        }

        $paging = $total > 0 ? Pagging::getNewPager(3,$page_no,$total,$limit,$dataSearch) : '';
        $this->layout->content = View::make('admin.Permission.view')
            ->with('data', $aryPermission)
            ->with('dataSearch', $dataSearch)
            ->with('total', $total)
            ->with('paging', $paging)
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0)
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)
            ->with('start', ($page_no - 1) * $limit)
            ->with('arrStatus', $this->arrStatus);
    }

    public function createInfo()
    {
//        CGlobal::$pageTitle = "Tạo mới quyền | Admin Seo";
//        if (!in_array($this->permission_create, $this->permission)) {
//            return Redirect::route('admin.dashboard');
//        }
        //$optionStatus = FunctionLib::getOption($this->arrStatus, 1);
        // Show the page
//        $arrGroupUser = GroupUser::getListGroupUser();
        $this->layout->content = View::make('admin.Permission.create');
        //->with('optionStatus',$optionStatus)
    }

    public function create()
    {
//        //check permission
//        if (!in_array($this->permission_create, $this->permission)) {
//            return Redirect::route('admin.dashboard');
//        }
        $error = array();
        $data['permission_code'] = htmlspecialchars(trim(Request::get('permission_code', '')));
        $data['permission_name'] = htmlspecialchars(trim(Request::get('permission_name', '')));
        $data['permission_group_name'] = htmlspecialchars(trim(Request::get('permission_group_name', '')));
        //encode các ký tự html
//        $dataResult = FunctionLib::encodeParam($dataEncode);
//        $dataSave['permission_code'] = (!FunctionLib::stripUnicode($dataResult['permission_code'])) ? $dataResult['permission_code'] : FunctionLib::stripUnicode($dataResult['permission_code']);
//        $dataSave['permission_name'] = $dataResult['permission_name'];
        $data['permission_status'] = 1;
//        $dataSave['permission_group_name'] = (!FunctionLib::stripUnicode($dataResult['permission_group_name'])) ? $dataResult['permission_group_name'] : FunctionLib::stripUnicode($dataResult['permission_group_name']);
//        $data = $dataSave;

//        $arrGroupUser = $data['user_group'] = Request::get('user_group',array());

        if ($data['permission_code'] == '') {
            $error[] = 'Mã quyền không được để trống ';
        }
        if ($data['permission_name'] == '') {
            $error[] = 'Tên quyền không được để trống ';
        }
        if ($data['permission_group_name'] == '') {
            $error[] = 'Nhóm quyền không được để trống ';
        }
        if (Permission::checkExitsPermissionCode($data['permission_code'])) {
            $error[] = 'Mã quyền đã tồn tại ';
        }

        if ($error != null) {
            $this->layout->content = View::make('admin.Permission.create')
                ->with('error', $error)
                ->with('data', $data);
        } else {

            //insert dl
            if (Permission::createPermission($data)) {
                return Redirect::route('admin.permission_view');
            } else {
                $error[] = 'Lỗi truy xuất dữ liệu';
                $this->layout->content = View::make('admin.Permission.create')
                    ->with('error', $error)
                    ->with('data', $data);
            }
        }

    }

    public function editInfo($id)
    {
//        CGlobal::$pageTitle = "Sửa quyền | Admin Seo";
//        if (!in_array($this->permission_edit, $this->permission)) {
//            return Redirect::route('admin.dashboard');
//        }
        $data = Permission::find($id);//lay dl permission theo id
//        $groupPermission = new GroupUserPermission();
//        $dataGroups = $groupPermission->getListGroupByPermissionId(array($id));

//        $aryGroup = array();
//        if($dataGroups){
//            foreach($dataGroups as $group){
//                $aryGroup[] = $group->group_user_id;
//            }
//        }
//        $data->user_group = $aryGroup;

        $this->layout->content = View::make('admin.Permission.edit')
            ->with('data', $data)
            ->with('arrStatus', $this->arrStatus);
//            ->with('arrGroupUser',GroupUser::getListGroupUser());
    }

    public function edit($id)
    {
        //check permission
//        if (!in_array($this->permission_edit, $this->permission)) {
//            return Redirect::route('admin.dashboard');
//        }
        $error = array();
        $data['permission_code'] = htmlspecialchars(trim(Request::get('permission_code', '')));
        $data['permission_name'] = htmlspecialchars(trim(Request::get('permission_name', '')));
        $data['permission_group_name'] = htmlspecialchars(trim(Request::get('permission_group_name', '')));
        $data['permission_status'] = (int)Request::get('permission_status', 1);
        //encode các ký tự html
        if ($data['permission_code'] == '') {
            $error['mess'] = 'Mã quyền không được để trống ';
        }
        if ($data['permission_name'] == '') {
            $error['mess'] = 'Tên quyền không được để trống ';
        }
        if ($data['permission_group_name'] == '') {
            $error['mess'] = 'Nhóm quyền không được để trống ';
        }

        if (Permission::checkExitsPermissionCode($data['permission_code'], $id)) {
            $error[] = 'Mã quyền đã tồn tại ';
        }

        if ($error != null) {
            $this->layout->content = View::make('admin.Permission.edit')
                ->with('error', $error['mess'])
                ->with('data', $data)
                ->with('arrStatus', $this->arrStatus);
        } else {

            if (Permission::updatePermission($id, $data)) {
                return Redirect::route('admin.permission_view');
            } else {
                $error[] = 'Lỗi truy xuất dữ liệu';
                $this->layout->content = View::make('admin.Permission.edit')
                    ->with('error', $error)
                    ->with('data', $data)
                    ->with('arrStatus', $this->arrStatus);
            }
        }

        parent::debug();
    }


}