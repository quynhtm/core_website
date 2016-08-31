<div class="container">
	<div class="line-top">
		<div class="box-menu-list">
			<div class="content-box-menu">
				<ul>
					<li>
						<a href="">Ẩm thực - Giải trí</a>
						<div class="list-subcat">
							<ul>
								<li><a href="" title="">Buffet</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="">Văn phòng phẩm - Sách báo</a>
						<div class="list-subcat">
							<ul>
								<li><a href="" title="">Buffet</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="">Dịch vụ</a>
						<div class="list-subcat">
							<ul>
								<li><a href="" title="">Buffet</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="">Mỹ phẩm - làm đẹp</a>
						<div class="list-subcat">
							<ul>
								<li><a href="" title="">Buffet</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="">Nhà sạch - nhà đẹp</a>
						<div class="list-subcat">
							<ul>
								<li><a href="" title="">Buffet</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="slider-box-mid">
			<a rel="nofollow" title="Hòa nhịp bóng đá, tặng lịch Euro" href="" class="nivo-imageLink">
			<img src="http://sanphamredep.com/uploads/images/ads/11-2015/08-35-08-07-11-2015-1.jpg" alt="Hòa nhịp bóng đá, tặng lịch Euro">
		</a>
		</div>
		<div class="ads-right-mid">
			<div class="item-right-slider">
				<a rel="nofollow" href="" title="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà" target="_blank">
					<img src="http://shopcuatui.com.vn/image.php?type_dir=banner&amp;id=22&amp;width=300&amp;height=0&amp;image=http://shopcuatui.com.vn/uploads/banner/22/08-19-38-24-05-2016-3.jpg" alt="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà">
				</a>
			</div>
			<div class="item-right-slider">
				<a rel="nofollow" href="" title="Khuyến mãi bất ngờ từ Luminarc" target="_blank">
					<img src="http://shopcuatui.com.vn/image.php?type_dir=banner&amp;id=20&amp;width=300&amp;height=0&amp;image=http://shopcuatui.com.vn/uploads/banner/20/09-48-59-01-06-2016-77.jpg" alt="Khuyến mãi bất ngờ từ Luminarc">
				</a>
			</div>
		</div>
	</div>
	<div class="line-box">
		<div class="cate-box">
			<div class="inner-cate-box hide-text-over">
				<h2 class="parent-cate">
                	<a href="">
                       <span>Sản phẩm Shop Vip</span>
                    </a>
                </h2>
                @if(!empty($listParentCate))
                <div class="list-sub-cate">
                	<?php $i=0 ?>
                	@foreach($listParentCate as $key => $val)
                	<?php $i++ ?>
                	@if($i<=8)
                	<h3 class="sub-item">
                        <a href="javascript:void(0)">{{ $val }}</a>
                    </h3>
                     @endif
                    @endforeach
                </div>
                @endif
			</div>
		</div>
		<div class="content-list-item w-home">
			<ul>
				@if($dataProVip != null)
				@foreach($dataProVip as $item)
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
								<img alt="{{$item->product_name}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], 300, 300, '', true, true)}}"
									data-other-src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image_hover'], 300, 300, '', true, true)}}">
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
		</div>
	</div>
	<div class="line-ads">
		<div class="banner-ads">
			<img src="https://static11.muachungcdn.com/original/i:plaza/product/product/-0-HangNhatThai_1150x60-146837977178478/sunhouse-thang-7.jpg" alt="">
		</div>
	</div>
	<div class="line-box">
		<div class="cate-box">
			<div class="inner-cate-box hide-text-over">
				<h2 class="parent-cate">
                	<a href="">
                       <span>Sản phẩm Shop thường</span>
                    </a>
                </h2>
                @if(!empty($listParentCate))
                <div class="list-sub-cate">
                	<?php $i=0 ?>
                	@foreach($listParentCate as $key => $val)
                	<?php $i++ ?>
                	@if($i<=8)
                	<h3 class="sub-item">
                        <a href="javascript:void(0)">{{ $val }}</a>
                    </h3>
                     @endif
                    @endforeach
                </div>
                @endif
			</div>
		</div>
		<div class="content-list-item w-home">
			<ul>
				@if($dataProFree != null)
				@foreach($dataProFree as $item)
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
								<img alt="{{$item->product_name}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], 300, 300, '', true, true)}}"
									data-other-src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image_hover'], 300, 300, '', true, true)}}">
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
		</div>
	</div>
</div>