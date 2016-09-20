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
	public function ajaxAddCart(){
		
		if(empty($_POST)){
			return Redirect::route('site.home');
		}
		
		$pid = (int)Request::get('pid');
		$pnum = (int)Request::get('pnum');
		$data = array();
		
		if($pid > 0 && $pnum > 0){
			$result = Product::getProductByID($pid);
			//Tam Het Hang
			if($result->is_sale != CGlobal::PRODUCT_IS_SALE){
				echo 'Tạm hết hàng!'; exit();
			}
			if($result->is_block == CGlobal::PRODUCT_BLOCK){
				echo 'Sản phẩm đang bị khóa!'; exit();
			}
			//Tam Het Hang
			if(sizeof($result) != 0){
				if(Session::has('cart')){
					$data = Session::get('cart');
					if(isset($data[$pid])){
						$data[$pid] += $pnum;
						if($data[$pid] > CGlobal::max_num_buy_item_product){
							$data[$pid] = CGlobal::max_num_buy_item_product;
						}
					}else{
						$data[$pid] = 1;
					}
				}else{
					$data[$pid] = 1;
				}
				Session::put('cart', $data, 60*24);
				echo 1;
			}else{
				if(Session::has('cart')){
					$data = Session::get('cart');
					if(isset($data[$pid])){
						unset($data[$pid]);
					}
					Session::put('cart', $data, 60*24);
				}
				echo 'Không tồn tại sản phẩm!';
			}

			Session::save();
		}
		exit();
	}
    public function listCartOrder(){
    	$meta_title = $meta_keywords = $meta_description = 'Thông tin giỏ hàng';
    	$meta_img = '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	$this->header();
    	
    	$dataCart = array();
    	//Update Cart
    	if(!empty($_POST)){
    		$token = Request::get('_token', '');
    		if(Session::token() === $token){
    			$updateCart = Request::get('listCart', array());
    			$dataCart = Session::get('cart');
    			foreach($updateCart as $k=>$v){
	    			if($v == 0){
	    				if(isset($dataCart[$k])){
	    					unset($dataCart[$k]);
	    				}
	    				if(empty($dataCart[$k])){
	    					unset($dataCart[$k]);
	    				}
	    			}else{
	    				if(isset($dataCart[$k])){
	    					$dataCart[$k] = $v;
	    					if($dataCart[$k] > CGlobal::max_num_buy_item_product){
	    						$dataCart[$k] = CGlobal::max_num_buy_item_product;
	    					}
	    				}
	    			}
    			}
    	
    			Session::put('cart', $dataCart);
    			Session::save();
    			unset($_POST);
    			return Redirect::route('site.listCartOrder');
    		}
    	}
    	//End Update Cart
    	
    	if(Session::has('cart')){
    		$dataCart = Session::get('cart');
    	}
    	//Config Page
    	$pageNo = (int) Request::get('page', 1);
    	$pageScroll = CGlobal::num_scroll_page;
    	$limit = CGlobal::number_show_30;
    	$offset = ($pageNo - 1) * $limit;
    	$search = $dataItem = array();
    	$total = 0;
    	$paging = '';
    	
    	if(!empty($dataCart)){
    		$arrId = array_keys($dataCart);
    		$paging = '';
    		if(!empty($arrId)){
    			$search['product_id'] = $arrId;
    			$search['field_get'] = $this->str_field_product_get;
    			$dataItem = Product::getProductForSite($search, $limit, $offset, $total);
    			$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
    		}
    	}
    	$this->layout->content = View::make('site.SiteOrder.listCartOrder')
    	->with('dataCart',$dataCart)
    	->with('dataItem',$dataItem)
    	->with('paging',$paging);
    	$this->footer();
 
    }
    public function deleteOneItemInCart(){
    
    	if(empty($_POST)){
    		return Redirect::route('site.home');
    	}
    
    	$id = (int)Request::get('id', 0);
    	if($id > 0){
    		if(Session::has('cart')){
    			$data = Session::get('cart');
    			if(isset($data[$id])){
    				unset($data[$id]);
    			}
    			Session::put('cart', $data, 60*24);
    			Session::save();
    		}
    	}
    	echo 'ok';exit();
    }
    public function deleteAllItemInCart(){
    	if(empty($_POST)){
    		return Redirect::route('site.home');
    	}
    	$dell = addslashes(Request::get('delAll', ''));
    	if($dell == 'delAll'){
    		if(Session::has('cart')){
    			Session::forget('cart');
    			Session::save();
    		}
    	}
    	echo 'ok';exit();
    }
    public function sendCartOrder(){
    	$meta_title = $meta_keywords = $meta_description = 'Gửi thông tin đơn hàng';
    	$meta_img = '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
    	if(!Session::has('cart')){
    		return Redirect::route('site.home');
    	}
    	
    	$this->header();
    	$dataCart = array();
    	if(Session::has('cart')){
    		$dataCart = Session::get('cart');
    	}
    	
    	//Config Page
    	$pageNo = (int) Request::get('page', 1);
    	$pageScroll = CGlobal::num_scroll_page;
    	$limit = CGlobal::number_show_30;
    	$offset = ($pageNo - 1) * $limit;
    	$search = $dataItem = array();
    	$total = 0;
    	$paging = '';
    	
    	if(!empty($dataCart)){
    		$arrId = array_keys($dataCart);
    		$paging = '';
    		if(!empty($arrId)){
    			$search['product_id'] = $arrId;
    			$search['field_get'] = $this->str_field_product_get;
    			$dataItem = Product::getProductForSite($search, $limit, $offset, $total);
    			$paging = $total > 0 ? Pagging::getNewPager($pageScroll, $pageNo, $total, $limit, $search) : '';
    		}
    	}
    	
    	if(!empty($_POST)){
    		$token = Request::get('_token', '');
    		if(Session::token() === $token){
    			$txtName = addslashes(Request::get('txtName', ''));
    			$txtMobile = addslashes(Request::get('txtMobile', ''));
    			$txtEmail = addslashes(Request::get('txtEmail', ''));
    			$txtAddress = addslashes(Request::get('txtAddress', ''));
    			$txtMessage = addslashes(Request::get('txtMessage', ''));
    	
    			if($txtName!= '' && $txtMobile != '' && $txtAddress != ''){
    				$data = array(
    						'order_customer_name'=>$txtName,
    						'order_customer_phone'=>$txtMobile,
    						'order_customer_email'=>$txtEmail,
    						'order_customer_address'=>$txtAddress,
    						'order_customer_note'=>$txtMessage,
    						'order_time'=>time(),
    						'order_status'=>CGlobal::ORDER_STATUS_NEW,
    				);
					$arrMailShop = array();
    				foreach($dataItem as $item){
						foreach($dataCart as $k=>$v){
    						if($item->product_id == $k){
    							$data['order_product_id'] = $item->product_id;
    							$data['order_product_name'] = $item->product_name;
    							$data['order_product_price_sell'] = $item->product_price_sell;
    							$data['order_product_image'] = $item->product_image;
    							$data['order_product_type_price'] = $item->product_type_price;
    							$data['order_quality_buy'] = $v;
    							
    							$data['order_category_id'] = $item->category_id;
    							$data['order_category_name'] = $item->category_name;
    							
    							$data['order_user_shop_id'] = $item->user_shop_id;
    							$data['order_user_shop_name'] = $item->user_shop_name;
    							$data['order_product_province'] = $item->shop_province;
    							
    							Order::addData($data);
								$arrMailShop[$item->user_shop_id][] = $data;
    						}
    					}
    				}
					//Send Mail Cart To Shop
					foreach($arrMailShop as $key=>$val){
						$get_user_shop = UserShop::getByID($key);
						$email_shop = $get_user_shop->shop_email;
						if($email_shop != ''){
							$dataMail = array(
								'user_shop'=>$get_user_shop->user_shop,
								'items'=>$val,
								'txtName'=>$txtName,
								'txtMobile'=>$txtMobile,
								'txtEmail'=>$txtEmail,
								'txtAddress'=>$txtAddress,
								'txtMessage'=>$txtMessage,
							);
							$emails = [$email_shop, 'shoponlinecuatui@gmail.com'];
							Mail::send('emails.SendOrderToMailShop', array('data'=>$dataMail), function($message) use ($emails){
								$message->to($emails, 'OrderToShop')
									->subject(CGlobal::web_name.' - Khách đã đặt mua sản phẩm của shop '.date('d/m/Y h:i',  time()));
							});
						}
					}
    				if(Session::has('cart')){
    					Session::forget('cart');
    					return Redirect::route('site.thanksBuy');
    				}
    			}
    		}
    	}
    	
    	$this->layout->content = View::make('site.SiteOrder.sendCartOrder')
    	->with('dataCart',$dataCart)
    	->with('dataItem',$dataItem)
    	->with('paging',$paging);
    	$this->footer();
    }
    public function thanksBuy(){
    	
    	$meta_title = $meta_keywords = $meta_description = 'Cảm ơn bạn đã mua hàng';
    	$meta_img = '';
    	FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
    	
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

