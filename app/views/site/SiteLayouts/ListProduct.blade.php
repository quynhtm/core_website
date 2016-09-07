<div class="container">
	<div class="link-breadcrumb">
		<a href="{{Config::get('config.WEB_ROOT')}}" title="Trang chủ">Trang chủ</a>
		@if(sizeof($arrParrentCat) != 0)
		<i class="fa fa-angle-double-right"></i>
		<a href="{{URL::route('site.listProduct', array('name'=>strtolower(FunctionLib::safe_title($arrParrentCat->category_name)),'id'=>$arrParrentCat->category_id))}}" title="{{$arrParrentCat->category_name}}">{{$arrParrentCat->category_name}}</a>
		@endif
	</div>
	<div class="main-view-post">
		<div class="wrapp-content-news">
			<div class="left-category-shop">
				@if(!empty($arrChildCate))
				<div class="wrapp-category-menu">
					<div class="title-category-parent">{{$arrParrentCat->category_name}}</div>
					<ul>
						@foreach($arrChildCate as $key=>$cat)
						<li><a href="{{URL::route('site.listProduct', array('name'=>strtolower(FunctionLib::safe_title($cat)),'id'=>$key))}}" title="{{$cat}}">{{$cat}}</a></li>
						@endforeach
					</ul>
				</div>
				@endif
				@if(sizeof($arrParrentCat) != 0)
				<div class="content-right-product">
					<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="{{URL::route('site.listProduct', array('name'=>strtolower(FunctionLib::safe_title($arrParrentCat->category_name)),'id'=>$arrParrentCat->category_id))}}"
						data-layout="button_count" data-action="like" 
						data-show-faces="false" data-share="true">
					</div>
				</div>
				@endif
				<div class="content-line-ads">
					<div class="item-right-ads">
						<a rel="nofollow" href="" title="Giờ vàng giá sốc mua thần tốc" target="_blank">
							<img src="http://shopcuatui.com.vn/image.php?type_dir=banner&amp;id=17&amp;width=300&amp;height=0&amp;image=http://shopcuatui.com.vn/uploads/banner/17/08-11-10-09-06-2016-77.jpg" alt="Giờ vàng giá sốc mua thần tốc">
						</a>
					</div>
				</div>
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
							@if($item->user_shop_id > 0 && $item->user_shop_name != '')
							<div class="mgt5 amount-call">
			                	<a title="{{$item->user_shop_name}}" class="link-shop" href="{{Config::get('config.WEB_ROOT')}}shop-{{$item->user_shop_id}}/{{$item->user_shop_name}}.html">{{$item->user_shop_name}}</a>
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