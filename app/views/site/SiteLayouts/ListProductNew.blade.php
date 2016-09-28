<div class="container">
	<div class="link-breadcrumb">
		<a href="{{URL::route('site.home')}}" title="Trang chủ">Trang chủ</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="{{URL::route('site.product_new')}}" title="Sản phẩm mới">Sản phẩm mới</a>
		
	</div>
	<div class="main-view-post">
		<div class="wrapp-content-news">
			<div class="left-category-shop">
				<div class="content-right-product">
					<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="{{URL::route('site.product_new')}}"
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
			<div class="right-show-product-shop body-list-item">
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
							@if($item->user_shop_id > 0 && $item->user_shop_name != '' && $item->is_shop == CGlobal::SHOP_VIP)
							<div class="mgt5 amount-call">
			                	<a title="{{$item->user_shop_name}}" class="link-shop" href="{{URL::route('shop.home',array('shop_id' => $item->user_shop_id,'shop_name' => FunctionLib::safe_title($item->user_shop_name)))}}">{{$item->user_shop_name}}</a>
			            	</div>
			            	@endif
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