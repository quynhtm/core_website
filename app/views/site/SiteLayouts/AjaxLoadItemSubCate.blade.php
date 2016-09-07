<ul class="data-tab tab-{{$catid}}">
	@if(sizeof($data) != 0)
	@foreach($data as $item)
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
				@if($item->user_shop_id > 0 && $item->user_shop_name != '' && $item->is_shop == CGlobal::SHOP_VIP)
				<div class="mgt5 amount-call">
					<a title="{{$item->user_shop_name}}" class="link-shop" href="{{Config::get('config.WEB_ROOT')}}shop-{{$item->user_shop_id}}/{{$item->user_shop_name}}.html">{{$item->user_shop_name}}</a>
				</div>
				@endif
			</div>
		</li>
	@endforeach
	@else
	<li class="updating"><div>Chưa có sản phẩm nào trong danh mục này...</div></li>
	@endif
</ul>	