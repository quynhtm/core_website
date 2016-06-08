<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class UserController extends BaseAdminController
{
    private $permission_view = 'user_view';
    private $permission_create = 'user_create';
    private $permission_edit = 'user_edit';
    private $permission_change_pass = 'user_change_pass';
    private $permission_remove = 'user_remove';
    private $arrStatus = array(0 => 'Tất cả', 1 => 'Hoạt động', -1 => "Khóa");

    public function __construct()
    {
        parent::__construct();
    }

    public function view()
    {
        CGlobal::$pageAdminTitle  = "Quản trị User | Admin CMS";
        //check permission
        if (!$this->is_root && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }
        $page_no = Request::get('page_no', 1);
        $dataSearch['user_status'] = Request::get('user_status', 0);
        $dataSearch['user_email'] = Request::get('user_email', '');
        $dataSearch['user_full_name'] = Request::get('user_full_name', '');
        $dataSearch['user_name'] = Request::get('user_name', '');
        $dataSearch['user_group'] = Request::get('user_group', 0);

        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($page_no - 1) * $limit;
        $data = User::searchByCondition($dataSearch, $limit, $offset, $total);
        $arrGroupUser = GroupUser::getListGroupUser();

        $paging = $total > 0 ? Pagging::getNewPager(3,$page_no,$total,$limit,$dataSearch) : '';
        $this->layout->content = View::make('admin.User.view')
            ->with('arrStatus', $this->arrStatus)
            ->with('arrGroupUser', $arrGroupUser)
            ->with('data', $data)
            ->with('dataSearch', $dataSearch)
            ->with('size', $total)
            ->with('start', ($page_no - 1) * $limit)
            ->with('paging', $paging)
            ->with('is_root', $this->is_root)
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0)
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)
            ->with('permission_change_pass', in_array($this->permission_change_pass, $this->permission) ? 1 : 0)
            ->with('permission_remove', in_array($this->permission_remove, $this->permission) ? 1 : 0);
    }

    public function createInfo(){
        CGlobal::$pageAdminTitle = "Tạo mới User | Admin CMS";
        if (!$this->is_root && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }
        $arrGroupUser = GroupUser::getListGroupUser();
        $this->layout->content = View::make('admin.User.create')
            ->with('arrGroupUser', $arrGroupUser);
    }

    public function editInfo($id)
    {
        CGlobal::$pageTitle = "Sửa nhóm User | Admin Seo";
//        //check permission
        if (!$this->is_root && !in_array($this->permission_edit, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }

        $data = User::getUserById($id);
        $data['user_group'] = explode(',', $data['user_group']);
        $arrGroupUser = GroupUser::getListGroupUser();
        $this->layout->content = View::make('admin.User.edit')
            ->with('arrGroupUser', $arrGroupUser)
            ->with('is_root', $this->is_root)
            ->with('arrStatus', $this->arrStatus)
            ->with('data', $data);
    }

    public function create()
    {
        CGlobal::$pageAdminTitle = "Tạo mới User | Admin CMS";
        //check permission
        if(!$this->is_root && !in_array($this->permission_create, $this->permission)){
            return Redirect::route('admin.dashboard');
        }
        $error = array();
        $data['user_name'] = htmlspecialchars(trim(Request::get('user_name', '')));
        $data['user_full_name'] = htmlspecialchars(trim(Request::get('user_full_name', '')));
        $data['user_email'] = htmlspecialchars(trim(Request::get('user_email', '')));
        $data['user_phone'] = htmlspecialchars(trim(Request::get('user_phone', '')));

        if ($data['user_name'] == '') {
            $error[] = 'Tên đăng nhập không được bỏ trống';
        } else {
            $dataResponse = User::getUserByName($data['user_name']);
            if ($dataResponse) {
                $error[] = 'Tên đăng nhập đã tồn tại!';
            }
        }
        if (isset($data['user_full_name']) && $data['user_full_name'] == '') {
            $error[] = 'Tên nhân viên không được bỏ trống';
        }

        $groupUser = $data['user_group'] = Request::get('user_group', array());
        if ($groupUser) {
            $strGroupUser = implode(',', $groupUser);
            $dataInsert['user_group'] = $strGroupUser;
        }
        if (empty($error)) {
            //Insert dữ liệu
            $dataInsert['user_name'] = $data['user_name'];
            $dataInsert['user_email'] = $data['user_email'];
            $dataInsert['user_phone'] = $data['user_phone'];
            $dataInsert['user_full_name'] = $data['user_full_name'];
            $dataInsert['user_status'] = 1;
            $dataInsert['user_password'] = 'vpp@123';
            $dataInsert['user_create_id'] = User::user_id();
            $dataInsert['user_create_name'] = User::user_name();
            $dataInsert['user_created'] = time();
            if (User::createNew($dataInsert)) {
                return Redirect::route('admin.user_view');
            } else {
                $error['mess'] = 'Lỗi truy xuất dữ liệu';
            }
        }
        $arrGroupUser = GroupUser::getListGroupUser();
        $this->layout->content = View::make('admin.User.create')
            ->with('error', $error)
            ->with('data', $data)
            ->with('arrGroupUser', $arrGroupUser);
    }

    public function edit($id){
        //check permission
        if (!$this->is_root && !in_array($this->permission_edit, $this->permission)) {
            return Redirect::route('admin.dashboard');
        }

        $error = array();
        $data['user_id'] = $id;
        $data['user_status'] = (int)Request::get('user_status', -1);
        $data['user_full_name'] = htmlspecialchars(trim(Request::get('user_full_name', '')));
        $data['user_email'] = htmlspecialchars(trim(Request::get('user_email', '')));
        $data['user_phone'] = htmlspecialchars(trim(Request::get('user_phone', '')));

        if (isset($data['user_full_name']) && $data['user_full_name'] == '') {
            $error[] = 'Tên nhân viên không được bỏ trống';
        }

        $data['user_name'] = Request::get('user_name', '');
        $groupUser = $data['user_group'] = Request::get('user_group', array());
        if ($groupUser) {
            $strGroupUser = implode(',', $groupUser);
            $dataInsert['user_group'] = $strGroupUser;
        }
        if (empty($error)) {
            //Insert dữ liệu
            $dataInsert['user_email'] = $data['user_email'];
            $dataInsert['user_phone'] = $data['user_phone'];
            $dataInsert['user_full_name'] = $data['user_full_name'];
            $dataInsert['user_status'] = (int)$data['user_status'];
            $dataInsert['user_edit_id'] = User::user_id();
            $dataInsert['user_edit_name'] = User::user_name();
            $dataInsert['user_updated'] = time();
            if (User::updateUser($id, $dataInsert)) {
                return Redirect::route('admin.user_view');
            } else {
                $error[] = 'Lỗi truy xuất dữ liệu';;
            }
        }
        $arrGroupUser = GroupUser::getListGroupUser();
        $this->layout->content = View::make('admin.User.edit')
            ->with('error', $error)
            ->with('data', $data)
            ->with('arrStatus', $this->arrStatus)
            ->with('arrGroupUser', $arrGroupUser);
    }

    public function changePassInfo($ids)
    {
        $id = base64_decode($ids);
        $user = User::user_login();
        if (!$this->is_root && !in_array($this->permission_change_pass, $this->permission) && (int)$id !== (int)$user['user_id']) {
            return Redirect::route('admin.dashboard');
        }
        $this->layout->content = View::make('admin.User.change')
            ->with('id', $id)
            ->with('is_root', $this->is_root)
            ->with('permission_change_pass', in_array($this->permission_change_pass, $this->permission) ? 1 : 0);
    }

    public function changePass($ids)
    {
        $id = base64_decode($ids);
        $user = User::user_login();
        //check permission
        if (!$this->is_root && !in_array($this->permission_change_pass, $this->permission) && (int)$id !== (int)$user['user_id']) {
            return Redirect::route('admin.dashboard');
        }

        $error = array();
        $old_password = Request::get('old_password', '');
        $new_password = Request::get('new_password', '');
        $confirm_new_password = Request::get('confirm_new_password', '');
        if(!in_array($this->permission_change_pass, $this->permission)){
            $user_byId = User::getUserById($id);
            if($old_password == ''){
                $error[] = 'Bạn chưa nhập mật khẩu hiện tại';
            }
            if(User::encode_password($old_password) !== $user_byId->user_password ){
                $error[] = 'Mật khẩu hiện tại không chính xác';
            }
        }
        if ($new_password == '') {
            $error[] = 'Bạn chưa nhập mật khẩu mới';
        } elseif (strlen($new_password) < 5) {
            $error[] = 'Mật khẩu quá ngắn';
        }
        if ($confirm_new_password == '') {
            $error[] = 'Bạn chưa xác nhận mật khẩu mới';
        }
        if ($new_password != '' && $confirm_new_password != '' && $confirm_new_password !== $new_password) {
            $error[] = 'Mật khẩu xác nhận không chính xác';
        }
        if (empty($error)) {
            //Insert dữ liệu
            if (User::updatePassword($id, $new_password)) {
                if((int)$id !== (int)$user['user_id']){
                    return Redirect::route('admin.dashboard');
                }else{
                    return Redirect::route('admin.user_view');
                }
            } else {
                $error[] = 'Không update được dữ liệu';
            }
        }

        $this->layout->content = View::make('admin.User.change')
            ->with('id', $id)
            ->with('is_root', $this->is_root)
            ->with('error', $error);
    }

    public function remove($id){
        $data['success'] = 0;
        if(!$this->is_root && !in_array($this->permission_remove, $this->permission)){
            return Response::json($data);
        }
        $user = User::find($id);
        if($user){
            if(User::remove($user)){
                $data['success'] = 1;
            }
        }
        return Response::json($data);
    }

}