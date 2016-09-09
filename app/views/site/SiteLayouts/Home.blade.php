<div class="container">
	<div class="line-top">
		<div class="box-menu-list">
			<div class="content-box-menu">
				<?php if(isset($arrCategory) && !empty($arrCategory)){?>
                    <ul>
                        <?php
                        $i=0;
                        foreach($arrCategory as $cat){
                        $i++;
                        if($i<=11){
                        ?>
                        <?php if(isset($cat['category_parent_name']) && $cat['category_parent_name'] != ''){ ?>
                        <li>
                            <a href="{{URL::route('site.listProduct', array('name'=>strtolower(FunctionLib::safe_title($cat['category_parent_name'])),'id'=>$cat['category_id']))}}" title="<?php echo $cat['category_parent_name'] ?>"><?php echo $cat['category_parent_name'] ?></a>
                            <?php if(isset($cat['arrSubCategory']) && !empty($cat['arrSubCategory'])) {?>
                            <?php
                            $url = '';
                            if($cat['category_image_background'] != ''){
                                $url = 'url('.FunctionLib::getThumbImage($cat['category_image_background'],$cat['category_id'],FOLDER_CATEGORY,735,428).') no-repeat bottom right';
                            } ?>
                            <div class="list-subcat" style="background: #fff <?php echo $url ?>">
                                <?php
                                $list_ul = array_chunk($cat['arrSubCategory'], 10);
                                ?>
                                <?php foreach($list_ul as $ul){?>
                                <ul>
                                    <?php foreach($ul as $sub){ ?>
                                    <li><a href="{{URL::route('site.listProduct', array('name'=>strtolower(FunctionLib::safe_title($sub['category_name'])),'id'=>$sub['category_id']))}}" title="<?php echo $sub['category_name'] ?>"><?php echo $sub['category_name'] ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </li>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
			</div>
		</div>
		<div class="slider-box-mid">
			<div id="sliderMid">
				<div class="slide ">
					<a rel="nofollow" title="Hòa nhịp bóng đá, tặng lịch Euro" href="" class="nivo-imageLink">
						<img src="http://sanphamredep.com/uploads/images/ads/11-2015/08-35-08-07-11-2015-1.jpg" alt="Hòa nhịp bóng đá, tặng lịch Euro">
					</a>
				</div>
				<div class="slide ">
					<a rel="nofollow" title="Hòa nhịp bóng đá, tặng lịch Euro" href="" class="nivo-imageLink">
						<img src="http://dev.sanphamredep.com/uploads/thumbs/banner/3/730x428/08-14-38-09-06-2016-5.jpg" alt="Hòa nhịp bóng đá, tặng lịch Euro">
					</a>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#sliderMid').bxSlider({
						slideWidth: 720,
						slideHeight: 428,
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
		<div class="ads-right-mid">
			<div class="item-right-slider">
				<div id="sliderRight1">
					<div class="slide">
						<a rel="nofollow" href="" title="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà" target="_blank">
							<img src="http://dev.sanphamredep.com/uploads/thumbs/banner/22/300x210/08-19-38-24-05-2016-3.jpg" alt="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà">
						</a>
					</div>
					<div class="slide">
						<a rel="nofollow" href="" title="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà" target="_blank">
							<img src="http://shopcuatui.com.vn/uploads/banner/20/09-48-59-01-06-2016-77.jpg" alt="Khuyến mãi bất ngờ từ Luminarc">
						</a>
					</div>
				</div>
			</div>
			<div class="item-right-slider">
				<div id="sliderRight2">
					<div class="slide">
						<a rel="nofollow" href="" title="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà" target="_blank">
							<img src="http://shopcuatui.com.vn/uploads/banner/20/09-48-59-01-06-2016-77.jpg" alt="Khuyến mãi bất ngờ từ Luminarc">
						</a>
					</div>
					<div class="slide">
						<a rel="nofollow" href="" title="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà" target="_blank">
							<img src="http://dev.sanphamredep.com/uploads/thumbs/banner/22/300x210/08-19-38-24-05-2016-3.jpg" alt="Đồng hành cùng Euro 2016 - Sút bóng tích điểm nhận quà">
						</a>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#sliderRight1, #sliderRight2').bxSlider({
						slideWidth: 315,
						slideHeight: 170,
						minSlides: 1,
						maxSlides: 2,
						slideMargin: 10,
						mode: 'fade',
						pager: false,
						auto: true,
					});
				});
			</script>
		</div>
	</div>
	<div class="line-box line-box-cat">
		<div class="cate-box">
			<div class="inner-cate-box hide-text-over">
				<h2 class="parent-cate">
                	<a href="javascript:void(0)">
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
                        <a href="javascript:void(0)" datacatid="{{$key}}" datatype="vip">{{ $val }}</a>
                    </h3>
                     @endif
                    @endforeach
                </div>
                @endif
			</div>
		</div>
		<div class="content-list-item w-home">
			<ul class="data-tab data-tab-one act">
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
			                	<a title="{{$item->user_shop_name}}" class="link-shop" href="{{Config::get('config.WEB_ROOT')}}shop-{{$item->user_shop_id}}/{{FunctionLib::safe_title($item->user_shop_name)}}.html">{{$item->user_shop_name}}</a>
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
	<div class="line-box line-box-cat">
		<div class="cate-box">
			<div class="inner-cate-box hide-text-over">
				<h2 class="parent-cate">
                	<a href="javascript:void(0)">
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
                        <a href="javascript:void(0)" datacatid="{{$key}}" datatype="normal">{{ $val }}</a>
                    </h3>
                     @endif
                    @endforeach
                </div>
                @endif
			</div>
		</div>
		<div class="content-list-item w-home">
			<ul class="data-tab data-tab-one act">
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
						</div>
					</li>
				@endforeach
				@endif
			</ul>
		</div>
	</div>
</div>