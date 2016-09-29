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
				@if($user_shop->shop_logo != '' && $user_shop->is_shop == CGlobal::SHOP_VIP)
				<div class="logo-shop">
					<img src="{{ ThumbImg::getImageThumb(CGlobal::FOLDER_LOGO_SHOP, $user_shop->shop_id, $user_shop->shop_logo, CGlobal::sizeImage_300, '', true, CGlobal::type_thumb_image_banner, false)}}">
				</div>
				@endif
				<div class="content-right-product shopInfo">
					<div class="order-number-phone">
						<p><b>THÔNG TIN SHOP</b></p>
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
				@if(!empty($arrChildCate))
				<div class="wrapp-category-menu">
					<div class="title-category-parent">Danh mục sản phẩm</div>
					<ul>
						@foreach($arrChildCate as $key=>$cat)
						<li><a href="{{URL::route('shop.shopListProduct', array('shop_id'=>$user_shop->shop_id,'cat_id'=>$key, 'name'=>strtolower(FunctionLib::safe_title($cat))))}}" title="{{$cat}}">{{$cat}}</a></li>
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
				@if(sizeof($arrBannerLeft) != 0)
				<div class="content-line-ads">
					@foreach($arrBannerLeft as $item)
					<div class="item-right-ads">
						<a @if($item->banner_is_rel == CGlobal::LINK_NOFOLLOW) rel="nofollow" @endif @if($item->banner_is_target == CGlobal::BANNER_TARGET_BLANK) target="_blank" @endif title="{{$item->banner_name}}" href="@if($item->banner_link != '') {{$item->banner_link}} @else javascript:void(0) @endif">
							<img src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_BANNER, $item->banner_id, $item->banner_image, CGlobal::freeSizeImage_300, '', true, CGlobal::type_thumb_image_banner, false)}}" alt="{{$item->banner_name}}">
						</a>
					</div>
					@endforeach
				</div>
				@endif
			</div>
			<div class="right-show-product-shop body-list-item {{(FunctionLib::checkOS()) ? 'phone' : ''}}">
				@if(sizeof($arrBannerSlider) != 0)
				<div class="banner-shop-content">
					<div id="sliderShop">
						@foreach($arrBannerSlider as $item)
						<div class="slide ">
							<a @if($item->banner_is_rel == CGlobal::LINK_NOFOLLOW) rel="nofollow" @endif @if($item->banner_is_target == CGlobal::BANNER_TARGET_BLANK) target="_blank" @endif title="{{$item->banner_name}}" href="@if($item->banner_link != '') {{$item->banner_link}} @else javascript:void(0) @endif">
								<img src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_BANNER, $item->banner_id, $item->banner_image, CGlobal::sizeImage_1020, '', true, CGlobal::type_thumb_image_banner, false)}}" alt="{{$item->banner_name}}">
							</a>
						</div>
						@endforeach
					</div>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#sliderShop').bxSlider({
								slideWidth: 1020,
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
				@else
				<div class="banner-shop-content">
					<img src="{{Config::get('config.WEB_ROOT').CGlobal::banner_slider_default_shop}}" alt="ShopCuaTui.com.vn">
				</div>
				@endif

				<!---- San pham Giam gia---->
				@if(sizeof($productSellOff) > 0)
				<div class="box-tab-shop">
					<ul class="tab-shop">
						<li data-tab="1" class="act" >Sản phẩm giảm giá</li>
					</ul>
				</div>
				<ul>
					@foreach($productSellOff as $product_sell_off)
					<li class="item">
						@if($product_sell_off->product_type_price == 1)
							@if((float)$product_sell_off->product_price_market > (float)$product_sell_off->product_price_sell)
							<span class="sale-off">
								-{{ number_format(100 - ((float)$product_sell_off->product_price_sell/(float)$product_sell_off->product_price_market)*100, 1) }}%
							</span>
							@endif
						@endif
						<div class="post-thumb">
							<a title="{{$product_sell_off->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($product_sell_off->product_id, $product_sell_off->product_name, $product_sell_off->category_name)}}">
								<img alt="{{$product_sell_off->product_name}}" src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_sell_off['product_id'], $product_sell_off['product_image'], CGlobal::sizeImage_300)}}"
									data-other-src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_sell_off['product_id'], $product_sell_off['product_image_hover'], CGlobal::sizeImage_300)}}">
							</a>
						</div>
						<div class="item-content">
							<div class="title-info">
								<h4 class="post-title">
									<a title="{{$product_sell_off->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($product_sell_off->product_id, $product_sell_off->product_name, $product_sell_off->category_name)}}">{{$product_sell_off->product_name}}</a>
								</h4>
								<div class="item-price">
									@if($product_sell_off->product_type_price == 1)
										@if($product_sell_off->product_price_sell > 0)
										<span class="amount-1">{{FunctionLib::numberFormat($product_sell_off->product_price_sell)}}đ</span>
										@endif
										@if($product_sell_off->product_price_market > 0)
										<span class="amount-2">{{FunctionLib::numberFormat($product_sell_off->product_price_market)}}đ</span>
										@endif
									@else
										<span class="amount-1">Liên hệ</span>
									@endif
								</div>
							</div>
						</div>
					</li>
					@endforeach
				</ul>
				<div style="clear: both"></div>
				@endif

				<!---- San pham nổi bật---->
				@if(sizeof($productHot) > 0)
				<div class="box-tab-shop">
					<ul class="tab-shop">
						<li data-tab="1" class="act" >Sản phẩm nổi bật</li>
					</ul>
				</div>
				<ul>
					@foreach($productHot as $product_hot)
						<li class="item">
							@if($product_hot->product_type_price == 1)
								@if((float)$product_hot->product_price_market > (float)$product_hot->product_price_sell)
									<span class="sale-off">
									-{{ number_format(100 - ((float)$product_hot->product_price_sell/(float)$product_hot->product_price_market)*100, 1) }}%
								</span>
								@endif
							@endif
							<div class="post-thumb">
								<a title="{{$product_hot->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($product_hot->product_id, $product_hot->product_name, $product_hot->category_name)}}">
									<img alt="{{$product_hot->product_name}}" src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_hot['product_id'], $product_hot['product_image'], CGlobal::sizeImage_300)}}"
										 data-other-src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $product_hot['product_id'], $product_hot['product_image_hover'], CGlobal::sizeImage_300)}}">
								</a>
							</div>
							<div class="item-content">
								<div class="title-info">
									<h4 class="post-title">
										<a title="{{$product_hot->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($product_hot->product_id, $product_hot->product_name, $product_hot->category_name)}}">{{$product_hot->product_name}}</a>
									</h4>
									<div class="item-price">
										@if($product_hot->product_type_price == 1)
											@if($product_hot->product_price_sell > 0)
												<span class="amount-1">{{FunctionLib::numberFormat($product_hot->product_price_sell)}}đ</span>
											@endif
											@if($product_hot->product_price_market > 0)
												<span class="amount-2">{{FunctionLib::numberFormat($product_hot->product_price_market)}}đ</span>
											@endif
										@else
											<span class="amount-1">Liên hệ</span>
										@endif
									</div>
								</div>
							</div>
						</li>
					@endforeach
				</ul>
				<div style="clear: both"></div>
				@endif

				<!---San pham bthuong--->
				@if(sizeof($product) != 0)
					<div class="box-tab-shop">
						<ul class="tab-shop">
							<li data-tab="1" class="act" >Sản phẩm mới về</li>
						</ul>
					</div>
					<ul>
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
										@if($item->product_type_price == 1)
											@if($item->product_price_sell > 0)
											<span class="amount-1">{{FunctionLib::numberFormat($item->product_price_sell)}}đ</span>
											@endif
											@if($item->product_price_market > 0)
											<span class="amount-2">{{FunctionLib::numberFormat($item->product_price_market)}}đ</span>
											@endif
										@else
											<span class="amount-1">Liên hệ</span>
										@endif
									</div>
								</div>
							</div>
						</li>
						@endforeach
					</ul>
				@endif

				<div class="show-box-paging">{{$paging}}</div>
			</div>
		</div>
	</div>
</div>