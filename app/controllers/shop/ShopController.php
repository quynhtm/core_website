<?php

class ShopController extends BaseShopController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * *************************************************************************************************************
     * Phan login/logout cua shop
     * *************************************************************************************************************
     */
    public function loginInfo($url = '')
    {
        if (Session::has('user')) {
            if ($url === '' || $url === 'login') {
                return Redirect::route('admin.dashboard');
            } else {
                return Redirect::to(self::buildUrlDecode($url));
            }
        } else {
            $this->layout->content = View::make('admin.User.login');
        }
    }

    public function login($url = '')
    {
        $username = Request::get('user_name', '');
        $password = Request::get('user_password', '');
        $error = '';
        if ($username != '' && $password != '') {
            if (strlen($username) < 3 || strlen($username) > 50 || preg_match('/[^A-Za-z0-9_\.@]/', $username) || strlen($password) < 5) {
                $error = 'Không t?n t?i tên ??ng nh?p!';
            } else {
                $user = User::getUserByName($username);
                if ($user !== NULL) {
                    if ($user->user_status == 0) {
                        $error = 'Tài kho?n b? khóa!';
                    } elseif ($user->user_status == 1) {
                        if ($user->user_password == User::encode_password($password)) {
                            $permission_code = array();
                            $group = explode(',', $user->user_group);
                            if ($group) {
                                $permission = GroupUserPermission::getListPermissionByGroupId($group);
                                if ($permission) {
                                    foreach ($permission as $v) {
                                        $permission_code[] = $v->permission_code;
                                    }
                                }
                            }
                            $data = array(
                                'user_id' => $user->user_id,
                                'user_name' => $user->user_name,
                                'user_full_name' => $user->user_full_name,
                                'user_email' => $user->user_email,
                                'user_employee_id' => $user->user_employee_id,
                                'user_is_admin' => $user->user_is_admin,
                                'user_permission' => $permission_code
                            );
                            Session::put('user', $data, 60*24);
                            User::updateLogin($user);
                            //echo FunctionLib::buildUrlDecode($url); die('xxx');
                            if ($url === '' || $url === 'login') {
                                return Redirect::route('admin.dashboard');
                            } else {
                                return Redirect::to(self::buildUrlDecode($url));
                            }
                        } else {
                            $error = 'M?t kh?u không ?úng!';
                        }
                    }
                } else {
                    $error = 'Không t?n t?i tên ??ng nh?p!';
                }
            }
        } else {
            $error = 'Ch?a nh?p thông tin ??ng nh?p!';
        }

        $this->layout->content = View::make('admin.User.login')
            ->with('error', $error)->with('username', $username);
    }

    public function logout()
    {
        if (Session::has('user')) {
            // Session::forget('key');
            Session::forget('user');
        }
        return Redirect::route('admin.login', array('url' => self::buildUrlEncode(URL::previous())));
    }


    public function index(){
        $this->header();
        $dataShow = array();
        $this->layout->content = View::make('site.ShopLayouts.ShopHome')
            ->with('data',$dataShow)
            ->with('user', $this->user);
        $this->footer();
    }

}

