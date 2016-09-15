<div class="container">
	<div class="main-view-post shopcart">
		<div class="wrap-main-view">
			<div class="view-content-post">
			<h3 class="title-head text-left"><a rel="nofollow" title="Giỏ hàng của bạn" href="">Giỏ hàng của bạn</a></h3>
				@if(sizeof($dataItem) != 0)
				{{Form::open(array('method' => 'POST', 'id'=>'txtFormShopCart', 'class'=>'txtFormShopCart', 'name'=>'txtFormShopCart'))}}
				<div class="grid-shop-cart">
					<table class="list-shop-cart-item" width="100%">
						<tbody>
							<tr class="first">
								<th>STT</th>
								<th>Tên sản phẩm</th>
								<th>Số lượng</th>
								<th>Giá / 1 sản phẩm</th>
								<th>Thành tiền</th>
								<th>Thao tác</th>
							</tr>
							<?php $total = 0; ?>
							@foreach($dataItem as $key => $item)
							@foreach($dataCart as $k=>$v)
							@if($item->product_id == $k)
							<?php 
					         	if($item->product_price_sell > 0){
					         		$total += (int)$item->product_price_sell * $v;
					         	}
					         ?>
							<tr>
								<td>{{$key+1}}</td>
								<td><a target="_blank" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}">{{$item->product_name}}</a></td>
								<td>
									<select name="listCart[{{$item->product_id}}]">
										@for($i=1; $i<=CGlobal::max_num_buy_item_product; $i++)
		                            	<option value="{{$i}}" @if($v == $i)selected="selected"@endif>{{$i}}</option>
		                            	@endfor
									</select>
								</td>
								<td>
									 @if($item->product_price_sell > 0)
						          		{{FunctionLib::numberFormat((int)$item->product_price_sell)}}<sup>đ</sup>
						          	@else
						          		Liên hệ
						          	@endif
								</td>
								<td>@if($item->product_price_sell > 0) {{FunctionLib::numberFormat((int)$item->product_price_sell * $v)}} @else Liên hệ @endif<sup>đ</sup></td>
								<td>
									<a data-id="{{$item->product_id}}" class="delOneItemCart" href="javascript:void(0)">Xóa</a>
								</td>
							</tr>
							@endif
							@endforeach
							@endforeach
							<tr>
					            <td colspan="4"><b>Tổng số tiền thanh toán:</b></td>
					            <td colspan="2"><b>{{FunctionLib::numberFormat((int)$total)}}</b><sup>đ</sup></td>
					         </tr>
						</tbody>
					</table>
				</div>
				{{Form::close()}}
				@else
				<div class="not-product-in-cat">Không có sản phẩm nào trong giỏ hàng...</div>
				@endif
				<div class="list-btn-control">
					<a id="backBuy" class="btndefault btn-primary" href="{{URL::route('site.home')}}">Tiếp tục mua hàng</a>
					@if(sizeof($dataItem) != 0)
					<a id="dellAllCart" class="btndefault btn-primary" data="delAll" href="javascript:void(0)">Xóa toàn bộ đơn hàng</a>
					<a id="sendCart" class="btndefault btn-primary" href="{{URL::route('site.sendCartOrder')}}">Gửi đơn hàng</a>
					@endif
					<div class="page-order-cart">{{$paging}}</div>
				</div>
			</div>
		</div>
	</div>	
</div>