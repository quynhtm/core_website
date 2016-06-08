<?php

class GroupUserController extends BaseAdminController
{

    private $permission_view = 'group_user_view';
    private $permission_create = 'group_user_create';
    private $permission_edit = 'group_user_edit';
    private $arrStatus = array(-1 => 'Khóa', 0 => 'Tất cả', 1 => 'Hoạt động');

    public function __construct()
    {
        parent::__construct();
    }

    public function view()
    {
        //check permission
        if (!in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }

        $page_no = Request::get('page_no', 1);//phan trang
        $dataSearch['group_user_name'] = Request::get('group_user_name', '');
        $dataSearch['group_user_status'] = Request::get('group_user_status', 0);


        $limit = 30;
        $offset = ($page_no - 1) * $limit;
        $total = 0;
        //call api
        $aryGroupUser = GroupUser::searchGroupUser($dataSearch, $limit, $offset, $total);
        if (!empty($aryGroupUser)) {
            $aryGroupId = array();
            foreach ($aryGroupUser as $val) {
                $aryGroupId[] = $val->group_user_id;
            }
            if (!empty($aryGroupId)) {
                $aryPermission = GroupUserPermission::getListPermissionByGroupId($aryGroupId);
                if (!empty($aryPermission)) {
                    foreach ($aryGroupUser as $k => $v) {
                        $items = $v;
                        foreach ($aryPermission as $val) {
                            if ($v->group_user_id == $val->group_user_id) {
                                $item = isset($v->permissions) ? $v->permissions : array();
                                $count = isset($v->countPermission) ? $v->countPermission : 0;
                                $item[] = $val;
                                $count++;
                                $items->permissions = $item;
                                $items->countPermission = $count;
                            }
                        }
                        $aryGroupUser[$k] = $items;
                    }
                }
            }
        }


        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $dataSearch) : '';

        $this->layout->content = View::make('admin.GroupUser.view')
            ->with('data', $aryGroupUser)
            ->with('dataSearch', $dataSearch)
            ->with('total', $total)
            ->with('start', ($page_no - 1) * $limit)
            ->with('paging', $paging)
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0)
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)
            ->with('arrStatus', $this->arrStatus);

    }

    public function createInfo()
    {
//        CGlobal::$pageTitle = "Tạo nhóm User | Admin Seo";
        if (!in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }
        // Show the page
        $listPermission = Permission::getListPermission();
        $arrPermissionByController = $this->buildArrayPermissionByController($listPermission);
        $this->layout->content = View::make('admin.GroupUser.create')
//            ->with('optionStatus',$optionStatus)
            ->with('arrPermissionByController', $arrPermissionByController);
    }

    public function create()
    {
        //check permission
        if (!in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }

        $error = array();
        $data['group_user_name'] = htmlspecialchars(trim(Request::get('group_user_name', '')));

        //encode các ký tự html

        $data['group_user_status'] = 1;

        $arrPermission = Request::get('permission_id', array());
//        if(!empty($arrPermission)){
//            $strPermission = implode(',',$arrPermission);
//            $dataSave['strPermission'] = $strPermission;
//        }

        if ($data['group_user_name'] == '') {
            $error[] = 'Tên nhóm người dùng không được để trống ';
        }
        if (GroupUser::checkExitsGroupName($data['group_user_name'])) {
            $error[] = 'Tên nhóm người dùng đã được sử dụng ';
        }
        if ($error != null) {
            $listPermission = Permission::getListPermission();
            $arrPermissionByController = $this->buildArrayPermissionByController($listPermission);
            $data['strPermission'] = $arrPermission;
            $this->layout->content = View::make('admin.GroupUser.create')
                ->with('error', $error)
                ->with('data', $data)
                ->with('arrPermissionByController', $arrPermissionByController);
        } else {
            //urlencode dữ liệu
//            $dataSave = FunctionLib::urlEncode($dataSave);
            //insert dl
            if (GroupUser::createGroup($data, $arrPermission)) {
                return Redirect::route('admin.groupUser_view');
            } else {
                $listPermission = Permission::getListPermission();
                $arrPermissionByController = $this->buildArrayPermissionByController($listPermission);
                $data['strPermission'] = $arrPermission;
                $error[] = 'Lỗi truy xuất dữ liệu';
                $this->layout->content = View::make('admin.GroupUser.create')
                    ->with('error', $error)
                    ->with('data', $data)
                    ->with('arrPermissionByController', $arrPermissionByController);
            }
        }
    }

    public function editInfo($id)
    {
//        CGlobal::$pageTitle = "Sửa nhóm User | Admin Seo";
        if (!in_array($this->permission_edit, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }

        $data = GroupUser::find($id);//lay dl permission theo id
        $dataPermission = GroupUserPermission::getListPermissionByGroupId(array($id));

        $aryPermission = array();
        if ($dataPermission) {
            foreach ($dataPermission as $per) {
                $aryPermission[] = $per->permission_id;
            }
        }
        $data->strPermission = $aryPermission;

        // Show the page
        $listPermission = Permission::getListPermission();
        $arrPermissionByController = $this->buildArrayPermissionByController($listPermission);
        $this->layout->content = View::make('admin.GroupUser.edit')
            ->with('data', $data)
            ->with('arrStatus', $this->arrStatus)
//            ->with('arrType',$this->arrType)
            ->with('arrPermissionByController', $arrPermissionByController);
    }

    public function edit($id)
    {
        //check permission
        if (!in_array($this->permission_edit, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }
        $error = array();
        $data['group_user_name'] = htmlspecialchars(trim(Request::get('group_user_name', '')));

        $data['group_user_status'] = Request::get('group_user_status', 1);

        $arrPermission = Request::get('permission_id');

        if ($data['group_user_name'] == '') {
            $error[] = 'Tên nhóm người dùng không được để trống ';
        }
        if (GroupUser::checkExitsGroupName($data['group_user_name'], $id)) {
            $error[] = 'Tên nhóm người dùng đã được sử dụng ';
        }
        if ($error != null) {
            $listPermission = Permission::getListPermission();
            $arrPermissionByController = $this->buildArrayPermissionByController($listPermission);
            $data['strPermission'] = $arrPermission;
            $this->layout->content = View::make('admin.GroupUser.edit')
                ->with('error', $error)
                ->with('data', $data)
                ->with('arrStatus', $this->arrStatus)
                ->with('arrPermissionByController', $arrPermissionByController);
        } else {

            if (GroupUser::updateGroup($id, $data, $arrPermission)) {
                return Redirect::route('admin.groupUser_view');
            } else {
                $listPermission = Permission::getListPermission();
                $arrPermissionByController = $this->buildArrayPermissionByController($listPermission);
                $error['mess'] = 'Lỗi truy xuất dữ liệu';
                $data['strPermission'] = $arrPermission;
                $this->layout->content = View::make('admin.GroupUser.edit')
                    ->with('error', $error)
                    ->with('data', $data)
                    ->with('arrStatus', $this->arrStatus)
                    ->with('arrPermissionByController', $arrPermissionByController);
            }
        }
    }

    private function buildArrayPermissionByController($listPermission)
    {

        $arrPermissionByController = array();
        if (!empty($listPermission)) {
            foreach ($listPermission as $permission) {
                $arrPermissionByController[$permission['permission_group_name']][] = $permission;
            }
        }
        return $arrPermissionByController;
    }


}