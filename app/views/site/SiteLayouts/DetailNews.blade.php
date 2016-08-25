<div class="container">
	<div class="link-breadcrumb">
		<a href="" title="Trang chủ">Trang chủ</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="" title="Tin tức chung">Tin tức chung</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="" title="Thực phẩm tốt cho người ngồi máy tính nhiều">Thực phẩm tốt cho người ngồi máy tính nhiều</a>
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
							<a href="" title="{{$item->news_title}}">{{$item->news_title}}</a>
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
					<div class="fb-like" data-href="http://shopcuatui.com.vn/tin-tuc-chung/t146/Thuc-pham-tot-cho-nguoi-ngoi-may-tinh-nhieu.html" 
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
								<a class="i-thumb post-thumb" title="{{$item->product_name}}" href="">
									<img alt="{{$item->product_name}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item->product_id, $item->product_image, 300, 300, '', true, true)}}">
								</a>
								<a class="item-name" title="{{$item->product_name}}" href="">{{$item->product_name}}</a>
									@if($item->product_price_sell > 0)
									<div class="item-price">
										<p class="price-sale">{{number_format($item->product_price_sell)}}<span>đ</span></p>
									</div>
									@else
									<div class="item-price">
										<p class="price-sale">Liên hệ</p>
									</div>
									@endif
								@if(!empty($user_shop))
								<div class="mgt5 amount-call">
				                	<a title="{{$user_shop['shop_name']}}" class="link-shop" href="">{{$user_shop['shop_name']}}</a>
				            	</div>
				            	@endif
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