<div class="container">
	<div class="link-breadcrumb">
		<a href="{{Config::get('config.WEB_ROOT')}}" title="Trang chủ">Trang chủ</a>
		@if(sizeof($user_shop) != 0)
		<i class="fa fa-angle-double-right"></i>
		<a title="{{$user_shop->shop_name}}" class="link-shop" href="{{Config::get('config.WEB_ROOT')}}shop-{{$user_shop->shop_id}}/{{FunctionLib::safe_title($user_shop->shop_name)}}.html">{{$user_shop->shop_name}}</a>
		@endif
	</div>
	<div class="main-view-post">
		<div class="wrapp-content-news">
			<div class="left-category-shop">
				@if(!empty($arrChildCate))
				<div class="wrapp-category-menu">
					<div class="title-category-parent">{{$categoryParrentCat->category_name}}</div>
					<ul>
						@foreach($arrChildCate as $key=>$cat)
						<li><a href="{{URL::route('site.listProduct', array('name'=>strtolower(FunctionLib::safe_title($cat)),'id'=>$key))}}" title="{{$cat}}">{{$cat}}</a></li>
						@endforeach
					</ul>
				</div>
				@endif
				<div class="content-right-product">
					<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="{{Config::get('config.WEB_ROOT')}}shop-{{$user_shop->shop_id}}/{{FunctionLib::safe_title($user_shop->shop_name)}}.html"
						data-layout="button_count" data-action="like" 
						data-show-faces="false" data-share="true">
					</div>
				</div>
				<div class="content-right-product">
						<div class="order-number-phone">
							<p><b>Đặt nhanh qua điện thoại</b></p>
							<div class="number-phone">
								<div class="fa fa-phone"></div>
								<span>{{$user_shop->shop_phone}}</span>
							</div>
							<p><a href="{{Config::get('config.WEB_ROOT')}}shop-{{$user_shop->shop_id}}/{{FunctionLib::safe_title($user_shop->shop_name)}}.html" title="Shop: {{$user_shop->shop_name}}">{{$user_shop->shop_name}}</a></p>
							<p><b>Thông tin liên hệ: </b></p>
							<p>{{$user_shop->shop_email}}</p>
							<p>{{$user_shop->shop_address}}</p>
						</div>
					</div>
			</div>
			<div class="right-show-product-shop body-list-item">
				<div class="banner-shop-content">
					<div id="sliderShop">
						<div class="slide ">
							<a target="_blank" href="" title="Danh mục mẹ và bé" rel="nofollow">
								<img alt="Danh mục mẹ và bé" src="http://shopcuatui.com.vn/uploads/banner/8/11-14-24-27-04-2016-banner-web-thoitrangbegai.jpg">
							</a>
						</div>
						<div class="slide ">
							<a target="_blank" href="" title="Danh mục mẹ và bé" rel="nofollow">
								<img alt="Danh mục mẹ và bé" src="http://shopcuatui.com.vn/uploads/banner/7/02-07-37-22-04-2016-bi-quyet-lam-trang-da-cap-toc-bang-my-pham.jpg">
							</a>
						</div>
					</div>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#sliderShop').bxSlider({
								slideWidth: 1170,
								slideHeight: 245,
								minSlides: 1,
								maxSlides: 2,
								slideMargin: 10,
								mode: 'fade',
								pager: true,
								auto: true,
							});
					    });
					</script>
				</div>
				<ul>
					@if(sizeof($product) != 0)
					@foreach($product as $item)
					<li class="item">
						@if($item->product_type_price == 1)
							@if((float)$item->product_price_market > (float)$item->product_price_sell)
							<span class="sale-off">
								-{{ number_format(100 - ((float)$item->product_price_sell/(float)$item->product_price_market)*100, 1) }}%
							</span>
							@endif
						@endif
						<div class="post-thumb">
							<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}">
								<img alt="{{$item->product_name}}" src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], CGlobal::sizeImage_300)}}"
									data-other-src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image_hover'], CGlobal::sizeImage_300)}}">
							</a>
						</div>
						<div class="item-content">
							<div class="title-info">
								<h4 class="post-title">
									<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}">{{$item->product_name}}</a>
								</h4>
								<div class="item-price">
									@if($item->product_price_sell > 0)
									<span class="amount-1">{{FunctionLib::numberFormat($item->product_price_sell)}}đ</span>
									@endif
									@if($item->product_price_market > 0)
									<span class="amount-2">{{FunctionLib::numberFormat($item->product_price_market)}}đ</span>
									@endif
									@if($item->product_price_sell == 0 && $item->product_price_market == 0)
										<span class="amount-1">Liên hệ</span>
									@endif
								</div>
							</div>
						</div>
					</li>
					@endforeach
					@endif
				</ul>
				<div class="show-box-paging">{{$paging}}</div>
			</div>
		</div>
	</div>
</div>