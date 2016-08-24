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
					<form action="" method="GET">
						<input name="keyword" class="keyword-news" type="text">
						<input name="catalias" value="tin-tuc-chung" type="hidden">
						<button value="s" name="submit" class="btn btn-primary">Tìm kiếm</button>
					</form>
				</div>
				<div class="content-right-product">
				</div>
				@if($dataFieldProductHot != null)
				<div class="right-bottom-content-view">
					<div class="title-hot"><span>Sản phẩm nổi bật</span></div>
					<div class="content-right-bottom-content-view">
						<ul>
							<li class="item">
								<a class="i-thumb post-thumb" title="Cell SP chống viêm, tăng sức đề kháng - Shopcuatui.com.vn" href="">
									<img src="http://shopcuatui.com.vn/image.php?type_dir=product&amp;id=701&amp;width=300&amp;height=300&amp;image=http://shopcuatui.com.vn/uploads/product/701/11-52-32-20-08-2016-cell-sp.jpg" alt="Cell SP chống viêm, tăng sức đề kháng - Shopcuatui.com.vn" data-other-src="http://shopcuatui.com.vn/image.php?type_dir=product&amp;id=701&amp;width=300&amp;height=300&amp;image=http://shopcuatui.com.vn/uploads/product/701/11-52-32-20-08-2016-cell-sp.jpg">
								</a>
								
								<a class="item-name" title="Cell SP chống viêm, tăng sức đề kháng - Shopcuatui.com.vn" href="http://shopcuatui.com.vn/san-pham/p701/Cell-SP-chong-viem-tang-suc-de-khang.html">Cell SP chống viêm, tăng sức đề kháng</a>
									<div class="item-price">
										<p class="price-sale">300,000<span>đ</span></p>
									</div>
								<div class="mgt5 amount-call">
				                	<a title="EnMax 98" class="link-shop" href="">EnMax 98</a>
				            	</div>
							</li>
						</ul>
					</div>
				</div>
				@endif
			</div>	
		</div>
	</div>
</div>