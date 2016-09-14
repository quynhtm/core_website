<?php

class SiteOrderController extends BaseSiteController
{
    public function __construct(){
        parent::__construct();
        FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
    }

    private $str_field_product_get = 'product_id,product_name,category_id,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop';//cac truong can lay


	/*********************************************************************************************************************************
	 * Phần đặt hàng
	 *********************************************************************************************************************************
	 */
    public function listCartOrder(){
    	$this->header();
    	$this->layout->content = View::make('site.SiteOrder.listCartOrder');
    	$this->footer();
    }
    public function sendCartOrder(){
    	$this->header();
    	$this->layout->content = View::make('site.SiteOrder.sendCartOrder');
    	$this->footer();
    }

    public function thanksBuy(){
    	$this->header();
    	$limit = CGlobal::number_show_30;
    	$total = $offset = 0;
    	$search['field_get'] = $this->str_field_product_get;
    	$dataProVip = Product::getProductForSite($search, $limit, $offset,$total);
    	$this->layout->content = View::make('site.SiteOrder.thanksBuy')
            ->with('dataProVip',$dataProVip);
    	$this->footer();
    }
}

