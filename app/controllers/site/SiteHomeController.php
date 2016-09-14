<?php

class SiteHomeController extends BaseSiteController
{
    public function __construct(){
        parent::__construct();
        FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
    }

    private $str_field_product_get = 'product_id,product_name,category_id,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop,is_block';//cac truong can lay
    //trang chu
    public function index(){
    	
    	FunctionLib::site_css('lib/bxslider/bxslider.css', CGlobal::$POS_HEAD);
    	FunctionLib::site_js('lib/bxslider/bxslider.js', CGlobal::$POS_END);
    	
    	$this->header();
        /**
         * list SP cua shop VIP
         * */
        $parentCategoryId = (int) Request::get('parent_category_id',0);
        $limit = CGlobal::number_show_30;
        $total = $offset = 0;
        if($parentCategoryId > 0){
            $arrChildCate = Category::getAllChildCategoryIdByParentId($parentCategoryId);
            if(sizeof($arrChildCate) > 0){
                $searchVip['category_id'] = array_keys($arrChildCate);
            }
        }
        $searchVip['is_shop'] = CGlobal::SHOP_VIP;
        $searchVip['field_get'] = $this->str_field_product_get;
        $dataProVip = Product::getProductForSite($searchVip, $limit, $offset,$total);
       
        /**
         * //list sản phẩm THUONG - FREE
         */
        $limit = $offset = CGlobal::number_show_15;
        $searchFree['is_shop'] = array( CGlobal::SHOP_NOMAL, CGlobal::SHOP_FREE );
        $searchFree['category_id'] = 0;
        $searchFree['field_get'] = $this->str_field_product_get;
        $dataProFree = Product::getProductForSite($searchFree, $limit, $offset, $total);

        //list danh mục cha
        $listParentCate = Category::getAllParentCategoryId();
        
        //Menu category
        $dataCategory = Category::getCategoriessAll();
        $arrCategory = $this->getTreeCategory($dataCategory);
        
        //Slider
        $arrSlider = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_BIG, CGlobal::BANNER_PAGE_HOME, 0, 0);
        $arrSliderRight1 = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_RIGHT_1, CGlobal::BANNER_PAGE_HOME, 0, 0);
        $arrSliderRight2 = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_RIGHT_2, CGlobal::BANNER_PAGE_HOME, 0, 0);
       
        $user_shop = array();
        $this->layout->content = View::make('site.SiteLayouts.Home')
            ->with('dataProVip',$dataProVip)
            ->with('dataProFree',$dataProFree)
            ->with('listParentCate',$listParentCate)
            ->with('user_shop', $user_shop)
            ->with('arrCategory', $arrCategory)
        	->with('arrSlider', $arrSlider)
	        ->with('arrSliderRight1', $arrSliderRight1)
	        ->with('arrSliderRight2', $arrSliderRight2);
        $this->footer();
    }

    //trang list sản phẩm mới
    public function listProductNew(){
        $this->header();

        $product = array();
        $pageNo = (int) Request::get('page_no', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($pageNo - 1) * $limit;
        $total = 0;
        $pageScroll = CGlobal::num_scroll_page;
        $pageNo = (int) Request::get('page_no', 1);
        $product = Product::getProductForSite($this->str_field_product_get, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, array()) : '';

        $arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);
 
        $this->layout->content = View::make('site.SiteLayouts.listProductNew')
            ->with('product',$product)
        	->with('paging', $paging)
        	->with('arrBannerLeft', $arrBannerLeft);

        $this->footer();
    }

    //trang tìm kiếm
    public function searchProduct(){
        $this->header();
        $this->layout->content = View::make('site.SiteLayouts.searchProduct');
        $this->footer();
    }

    //trang danh sách san pham theo danh mục
    public function listProduct($cat_id){
        $this->header();

        $product = array();
        $categoryParrentCat = $arrChildCate = array();
        $paging = '';
        if($cat_id > 0){
        	$categoryParrentCat = Category::getByID($cat_id);
            if($categoryParrentCat){
                //Get child cate in parent cate
            	$arrChildCate = Category::getAllChildCategoryIdByParentId($cat_id);

            	if($categoryParrentCat->category_parent_id == 0){
                    $search['category_parent_id'] = $categoryParrentCat->category_id;
                }else{
                    $search['category_id'] = $categoryParrentCat->category_id;
                }
                $search['category_name'] = FunctionLib::safe_title($categoryParrentCat->category_name);
                $pageNo = (int) Request::get('page_no', 1);
                $limit = CGlobal::number_show_20;
                $offset = ($pageNo - 1) * $limit;
                $total = 0;
                $pageScroll = CGlobal::num_scroll_page;
                $pageNo = (int) Request::get('page_no', 1);
                $product = Product::getProductForSite($search, $limit, $offset,$total);
                $paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
            }
        }

       $arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);
       //FunctionLib::debug($arrBannerLeft);
        $this->layout->content = View::make('site.SiteLayouts.ListProduct')
            ->with('product',$product)
            ->with('arrChildCate',$arrChildCate)
        	->with('categoryParrentCat', $categoryParrentCat)
        	->with('paging', $paging)
        	->with('arrBannerLeft', $arrBannerLeft);

        $this->footer();
    }
    public function detailProduct($cat_name, $pro_id, $pro_name){
        
    	FunctionLib::site_css('lib/slickslider/slick.css', CGlobal::$POS_HEAD);
    	FunctionLib::site_js('lib/slickslider/slick.min.js', CGlobal::$POS_END);

    	$this->header();
        $product = array();
        $user_shop = array();
        if($pro_id > 0){
            $product = Product::getProductByID($pro_id);
            //FunctionLib::debug($product);
            $user_shop = UserShop::getByID($product->user_shop_id);
            if ($product) {
                //check xem sản phẩm có khi khóa hay ẩn hay không
                if($product->product_status == CGlobal::status_hide || $product->is_block == CGlobal::PRODUCT_BLOCK){
                    return Redirect::route('site.Error');
                }
                $url = URL::current();
                $link_detail = FunctionLib::buildLinkDetailProduct($product->product_id,$product->product_name,$product->category_name);
                if ($url != $link_detail) {
                    return Redirect::to($link_detail);
                }
                $this->layout->title = $product->product_name . ' - shopcuatui.com.vn';
                $this->layout->title_seo = $product->product_name;
                $this->layout->url_seo = $link_detail;
                $this->layout->img_seo = '';
                $this->layout->des_seo = strip_tags($product->product_sort_desc);
            }
        }
        //get product hot
      	$limit = CGlobal::number_show_5;
    	$total = $offset = 0;
    	$search['field_get'] = $this->str_field_product_get;
    	$dataProVip = Product::getProductForSite($search, $limit, $offset,$total);
    	
        $this->layout->content = View::make('site.SiteLayouts.DetailProduct')
            ->with('product',$product)
            ->with('user_shop', $user_shop)
        	->with('dataProVip',$dataProVip);
        $this->footer();
    }

    //trang list tin tuc
    public function homeNew(){
    	$this->header();
        $dataNew = array();
        //thong tin tim kiem
        $pageNo = (int) Request::get('page_no',1);
        $limit = 15;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;
		
        $search['news_title'] = addslashes(Request::get('news_title', ''));
        $search['news_status'] = CGlobal::status_show;
        $search['field_get'] = 'news_id,news_title,news_desc_sort,news_image';//cac truong can lay
        
        $dataNew = News::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        
        //Star product hot
        $str_field_get = 'product_id,product_name,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop';//cac truong can lay
        $parentCategoryId = (int) Request::get('parent_category_id',0);
        $limit = CGlobal::number_show_5;
        $total = $offset = 0;
        if($parentCategoryId > 0){
        	$arrChildCate = Category::getAllChildCategoryIdByParentId($parentCategoryId);
        	if(sizeof($arrChildCate) > 0){
        		$searchVip['category_id'] = array_keys($arrChildCate);
        	}
        }
        $searchVip['is_shop'] = CGlobal::SHOP_VIP;
        $searchVip['field_get'] = $str_field_get;
        $dataProVip = Product::getProductForSite($searchVip, $limit, $offset,$total);
        //End product hot
        
        
        $this->layout->content = View::make('site.SiteLayouts.ListNews')
            ->with('dataNew',$dataNew)
            ->with('paging', $paging)
            ->with('dataProVip',$dataProVip);
        $this->footer();
    }
    //trang chi tiet tin tuc
    public function detailNew($new_id, $new_name){

        $this->header();
        $dataNew = $dataNewsSame = array();
        $user_shop = array();
        //get news detail
        if($new_id > 0) {
            $dataNew = News::getNewByID($new_id);
            //get news same
            if($dataNew != null){
                $dataField['field_get'] = 'news_id,news_title,news_desc_sort,news_content,news_category';
                $dataNewsSame = News::getSameNews($dataField, $dataNew->news_category, $new_id, 10);
            }
        }

        //Star product hot
        $str_field_get = 'product_id,product_name,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop';//cac truong can lay
        $parentCategoryId = (int) Request::get('parent_category_id',0);
        $limit = CGlobal::number_show_5;
        $total = $offset = 0;
        if($parentCategoryId > 0){
        	$arrChildCate = Category::getAllChildCategoryIdByParentId($parentCategoryId);
        	if(sizeof($arrChildCate) > 0){
        		$searchVip['category_id'] = array_keys($arrChildCate);
        	}
        }
        $searchVip['is_shop'] = CGlobal::SHOP_VIP;
        $searchVip['field_get'] = $str_field_get;
        $dataProVip = Product::getProductForSite($searchVip, $limit, $offset,$total);
        //End product hot
        
        $this->layout->content = View::make('site.SiteLayouts.DetailNews')
            ->with('dataNew',$dataNew)
            ->with('dataNewsSame',$dataNewsSame)
            ->with('dataProVip',$dataProVip)
            ->with('user_shop', $user_shop);
        $this->footer();
    }


    /***************************************************************************************************
     * Page lien quan tới shop
     ***************************************************************************************************
     */
    /**
     * Trang chủ của shop
     */
    public function shopIndex($shop_id = 0){
		
    	FunctionLib::site_css('lib/bxslider/bxslider.css', CGlobal::$POS_HEAD);
    	FunctionLib::site_js('lib/bxslider/bxslider.js', CGlobal::$POS_END);
    	$this->header();
    	
    	$arrChildCate = $user_shop = $product = $arrBannerSlider = $arrBannerLeft = array();
    	$paging = '';
    	
    	if($shop_id > 0){
    		$user_shop = UserShop::getByID($shop_id);
	    	if(sizeof($user_shop) != 0){
	    		$arrChildCate = UserShop::getCategoryShopById($shop_id);
	    		$search['user_shop_id'] = $shop_id;
	    		$pageNo = (int) Request::get('page_no', 1);
	    		$limit = CGlobal::number_show_20;
	    		$offset = ($pageNo - 1) * $limit;
	    		$total = 0;
	    		$pageScroll = CGlobal::num_scroll_page;
	    		$pageNo = (int) Request::get('page_no', 1);
	    		$product = Product::getProductForSite($search, $limit, $offset,$total);
	    		$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
	    	}
	    	$arrBannerSlider = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_BIG, CGlobal::BANNER_PAGE_LIST, 0, $shop_id);
	    	$arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);
    	}else{
    		return Redirect::route('site.page404');
    	}
    	$this->layout->content = View::make('site.SiteLayouts.ShopHome')
    	->with('product',$product)
    	->with('arrChildCate',$arrChildCate)
    	->with('paging', $paging)
    	->with('user_shop', $user_shop)
    	->with('arrBannerSlider', $arrBannerSlider)
    	->with('arrBannerLeft', $arrBannerLeft);
    	
    	$this->footer();

    }
    /**
     * Trang list sản phẩm của shop
     */
    public function shopListProduct($shop_id = 0,$cat_id = 0){
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
        		$limit = CGlobal::number_show_20;
        		$offset = ($pageNo - 1) * $limit;
        		$total = 0;
        		$pageScroll = CGlobal::num_scroll_page;
        		$pageNo = (int) Request::get('page_no', 1);
        		$product = Product::getProductForSite($search, $limit, $offset,$total);
        		$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
        	}
        	$arrBannerSlider = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_BIG, CGlobal::BANNER_PAGE_LIST, 0, $shop_id);
        	$arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);
        }else{
        	return Redirect::route('site.page404');
        }
       
        $this->layout->content = View::make('site.SiteLayouts.shopListProduct')
					            ->with('product',$product)
						    	->with('arrChildCate',$arrChildCate)
						    	->with('paging', $paging)
						    	->with('user_shop', $user_shop)
						    	->with('arrBannerSlider', $arrBannerSlider)
						    	->with('arrBannerLeft', $arrBannerLeft)
        						->with('arrCatShow', $arrCatShow);
        $this->footer();
    }


    /**
     * Login và logout, đăng ký shop
     */
    public function shopLogin(){
        FunctionLib::site_css('frontend/css/login.css', CGlobal::$POS_HEAD);
        if(sizeof($this->user) > 0){
            return Redirect::route('shop.adminShop');
        }
        $this->header();
        $error = '';
        $this->layout->content = View::make('site.ShopLayouts.ShopLogin')
            ->with('error',$error)
            ->with('user', $this->user);
        $this->footer();
    }
    public function login($url = ''){
        FunctionLib::site_css('frontend/css/login.css', CGlobal::$POS_HEAD);
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
        FunctionLib::site_css('frontend/css/register.css', CGlobal::$POS_HEAD);
        $this->header();
        //tỉnh thành
        $arrProvince = Province::getAllProvince();
        $optionProvince = FunctionLib::getOption(array(-1=>' ---Chọn tỉnh thành ----')+$arrProvince, -1);
        $this->layout->content = View::make('site.ShopLayouts.ShopRegister')
            ->with('error',array())
            ->with('optionProvince',$optionProvince)
            ->with('user', $this->user);
        $this->footer();
    }
    public function postShopRegister(){
        FunctionLib::site_css('frontend/css/register.css', CGlobal::$POS_HEAD);
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
            $dataSave['shop_created'] = time();

            //login luon
            $dataSave['shop_time_login'] = CGlobal::SHOP_ONLINE;
            $dataSave['is_login'] = time();

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

        $this->layout->content = View::make('site.ShopLayouts.ShopRegister')
            ->with('error',$error)
            ->with('optionProvince',$optionProvince)
            ->with('data',$dataSave)
            ->with('user', $this->user);
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

	public function page404(){
    	$this->header();

        $limit = CGlobal::number_show_30;
        $total = $offset = 0;
        $search['field_get'] = $this->str_field_product_get;
        $dataProVip = Product::getProductForSite($search, $limit, $offset,$total);
    	
    	$this->layout->content = View::make('site.SiteLayouts.page404')
            ->with('dataProVip',$dataProVip);

    	$this->footer();
    }


    
    //Ajax load item sub category home
    public function ajaxLoadItemSubCategory(){
    	if(empty($_POST)){
    		return Redirect::route('site.home');
    	}
        $parentCategoryId = (int)Request::get('dataCatId');
    	$type = addslashes(Request::get('dataType'));
   		if($parentCategoryId > 0 && $type != ''){
   			/*$offset=0;
   			if($type == 'vip'){
   				$search['is_shop'] = CGlobal::SHOP_VIP;
   				$limit = CGlobal::number_show_30;
   			}else{
   				$search['is_shop'] = CGlobal::SHOP_NOMAL;
   				$limit = CGlobal::number_show_15;
   			}
   			$search['category_id'] = $catid;
   			$search['field_get'] = $this->str_field_product_get;
   			$data = Product::getProductForSite($search, $limit, $offset,$total);*/


            $limit = ($type == 'vip')? CGlobal::number_show_30 : CGlobal::number_show_15;
            $total = $offset = 0;
            if($parentCategoryId > 0){
                $arrChildCate = Category::getAllChildCategoryIdByParentId($parentCategoryId);
                if(sizeof($arrChildCate) > 0){
                    $search['category_id'] = array_keys($arrChildCate);
                }
            }
            $search['is_shop'] = ($type == 'vip')? CGlobal::SHOP_VIP: array(CGlobal::SHOP_NOMAL,CGlobal::SHOP_FREE);
            $search['field_get'] = $this->str_field_product_get;
            $data = Product::getProductForSite($search, $limit, $offset,$total);
   			
   			return View::make('site.SiteLayouts.AjaxLoadItemSubCate')->with('data', $data)->with('catid', $parentCategoryId);
   			die;
   		}
    }
}

