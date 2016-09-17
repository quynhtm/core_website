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
    	
    	$meta_title = $meta_keywords = $meta_description= 'Thời trang nam, thời trang nữ, thời trang trẻ em, phụ kiện thời trang, đồ gia dụng';
    	$meta_img= '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
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
    //Ajax load item sub category home
    public function ajaxLoadItemSubCategory(){
        if(empty($_POST)){
            return Redirect::route('site.home');
        }
        $parentCategoryId = (int)Request::get('dataCatId');
        $type = addslashes(Request::get('dataType'));
        if($parentCategoryId > 0 && $type != ''){
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

    //trang list sản phẩm mới
    public function listProductNew(){
        
    	$meta_title = $meta_keywords = $meta_description= 'Sản phẩm mới';
    	$meta_img= '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
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
 
        $this->layout->content = View::make('site.SiteLayouts.ListProductNew')
            ->with('product',$product)
        	->with('paging', $paging)
        	->with('arrBannerLeft', $arrBannerLeft);

        $this->footer();
    }

    //trang tìm kiếm
    public function searchProduct(){
        
    	$meta_title = $meta_keywords = $meta_description= 'Tìm kiếm sản phẩm';
    	$meta_img= '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	$this->header();
        
        $catid = (int)Request::get('category_id', -1);
        $provinceid = (int)Request::get('shop_province', -1);
        
        $product = $arrCate = $arrProvince = array();
        $paging = '';
        $total = 0;
        if($catid>0 || $provinceid > 0){
        	$pageNo = (int) Request::get('page_no', 1);
        	$limit = CGlobal::number_show_20;
        	$offset = ($pageNo - 1) * $limit;
        	$pageScroll = CGlobal::num_scroll_page;
        	
        	$search['category_id'] = $catid;
        	$search['shop_province'] = $provinceid;
        	
        	$product = Product::getProductForSite($search, $limit, $offset,$total);
        	$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
        	
        	if($catid>0){
        		$arrCate = Category::getByID($catid);
        	}
        	if($provinceid>0){
        		$arrProvince = Province::getByID($provinceid);
        	}
        }
        
        $arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);
 
        $this->layout->content = View::make('site.SiteLayouts.searchProduct')
        ->with('product',$product)
        ->with('paging', $paging)
        ->with('total', $total)
        ->with('arrCate', $arrCate)
        ->with('arrProvince', $arrProvince)
        ->with('arrBannerLeft', $arrBannerLeft);
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
                $product = Product::getProductForSite($search, $limit, $offset,$total);
                $paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
                
                $meta_title = $meta_keywords = $meta_description = $categoryParrentCat->category_name;;
                $meta_img= '';
                FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
                
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
            if (sizeof($product) != 0) {
                //check xem sản phẩm có khi khóa hay ẩn hay không
                if($product->product_status == CGlobal::status_hide || $product->is_block == CGlobal::PRODUCT_BLOCK){
                    return Redirect::route('site.Error');
                }
                CGlobal::$pageTitle = $product->product_name.'-'.CGlobal::web_name;//title page
                $user_shop = UserShop::getByID($product->user_shop_id);
                
                $url = URL::current();
                $link_detail = FunctionLib::buildLinkDetailProduct($product->product_id,$product->product_name,$product->category_name);
                if ($url != $link_detail) {
                    return Redirect::to($link_detail);
                }
               
                $meta_title = $product->product_name . ' - '.CGlobal::web_name;
                $meta_keywords = $product->product_name;
                $meta_description = strip_tags($product->product_sort_desc);
                $meta_img= ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product->product_id, $product->product_image, CGlobal::sizeImage_450);
                FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description, $link_detail);

            }
        }
        //san pham bạn quan tâm
      	$limit = (isset($user_shop->is_shop) &&  $user_shop->is_shop = CGlobal::SHOP_VIP) ? CGlobal::number_show_15 : CGlobal::number_show_5;
    	$total = $offset = 0;
    	$search['field_get'] = $this->str_field_product_get;
    	$dataProVip = Product::getProductForSite($search, $limit, $offset,$total);

    	//san phẩm nôi bật
      	$limit = CGlobal::number_show_5;
    	$total = $offset = 0;
    	$search1['field_get'] = $this->str_field_product_get;
        $search1['shop_id_other'] = isset($user_shop->shop_id)? $user_shop->shop_id : 0;
    	$dataProNoiBat = Product::getProductForSite($search1, $limit, $offset,$total);
    	//$dataProNoiBat = array();

        $this->layout->content = View::make('site.SiteLayouts.DetailProduct')
            ->with('product',$product)
            ->with('user_shop', $user_shop)
            ->with('dataProNoiBat', $dataProNoiBat)
        	->with('dataProVip',$dataProVip);
        $this->footer();
    }

    //trang list tin tuc
    public function homeNew(){
    	
    	$meta_title = $meta_keywords = $meta_description ='Tin tức';
    	$meta_img= '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
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
        $search['field_get'] = 'news_id,news_category,news_title,news_desc_sort,news_image';//cac truong can lay
        
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
    public function detailNew($cat_id, $new_id, $new_name){
		
        $this->header();
        $dataNew = $dataNewsSame = array();
        $user_shop = array();
        //get news detail
        if($new_id > 0) {
            $dataNew = News::getNewByID($new_id);
            //get news same
            if($dataNew != null){
                $dataField['field_get'] = 'news_id,news_title,news_desc_sort,news_content,news_category,news_image';
                $dataNewsSame = News::getSameNews($dataField, $dataNew->news_category, $new_id, 10);
                
                $meta_title = $dataNew->news_title.'-'.CGlobal::web_name;
                $meta_keywords = $dataNew->news_title;
                $meta_description = strip_tags($dataNew->news_desc_sort);
                $meta_img= ThumbImg::getImageThumb(CGlobal::FOLDER_NEWS, $dataNew->news_id, $dataNew->news_image, CGlobal::sizeImage_450);
                FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
                
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
        $user_shop = UserShop::getByID($shop_id);
    	if($user_shop && sizeof($user_shop) > 0){
            //check shop thỏa mãn thì đi tiếp
            if($user_shop->shop_status != CGlobal::status_show){
                return Redirect::route('site.page404');
            }
           
        
            $meta_title = $meta_keywords = $meta_description = $user_shop->shop_name.'-'.CGlobal::web_name;
            $meta_img = '';
            FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
            
            $arrChildCate = UserShop::getCategoryShopById($user_shop->shop_id);
            $search['user_shop_id'] = $shop_id;
            $pageNo = (int) Request::get('page_no', 1);
            $limit = CGlobal::number_show_20;
            $offset = ($pageNo - 1) * $limit;
            $total = 0;
            $pageScroll = CGlobal::num_scroll_page;
            $product = Product::getProductForSite($search, $limit, $offset,$total);
            $paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';

            //quảng cáo của shop
	    	$arrBannerSlider = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_BIG, CGlobal::BANNER_PAGE_LIST, 0, $shop_id);
	    	$arrBannerLeft = FunctionLib::getBannerAdvanced(CGlobal::BANNER_TYPE_HOME_LEFT, CGlobal::BANNER_PAGE_LIST, 0, 0);

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
                        $shop_share = ShopShare::addData(array('shop_share_ip'=>$hostIp,'shop_id'=>$user_shop->shop_id,'shop_name'=>$user_shop->shop_name));
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
    	$this->layout->content = View::make('site.SiteLayouts.ShopHome')
    	->with('product',$product)
    	->with('arrChildCate',$arrChildCate)
    	->with('paging', $paging)
    	->with('user_shop', $user_shop)
    	->with('title', $user_shop->shop_name)
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
        		$product = Product::getProductForSite($search, $limit, $offset,$total);
        		$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
        		
        		$meta_title = $meta_keywords = $meta_description = $arrCatShow->category_name.'-'.CGlobal::web_name;
        		$meta_img = '';
        		FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
        		
        		
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
        
    	$meta_title = $meta_keywords = $meta_description = 'Đăng nhập';
    	$meta_img = '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        if(sizeof($this->user) > 0){
            return Redirect::route('shop.adminShop');
        }
        $this->header();
        $error = '';
        $this->layout->content = View::make('site.ShopAction.ShopLogin')
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

        $this->layout->content = View::make('site.ShopAction.ShopLogin')
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
        $this->layout->content = View::make('site.ShopAction.ShopRegister')
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

        $this->layout->content = View::make('site.ShopAction.ShopRegister')
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
    	$this->layout->content = View::make('site.ShopLayouts.ShopForgetPass')
    	->with('error',array());
    	$this->footer();
    }
    public function postShopForgetPass(){
    	
    	FunctionLib::site_css('frontend/css/reglogin.css', CGlobal::$POS_HEAD);
        $this->header();
        $dataSave = $error = array();

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
        	$emails = [$dataSave['shop_email'], 'shoponlinecuatui@gmail.com', 'nguyenduypt86@gmail.com'];
        	Mail::send('emails.ForgetPass', array('data'=>$data), function($message) use ($emails){
        		$message->to($emails, 'UserShop')
        				->subject('Thông tin mật khẩu mới'.date('d/m/Y h:i',  time()));
        	});
        	return Redirect::route('site.shopLogin');
        }
        $this->layout->content = View::make('site.ShopLayouts.ShopForgetPass')
            ->with('error',$error)
            ->with('data',$dataSave);
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
    	
		$meta_title = $meta_keywords = $meta_description = '404';
		$meta_img= '';
		FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
		
		$this->header();
		
        $limit = CGlobal::number_show_30;
        $total = $offset = 0;
        $search['field_get'] = $this->str_field_product_get;
        $dataProVip = Product::getProductForSite($search, $limit, $offset,$total);
    	
    	$this->layout->content = View::make('site.SiteLayouts.page404')
            ->with('dataProVip',$dataProVip);

    	$this->footer();
    }
}

