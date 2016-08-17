<?php

class SiteHomeController extends BaseSiteController
{
    public function __construct()
    {
        parent::__construct();
    }

    //trang ch?
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

    //trang chi ti?t S?N PH?M
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

    //trang chi ti?t TIN T?C
    public function detailNew($cat_name, $new_id, $pro_name){
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
    //trang danh sách tin t?c
    public function listNew($news_category = 0){
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
        $this->header();
        $dataShow = array();
        $this->layout->content = View::make('site.ShopLayouts.ShopLogin')
            ->with('data',$dataShow)
            ->with('user', $this->user);
        $this->footer();
    }

}

