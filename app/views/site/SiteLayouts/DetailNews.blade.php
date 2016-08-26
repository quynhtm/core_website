<div class="container">
	<div class="link-breadcrumb">
		<a href="{{Config::get('config.WEB_ROOT')}}" title="Trang chủ">Trang chủ</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="" title="Tin tức chung">Tin tức chung</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="{{FunctionLib::buildLinkDetailNews($dataNew->news_id, $dataNew->news_title)}}" title="{{$dataNew->news_title}}">{{$dataNew->news_title}}</a>
	</div>
	<div class="main-view-post">
		<div class="wrapp-content-news">
			<div class="left-news-view">
				<h1 class="title-news">{{$dataNew->news_title}}</h1>
				@if($dataNew->news_desc_sort != '')
				<div class="intro-news">
					{{$dataNew->news_desc_sort}}
				</div>
				@endif
				<div class="content-news">{{$dataNew->news_content}}</div>
				@if($dataNewsSame != null)
				<div class="same-content-news">
					<div class="same-title">Bài viết khác:</div>
					<ul>
						@foreach($dataNewsSame as $item)
						<li><i class="fa fa-angle-double-right"></i>
							<a href="{{FunctionLib::buildLinkDetailNews($item->news_id, $item->news_title)}}" title="{{$item->news_title}}">{{$item->news_title}}</a>
						</li>
						@endforeach
					</ul>
				</div>
				@endif
			</div>
			<div class="right-news-view">
				<div class="content-right-product search">
					{{Form::open(array('method' => 'GET', 'id'=>'formListItem', 'class'=>'formListItem', 'name'=>'txtForm', 'url'=>'tin-tuc.html'))}}
						<input name="news_title" class="keyword-news" type="text">
						<button value="s" name="submit" class="btn btn-primary">Tìm kiếm</button>
					{{Form::close()}}
				</div>
				<div class="content-right-product">
					<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="{{FunctionLib::buildLinkDetailNews($dataNew->news_id, $dataNew->news_title)}}"
						data-layout="button_count" data-action="like" 
						data-show-faces="false" data-share="true">
					</div>
				</div>
				@if($dataFieldProductHot != null)
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
				@endif
			</div>	
		</div>
	</div>
</div>