<?php

class SiteHomeController extends BaseSiteController
{
    public function __construct()
    {
        parent::__construct();
    }

    //trang chu
    public function index(){
        $this->header();
        $dataShow = array();
        $user_shop = array();
        $this->layout->content = View::make('site.SiteLayouts.Home')
            ->with('data',$dataShow)
            ->with('data',$dataShow)
            ->with('user_shop', $user_shop);
        $this->footer();
    }

    //trang chi tiet san pham
    public function detailProduct($cat_name, $pro_id, $pro_name){
        $this->header();
        $product = array();
        $user_shop = array();
        if($pro_id > 0){
            $product = Product::getProductByID($pro_id);
            //FunctionLib::debug($product);
            if ($product) {
                //check s?n ph?m có b? khóa hay ?n khong
                if($product->product_status == CGlobal::status_hide || $product->product_status == CGlobal::status_block){
                    return Redirect::route('site.Error');
                }
                $url = URL::current();
                $link_detail = URL::route('site.detailProduct', array('cat' => $product->category_name, 'name' => strtolower(FunctionLib::safe_title($product->product_name)), 'id' => $product->product_id));
                if ($url != $link_detail) {
                    return Redirect::to($link_detail);
                }
                $this->layout->title = $product->product_name . ' - Muachung Plaza';
                $this->layout->title_seo = $product->product_name;
                $this->layout->url_seo = $link_detail;
                $this->layout->img_seo = '';
                $this->layout->des_seo = strip_tags($product->product_sort_desc);
            }
        }

        $this->layout->content = View::make('site.SiteLayouts.DetailProduct')
            ->with('product',$product)
            ->with('user_shop', $user_shop);
        $this->footer();
    }

    //trang chi tiet tin tuc
    public function detailNew($cat_name, $new_id, $pro_name){
        
    	FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
    	
    	$this->header();
        $dataNew = array();
        $user_shop = array();
        //get thong tin c?a bài vi?t
        if($new_id > 0) {
            $dataNew = News::getNewByID($new_id);
            //FunctionLib::debug($dataNew);
        }

        $this->layout->content = View::make('site.SiteLayouts.DetailNews')
            ->with('dataNew',$dataNew)
            ->with('user_shop', $user_shop);
        $this->footer();
    }
    //trang list tin tuc
    public function listNew($news_category = 0){
        
    	FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
    	
    	$this->header();
        $dataNew = array();
        $user_shop = array();

        //thong tin tìm ki?m
        $pageNo = (int) Request::get('page_no',1);
        $limit = 15;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['news_category'] = (int)$news_category;
        $search['news_status'] = CGlobal::status_show;
        $search['field_get'] = 'news_id,news_title,news_desc_sort,news_image';//cac truong can lay
        $dataNew = News::searchByCondition($search, $limit, $offset,$total);
        //FunctionLib::debug($dataNew);

        $this->layout->content = View::make('site.SiteLayouts.ListNews')
            ->with('dataNew',$dataNew)
            ->with('user_shop', $user_shop);
        $this->footer();
    }


    //trang 404
    public function pageError(){
        $this->header();
        $dataShow = array();
        $this->layout->content = View::make('site.SiteLayouts.PageError')
            ->with('data',$dataShow)
            ->with('user', $this->user);
        $this->footer();
    }

    //trang login
    public function shopLogin(){
        FunctionLib::site_css('frontend/css/login.css', CGlobal::$POS_HEAD);
        $this->header();
        $error = '';
        $this->layout->content = View::make('site.ShopLayouts.ShopLogin')
            ->with('error',$error)
            ->with('user', $this->user);
        $this->footer();
    }
    public function login($url = ''){
        FunctionLib::site_css('frontend/css/login.css', CGlobal::$POS_HEAD);
        $user_shop = Request::get('user_shop_login', '');
        $password = Request::get('password_shop_login', '');
        $error = '';
        if ($user_shop != '' && $password != '') {
            if (strlen($user_shop) < 3 || strlen($user_shop) > 50 || preg_match('/[^A-Za-z0-9_\.@]/', $user_shop) || strlen($password) < 5) {
                $error = 'Không tồn tại tên đăng nhập!';
            } else {
                $userShop = UserShop::getUserByName($user_shop);
                if ($userShop !== NULL) {
                    if ($userShop->shop_status == CGlobal::status_hide || $userShop->shop_status == CGlobal::status_block) {
                        $error = 'Tài khoản bị khóa!';
                    } elseif ($userShop->shop_status == CGlobal::status_show) {
                        if ($userShop->user_password == User::encode_password($password)) {
                            Session::put('user_shop', $userShop, 60*24);
                            //cập nhật login
                            $dataUpdate['is_login'] = CGlobal::SHOP_ONLINE;
                            $dataUpdate['shop_time_login'] = time();
                            UserShop::updateData($userShop->shop_id,$dataUpdate);

                            if ($url === '' || $url === 'login') {
                                return Redirect::route('shop.adminShop');
                            } else {
                                return Redirect::to(self::buildUrlDecode($url));
                            }

                        } else {
                            $error = 'Mật khẩu không đúng!';
                        }
                    }
                } else {
                    $error = 'Không tồn tại shop này trên hệ thống!';
                }
            }
        } else {
            $error = 'Chưa nhập thông tin đăng nhập!';
        }

        $this->layout->content = View::make('site.ShopLayouts.ShopLogin')
            ->with('error', $error);
    }
    public function shopLogout(){
        if (Session::has('user_shop')) {
            //cap nhat thoi gian logout
            $userShop = Session::get('user_shop');
            $dataUpdate['is_login'] = CGlobal::SHOP_OFFLINE;
            $dataUpdate['shop_time_logout'] = time();
            UserShop::updateData($userShop->shop_id,$dataUpdate);

            Session::forget('user_shop');//xóa session
        }
        return Redirect::route('site.shopLogin', array('url' => self::buildUrlEncode(URL::previous())));
    }
    //trang register
    public function shopRegister(){
        FunctionLib::site_css('frontend/css/register.css', CGlobal::$POS_HEAD);
        $this->header();
        $dataShow = array();
        $this->layout->content = View::make('site.ShopLayouts.shopRegister')
            ->with('data',$dataShow)
            ->with('user', $this->user);
        $this->footer();
    }

}

