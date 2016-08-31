<div class="container">
	<div class="link-breadcrumb">
		<a href="{{Config::get('config.WEB_ROOT')}}" title="Trang chủ">Trang chủ</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="http://shopcuatui.com.vn/gian-hang/53/EnMax-98.html" title="EnMax 98">EnMax 98</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="http://shopcuatui.com.vn/gian-hang/53/c196/Thuc-pham-chuc-nang.html" title="Thực phẩm chức năng">Thực phẩm chức năng</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="http://shopcuatui.com.vn/san-pham/p702/Cham-soc-da-toc-bang-dau-dua-nguyen-chat.html" title="Chăm sóc da, tóc bằng dầu dừa nguyên chất - Shopcuatui.com.vn">Chăm sóc da, tóc bằng dầu dừa nguyên chất</a>
	</div>
	
	<div class="main-view-post .box-detail-product">
		<div class="wrap-main-view">
			<div class="top-content-view">
				<div class="left-slider-img">
					<ul class="list-thumb-img">
						<li>
							<a href="javascript:void(0)" data-zoom="http://shopcuatui.com.vn/uploads/thumbs/product/702/400x500/03-49-05-22-08-2016-tinh-dau-dua.jpg" class="act">
								<img src="http://shopcuatui.com.vn/uploads/thumbs/product/702/100x100/03-49-05-22-08-2016-tinh-dau-dua.jpg" alt="Chăm sóc da, tóc bằng dầu dừa nguyên chất - Shopcuatui.com.vn">
							</a>
						</li>
					</ul>
					<div class="max-thumb-img">
						<a href="javascript:void(0)" title="">
							<img src="http://shopcuatui.com.vn/uploads/thumbs/product/702/400x500/03-49-05-22-08-2016-tinh-dau-dua.jpg" alt="Chăm sóc da, tóc bằng dầu dừa nguyên chất - Shopcuatui.com.vn">
						</a>
					</div>
				</div>
				<div class="center-des-product">
					<h1>Chăm sóc da, tóc bằng dầu dừa nguyên chất</h1>
					<div class="row-price">
						<div class="lbl-row">Giá thị trường:</div>
						<div class="price-origin">158,000<span class="td-border">đ</span></div>
					</div>
					<div class="row-price">
						<div class="lbl-row lbl-price-sale">Giá bán:</div>
						<div class="price-sale">110,000<span class="td-border">đ</span></div>
					</div>	
					<div class="features-point">
						<div class="lbl-point">Mô tả sản phẩm</div>
						<div class="des-point">
							<p>Dầu dừa được xem là một loại sữa dưỡng thể rất an toàn, giúp da mềm mại ngăn ngừa khô da, trị&nbsp;da nứt nẻ,&nbsp;thích hợp với mọi loại da, mọi lứa tuổi, không lo bị kích ứng da. Trị gầu và&nbsp;đem lại mái tóc mềm mượt, ngăn ngừa tóc rụng.</p>
						</div>
						<div class="box-promotion">
							<div class="lbl-point">Thông tin khuyến mãi</div>
							<div class="box-content-promotion">giảm giá 30%</div>
						</div>
					</div>
				</div>
				<div class="right-des-product">
					<div class="content-right-product">
						<div id="fb-root"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>
						<div class="fb-like" data-href="http://shopcuatui.com.vn/danh-muc/c90/Thuc-pham.html"
							data-layout="button_count" data-action="like" 
							data-show-faces="false" data-share="true">
						</div>
					</div>
					<div class="content-right-product">
						<div class="order-number">
							<label for="buy-number">Số lượng</label>
							<select class="sl-num" id="buy-num" name="buy-num">
                            	<option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
								<option value="10">10</option>
							</select>
						</div>
						<div id="buttonFormBuySubmit" data-pid="702" class="buynow btn">Mua ngay</div>
					</div>
					<div class="content-right-product">
						<div class="order-number-phone">
							<p><b>Đặt nhanh qua điện thoại</b></p>
							<div class="number-phone">
								<div class="fa fa-phone"></div>
								<span>0932292136</span>
							</div>
							<p><a href="" title="Shop: EnMax 98">EnMax 98</a></p>
							<p><b>Thông tin liên hệ: </b></p>
							<p>phanngoc289@gmail.com</p>
							<p>98 Hoàng Văn Thái, Thanh Xuân, HN</p>
						</div>
					</div>
				</div>
			</div>
			<div class="center-content-view">
				<div class="title-center-content-view">Sản phẩm bạn có thể quan tâm</div>
				<div class="content-center-content-view">
				<ul>
					@foreach($dataFieldProductHot as $item)
						<li class="item">
							@if($item->product_type_price == 1)
								@if((float)$item->product_price_market > (float)$item->product_price_sell)
								<span class="sale-off">
									-{{ number_format(100 - ((float)$item->product_price_sell/(float)$item->product_price_market)*100, 1) }}%
								</span>
								@endif
							@endif
							<div class="post-thumb">
								<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_id)}}">
									<img alt="{{$item->product_name}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], 300, 300, '', true, true)}}">
								</a>
							</div>
							<div class="item-content">
								<div class="title-info">
									<h4 class="post-title">
										<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_id)}}">{{$item->product_name}}</a>
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
								@if(!empty($user_shop))
								<div class="mgt5 amount-call">
				                	<a title="{{$user_shop['shop_name']}}" class="link-shop" href="">{{$user_shop['shop_name']}}</a>
				            	</div>
				            	@endif
							</div>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="bottom-content-view">
				<div class="left-bottom-content-view">
					<ul class="tab">
						<li class="act" data-tab="1">Chi tiết sản phẩm</li>
						<li data-tab="2" class="">Bình luận</li>
						<li data-tab="3" class="">Chính sách giao nhận</li>
						<li data-tab="4" class="">Giới thiệu Shop</li>
					</ul>
					<div class="content-bottom-content-view">
						<div class="show-tab show-tab-1 act">Tab 1</div>
						<div class="show-tab show-tab-2">Tab 2</div>
						<div class="show-tab show-tab-3">Tab 3</div>
						<div class="show-tab show-tab-4">Tab 4</div>
					</div>
				</div>
				<div class="right-bottom-content-view">
					<div class="title-hot"><span>Sản phẩm nổi bật</span></div>
					<div class="content-right-bottom-content-view">
						<ul>
							@foreach($dataFieldProductHot as $item)
							<li class="item">
								<span class="sale-off">-11.1%</span>
								<div class="post-thumb">
									<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_id)}}">
										<img alt="{{$item->product_name}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], 300, 300, '', true, true)}}">
									</a>
								</div>
								<div class="item-content">
									<div class="title-info">
										<h4 class="post-title">
											<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_id)}}">{{$item->product_name}}</a>
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
									@if(!empty($user_shop))
									<div class="mgt5 amount-call">
					                	<a title="{{$user_shop['shop_name']}}" class="link-shop" href="">{{$user_shop['shop_name']}}</a>
					            	</div>
					            	@endif
								</div>
							</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>