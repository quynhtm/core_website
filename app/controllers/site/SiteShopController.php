<?php

class SiteShopController extends BaseSiteController
{
    public function __construct(){
        parent::__construct();
        FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
    }

    private $str_field_product_get = 'product_id,product_name,category_id,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop,is_block';//cac truong can lay

    /***************************************************************************************************
     * Trang chủ của shop
     ***************************************************************************************************
     */
    public function shopIndex($shop_id = 0){
		
    	FunctionLib::site_css('lib/bxslider/bxslider.css', CGlobal::$POS_HEAD);
    	FunctionLib::site_js('lib/bxslider/bxslider.js', CGlobal::$POS_END);
    	$this->header();
    	
    	$arrChildCate = $user_shop = $product = $arrBannerSlider = $arrBannerLeft = array();
        $productSellOff = $productHot = array();
    	$paging = '';
        $user_shop = UserShop::getByID($shop_id);
    	if($user_shop && sizeof($user_shop) > 0){
            //check shop thỏa mãn thì đi tiếp
            if($user_shop->shop_status != CGlobal::status_show){
                return Redirect::route('site.page404');
            }
           
            //seo
            $meta_title = $meta_keywords = $meta_description = $user_shop->shop_name.'-'.CGlobal::web_name;
            $meta_img = '';
            if($user_shop->shop_logo != '' && $user_shop->is_shop == CGlobal::SHOP_VIP){
            	$meta_img = ThumbImg::getImageThumb(CGlobal::FOLDER_LOGO_SHOP, $user_shop->shop_id, $user_shop->shop_logo, CGlobal::sizeImage_450, '', true, CGlobal::type_thumb_image_banner, false);
            }
            FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);

            //danh muc cua shop
            $arrChildCate = UserShop::getCategoryShopById($user_shop->shop_id);

            //dung cho shop VIP
            $totalProductHot = 0;
            if($user_shop->is_shop == CGlobal::SHOP_VIP){
                //sản phẩm giảm giá
                $search1['user_shop_id'] = $shop_id;
                $search1['product_is_hot'] = CGlobal::PRODUCT_SELLOFF;
                $limit1 = CGlobal::number_show_8;
                $productSellOff = Product::getProductForSite($search1, $limit1, 0, $totalProductHot);

                //sản phẩm nổi bật
                $search2['user_shop_id'] = $shop_id;
                $search2['product_is_hot'] = CGlobal::PRODUCT_HOT;
                $productHot = Product::getProductForSite($search2, $limit1, 0, $totalProductHot);
            }

            //list danh sách sản phẩm btuong
            $search['user_shop_id'] = $shop_id;
            $search['product_is_hot'] = CGlobal::PRODUCT_NOMAL;
            $pageNo = (int) Request::get('page_no', 1);
            $limit = ($user_shop->is_shop == CGlobal::SHOP_VIP && $totalProductHot > 15) ? CGlobal::number_show_20 : CGlobal::number_show_40;
            $offset = ($pageNo - 1) * $limit;
            $total = 0;
            $pageScroll = CGlobal::num_scroll_page;
            $product = Product::getProductForSite($search, $limit, $offset,$total);
            $paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';

            //quảng cáo của shop
	    	$arrBannerSlider = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_BIG, CGlobal::BANNER_PAGE_HOME, 0, $shop_id);
	    	$arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_HOME, 0, 0);

            //cap nhat luot share neu co
            $codeShare = trim(Request::get('shop_share', ''));
            if($codeShare != ''){
                $string_1 = base64_decode($codeShare);
                $pos1 = strrpos($string_1, "_");
                $string_2 = substr($string_1, (strlen(CGlobal::code_shop_share) + 1), strlen($string_1));
                $string_3 = substr($string_2, 0, $pos1);
                $pos2 = strrpos($string_3, "_");
                $shopIdShare = (int)substr($string_3, 0, $pos2);

                if((int)$user_shop->shop_id === $shopIdShare){
                    $hostIp = Request::getClientIp(); //$ip = $_SERVER['REMOTE_ADDR'];
                    $shopShare = ShopShare::checkIpShareShop($user_shop->shop_id);
                    if(!in_array($hostIp,array_keys($shopShare))){
                        $shop_share = ShopShare::addData(array('shop_share_ip'=>$hostIp,'shop_share_time'=>time(),'shop_id'=>$user_shop->shop_id,'shop_name'=>$user_shop->shop_name));
                        if($shop_share){
                            //cap nhat user
                            $userShopUpdate['shop_number_share'] = $user_shop->shop_number_share + 1;
                            $userShopUpdate['number_limit_product'] = $user_shop->number_limit_product + 1;
                            UserShop::updateData($user_shop->shop_id, $userShopUpdate);
                            if(Session::has('user_shop')){
                                $userShop = UserShop::getByID($user_shop->shop_id);
                                Session::forget('user_shop');//xóa session
                                Session::put('user_shop', $userShop, 60*24);
                            }
                        }
                    }
                    //echo 'dã vào day'; die;
                }
            }
    	}else{
    		return Redirect::route('site.page404');
    	}
    	$this->layout->content = View::make('site.ShopSite.ShopHome')
    	->with('product',$product)
    	->with('productSellOff',$productSellOff)
    	->with('productHot',$productHot)
    	->with('arrChildCate',$arrChildCate)
    	->with('paging', $paging)
    	->with('user_shop', $user_shop)
    	->with('title', $user_shop->shop_name)
    	->with('arrBannerSlider', $arrBannerSlider)
    	->with('arrBannerLeft', $arrBannerLeft);
    	
    	$this->footer();

    }

    /***************************************************************************************************
     * Trang chủ list sản phẩm của shop
     ***************************************************************************************************
     */
    public function shopListProduct($shop_id = 0,$cat_id = 0){
        
    	FunctionLib::site_css('lib/bxslider/bxslider.css', CGlobal::$POS_HEAD);
    	FunctionLib::site_js('lib/bxslider/bxslider.js', CGlobal::$POS_END);
    	
    	$this->header();
       
        $arrChildCate = $user_shop = $product = $arrBannerSlider = $arrBannerLeft = $arrCatShow = array();
        $paging = '';
        
        if($shop_id > 0){
        	$user_shop = UserShop::getByID($shop_id);
        	if(sizeof($user_shop) != 0){
        		$arrChildCate = UserShop::getCategoryShopById($shop_id);
        		$arrCatShow = Category::getByID($cat_id);
        		$search['user_shop_id'] = $shop_id;
        		$search['category_id'] = $cat_id;
        		$pageNo = (int) Request::get('page_no', 1);
        		$limit = CGlobal::number_show_40;
        		$offset = ($pageNo - 1) * $limit;
        		$total = 0;
        		$pageScroll = CGlobal::num_scroll_page;
        		$product = Product::getProductForSite($search, $limit, $offset,$total);
        		$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
        		
        		$meta_title = $meta_keywords = $meta_description = $arrCatShow->category_name.'-'.CGlobal::web_name;
	        	if($user_shop->shop_logo != '' && $user_shop->is_shop == CGlobal::SHOP_VIP){
	            	$meta_img = ThumbImg::getImageThumb(CGlobal::FOLDER_LOGO_SHOP, $user_shop->shop_id, $user_shop->shop_logo, CGlobal::sizeImage_450, '', true, CGlobal::type_thumb_image_banner, false);
	            }
        		FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
        		
        	}
            $arrBannerSlider = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_BIG, CGlobal::BANNER_PAGE_LIST, 0, $shop_id);
        	$arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);
        }else{
        	return Redirect::route('site.page404');
        }
       
        $this->layout->content = View::make('site.ShopSite.ShopListProduct')
					            ->with('product',$product)
						    	->with('arrChildCate',$arrChildCate)
						    	->with('paging', $paging)
						    	->with('user_shop', $user_shop)
						    	->with('arrBannerSlider', $arrBannerSlider)
						    	->with('arrBannerLeft', $arrBannerLeft)
        						->with('arrCatShow', $arrCatShow)
        						->with('cat_id', $cat_id);
        			;
        $this->footer();
    }

    /***************************************************************************************************
     * Login và logout, đăng ký shop
     ***************************************************************************************************
     */
    public function shopLogin(){
        
    	$meta_title = $meta_keywords = $meta_description = 'Đăng nhập';
    	$meta_img = '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        if(sizeof($this->user) > 0){
            return Redirect::route('shop.adminShop');
        }
        $this->header();
        $error = '';
        $this->layout->content = View::make('site.ShopSite.ShopLogin')
            ->with('error',$error)
            ->with('user', $this->user);
        $this->footer();
    }
    public function login($url = ''){
        FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        $this->header();
        $user_shop = trim(Request::get('user_shop_login', ''));
        $password = trim(Request::get('password_shop_login', ''));
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
                            //cập nhật login
                            $dataUpdate['is_login'] = CGlobal::SHOP_ONLINE;
                            $dataUpdate['shop_time_login'] = time();
                            UserShop::updateData($userShop->shop_id,$dataUpdate);
                            Session::put('user_shop', $userShop, 60*24);

                            if ($url === '' || $url === 'login') {
                                //kiểm tra shop mới cho vào cập nhật thông tin shop
                                if(isset($userShop->shop_up_product) && $userShop->shop_up_product == 0){
                                    return Redirect::route('shop.inforShop');
                                }
                                //kiem tra co don hang cho vao page don hang
                                $countOrderNew = Order::countOrderOfShopId($userShop->shop_id);
                                return ($countOrderNew > 0)?Redirect::route('shop.listOrder'): Redirect::route('shop.adminShop');
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

        $this->layout->content = View::make('site.ShopSite.ShopLogin')
            ->with('error', $error);
        $this->footer();
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
        
    	$meta_title = $meta_keywords = $meta_description = 'Đăng ký';
    	$meta_img = '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        $this->header();
        //tỉnh thành
        $arrProvince = Province::getAllProvince();
        $optionProvince = FunctionLib::getOption(array(-1=>' ---Chọn tỉnh thành ----')+$arrProvince, -1);
        $this->layout->content = View::make('site.ShopSite.ShopRegister')
            ->with('error',array())
            ->with('optionProvince',$optionProvince)
            ->with('user', $this->user);
        $this->footer();
    }
    public function postShopRegister(){
        FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        $this->header();
        $dataSave = $error = array();

        $dataSave['user_shop'] = addslashes(Request::get('user_shop'));
        $dataSave['user_password'] = addslashes(Request::get('user_password'));
        $dataSave['rep_user_password'] = addslashes(Request::get('rep_user_password'));
        $dataSave['shop_province'] = (int)(Request::get('shop_province',-1));

        $dataSave['shop_phone'] = addslashes(Request::get('shop_phone'));
        $dataSave['shop_email'] = addslashes(Request::get('shop_email'));

        $error = $this->validUserInforShop($dataSave);
        if (empty($error)) {
            unset($dataSave['rep_user_password']);
            $dataSave['user_password'] = User::encode_password(trim($dataSave['user_password']));
            //gan co dinh 1 shop khi dang ky
            $dataSave['number_limit_product'] = CGlobal::SHOP_NUMBER_PRODUCT_FREE;
            $dataSave['is_shop'] = CGlobal::SHOP_FREE;
            $dataSave['shop_status'] = CGlobal::status_show;
            $dataSave['shop_created'] = time();

            //login luon
            $dataSave['is_login'] = CGlobal::SHOP_ONLINE;
            $dataSave['shop_time_login'] = time();

            $shop_id = UserShop::addData($dataSave);
            if($shop_id > 0) {
                $userShop = UserShop::find($shop_id);
                if($userShop){
                    Session::put('user_shop', $userShop, 60*24);
                    return Redirect::route('shop.adminShop');
                }
            }
        }
        //tỉnh thành
        $arrProvince = Province::getAllProvince();
        $optionProvince = FunctionLib::getOption(array(-1=>' ---Chọn tỉnh thành ----')+$arrProvince, $dataSave['shop_province']);

        $this->layout->content = View::make('site.ShopSite.ShopRegister')
            ->with('error',$error)
            ->with('optionProvince',$optionProvince)
            ->with('data',$dataSave)
            ->with('user', $this->user);
        $this->footer();
    }
    //Lay lai mat khau
    public function shopForgetPass(){
    	
    	$meta_title = $meta_keywords = $meta_description = 'Quên mật khẩu';
    	$meta_img= '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
    	$this->header();
    	$this->layout->content = View::make('site.ShopSite.ShopForgetPass')
    	->with('error',array());
    	$this->footer();
    }
    public function postShopForgetPass(){
    	
    	FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        $this->header();
        $dataSave = $error = array();
        $message = '';

        $dataSave['user_shop'] = addslashes(Request::get('user_shop'));
        $dataSave['shop_email'] = addslashes(Request::get('shop_email'));
		if($dataSave['user_shop'] == ''){
			$error[] = 'Tên đăng nhập shop không được trống!';
		}
		//Check email
        $checkEmail = FunctionLib::checkRegexEmail(trim($dataSave['shop_email']));
        if(!$checkEmail){
        	$error[] = 'Email không đúng định dạng!';
        }
        //Check user exists
        $getUser = UserShop::getUserShopByEmail($dataSave['shop_email']);
        if(sizeof($getUser) != 0){
        	$user_shop = $getUser->user_shop;
        	if($user_shop != $dataSave['user_shop']){
        		$error[] = 'Không đúng tên đăng nhập hoặc email đăng ký shop!';
        	}
        }
        
        if (empty($error)) {
        	$randomString = FunctionLib::randomString(5);
        	$hash_pass = User::encode_password($randomString);
        	$dataUpdate = array(
        		'user_password'=>$hash_pass
        	);
        	UserShop::updateData($getUser->shop_id, $dataUpdate);
        	//Send mail
        	$data = array(
        			'user_shop'=>$dataSave['user_shop'],
        			'user_password'=>$randomString,
        			'phone_support'=>CGlobal::phoneSupport,
        			'web_name'=>CGlobal::web_name,
        	);
        	$emails = [$dataSave['shop_email'], 'shoponlinecuatui@gmail.com'];
        	Mail::send('emails.ForgetPass', array('data'=>$data), function($message) use ($emails){
        		$message->to($emails, 'UserShop')
        				->subject('Thông tin mật khẩu mới'.date('d/m/Y h:i',  time()));
        	});
        	$message = 'Hệ thống đã gửi cho bạn 1 email, bạn vui lòng kiểm tra mail để khôi phục mật khẩu mới.';
            unset($_POST);
            $dataSave = array();
        }
        $this->layout->content = View::make('site.ShopSite.ShopForgetPass')
            ->with('error',$error)
            ->with('data',$dataSave)
            ->with('message',$message);
        $this->footer();
    }
    private function validUserInforShop($data=array()) {
        $error = array();
        if(!empty($data)) {
            if(isset($data['user_shop']) && trim($data['user_shop']) == '') {
                $error[] = 'Tên đăng nhập không được bỏ trống';
            }else{
                $checUserShop = UserShop::getUserByName(trim($data['user_shop']));
                if($checUserShop){
                    $error[] = 'Đã tồn tại tên đăng nhập này! Hãy nhập lại';
                }
            }
            if(isset($data['shop_phone']) && trim($data['shop_phone']) == '') {
                $error[] = 'Điện thoại liên hệ không được bỏ trống';
            }else{
                $checUserShop = UserShop::getUserShopByPhone(trim($data['shop_phone']));
                if($checUserShop){
                    $error[] = 'Điện thoại này đã sử dụng! Hãy nhập lại';
                }
            }
            if(isset($data['shop_email']) && trim($data['shop_email']) == '') {
                $error[] = 'Email không được bỏ trống';
            }else{
                $checkEmail = FunctionLib::checkRegexEmail(trim($data['shop_email']));
                if($checkEmail){
                    $checUserShop = UserShop::getUserShopByEmail(trim($data['shop_email']));
                    if($checUserShop){
                        $error[] = 'Email này đã sử dụng! Hãy nhập lại';
                    }
                }else{
                    $error[] = 'Email không đúng định dạng! Hãy nhập lại';
                }
            }
            if(isset($data['shop_province']) && (int)$data['shop_province'] <= 0) {
                $error[] = 'Bạn chưa chọn tỉnh thành của shop';
            }

            if(isset($data['user_password']) && trim($data['user_password']) == '') {
                $error[] = 'Bạn chưa nhập password';
            }else{
                if(isset($data['rep_user_password']) && $data['rep_user_password'] == '') {
                    $error[] = 'Bạn chưa nhập lại password';
                }elseif(strcmp($data['user_password'],$data['rep_user_password']) != 0){
                    $error[] = 'Bạn nhập lại password chưa đúng';
                }
            }
            return $error;
        }
        return $error;
    }

}

