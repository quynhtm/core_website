<div class="container">
	<div class="link-breadcrumb">
		<a href="http://shopcuatui.com.vn" title="Trang chủ">Trang chủ</a>
		<i class="fa fa-angle-double-right"></i>
		<a href="" title="Tin tức chung">Tin tức chung</a>
	</div>
	<div class="main-view-post">
		<div class="wrapp-content-news">
			<div class="left-news-view">
				<h1 class="title-news">Tin tức chung</h1>
				<div class="list-news-post">
					@if(!empty($dataNew))
						@foreach ($dataNew as $key => $item)
						<div class="item-news">
							@if($item['news_image'] != '')
							<div class="post-img">
								<a title="{{$item['news_title']}}" href="">
									<!--<img alt="{{$item['news_title']}}" src="http://shopcuatui.com.vn/image.php?type_dir=news&amp;id=146&amp;width=400&amp;height=400&amp;image=http://shopcuatui.com.vn/uploads/news/146/10-10-33-12-08-2016-taobien.jpg">-->
									<img alt="{{$item['news_title']}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_NEWS, $item['news_id'], $item['news_image'], 100, 100, '', true, true)}}">
									<div class="post-format">
										<i class="fa fa-file-text"></i>
									</div>
								</a>
							</div>
							@endif
							<div class="post-data">
								<h2 class="post-title"><a href="">{{$item['news_title']}}</a></h2>
								<div class="post-content">{{$item['news_desc_sort']}}</div>
								<div class="redmoreNews"> <a href="">Xem thêm</a></div>
							</div>
						</div>
						@endforeach
					<div class="show-box-paging" style="margin-top:20px; ">
						<div class="showListPage">{{$paging}}</div>
					</div>
					@else
                    <div class="alert">
                        Đang cập nhật dữ liệu...
                    </div>
                	@endif
				</div>
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
			</div>	
		</div>
	</div>
</div>