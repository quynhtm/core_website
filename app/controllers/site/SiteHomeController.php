<?php

class SiteHomeController extends BaseSiteController
{
    public function __construct(){
        parent::__construct();
        FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
        //return $this->offSite();
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
        $limit = CGlobal::number_show_40;
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
        $limit2 = CGlobal::number_show_20;
        $total2 = $offset2 = 0;
        $searchFree['is_shop'] = array( CGlobal::SHOP_NOMAL, CGlobal::SHOP_FREE );
        $searchFree['category_id'] = 0;
        $searchFree['field_get'] = $this->str_field_product_get;
        $dataProFree = Product::getProductForSite($searchFree, $limit2, $offset2, $total2);

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
        $limit = CGlobal::number_show_40;
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
                $limit = CGlobal::number_show_40;
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
            if (sizeof($product) > 0) {
                //check xem sản phẩm có khi khóa hay ẩn hay không
                if($product->product_status == CGlobal::status_hide || $product->is_block == CGlobal::PRODUCT_BLOCK){
                    return Redirect::route('site.page404');
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

            }else{
            	return Redirect::route('site.page404');
            }
        }
        //san pham bạn quan tâm
      	$limit = CGlobal::number_show_8;
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
                $url = FunctionLib::buildLinkDetailNews($dataNew->news_id, $dataNew->news_category, $dataNew->news_title);
                FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description, $url);
                
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

    public function offSite()
    {
        $userAdmin = User::user_login();
        //FunctionLib::debug($userAdmin);
        if(empty($userAdmin)){
            $url_image = Config::get('config.WEB_ROOT').'images/cap-nhat-va-bao-tri-web.jpg';
            echo View::make('site.offSite', array('url_src_icon' => $url_image, 'date_off'=>86400 * 10));
            die();
        }
        // $this->layout->content = View::make('site.offSite');
    }

}

