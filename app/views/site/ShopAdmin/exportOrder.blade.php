<div style="padding-top: 10px;padding-left: 15px;">
   <a href="{{URL::route('site.home')}}"><img style="margin-top:5px; max-height: 30px; height:30px" id="logo" src="{{Config::get('config.WEB_ROOT')}}assets/frontend/img/logo.png" /></a>
</div>
<div style="background: #fff;margin: 0 auto;min-height: 200px;padding: 3% 2%;width: 88%;">
	<b>Thông tin đơn hàng!</b><br/><br/>
	<table width="100%" style="border-collapse:collapse">
		<tr class="first">
			<th style="border:1px solid #c8c8c8; padding:5px; text-align:center; width:10%">STT</th>
			<th style="border:1px solid #c8c8c8; padding:5px; text-align:left; width:60%">Tên sản phẩm</th>
			<th style="border:1px solid #c8c8c8; padding:5px; text-align:center; width:10%">Số lượng</th>
			<th style="border:1px solid #c8c8c8; padding:5px; text-align:right; width:20%">Thành tiền</th>
		</tr>
		@if(isset($data) && sizeof($data) > 0)
		<?php
			$allTotal = 0;
			if($data['order_product_price_sell'] > 0){
				$allTotal += $data['order_quality_buy'] * (int)$data['order_product_price_sell'];
				$total = FunctionLib::numberFormat($data['order_quality_buy'] * (int)$data['order_product_price_sell']).'đ';
			}else{
				$total = 'Liên hệ';
			}
		?>
		<tr>
			<td style="border:1px solid #c8c8c8; padding:5px; text-align:center">1</td>
			<td style="border:1px solid #c8c8c8; padding:5px; text-align:left"><a href="{{FunctionLib::buildLinkDetailProduct($data['order_product_id'], $data['order_product_name'], $data['order_category_name'])}}">{{$data['order_product_name']}}</a></td>
			<td style="border:1px solid #c8c8c8; padding:5px; text-align:center">{{$data['order_quality_buy']}}</td>
			<td style="border:1px solid #c8c8c8; padding:5px; text-align:right">{{$total}}</td>
		</tr>
		<tr>
			<td colspan="3" style="border:1px solid #c8c8c8; padding:5px; text-align:right"><b>Tổng tiền</b></td>
			<td colspan="1" style="border:1px solid #c8c8c8; padding:5px; text-align:right">{{FunctionLib::numberFormat($allTotal)}}đ</td>
		</tr>
	</table><br/><br/>
	<table width="100%">
		<tr>
			<td width="50%">
				<b>Thông tin liên hệ của của Shop:</b><br/><br/>
				Họ và tên: {{$data['user_shop']['shop_name']}}<br/>
				Di động: {{$data['user_shop']['shop_phone']}}<br/>
				Email: {{$data['user_shop']['shop_email']}}<br/>
				Địa chỉ: {{$data['user_shop']['shop_address']}}<br/>
			</td>
			<td width="50%">
				<b>Thông tin liên hệ của của khách hàng:</b><br/><br/>
				Họ và tên: {{$data['order_customer_name']}}<br/>
				Di động: {{$data['order_customer_phone']}}<br/>
				Email: {{$data['order_customer_email']}}<br/>
				Địa chỉ: {{$data['order_customer_address']}}<br/>
				Yêu cầu: {{$data['order_customer_note']}}<br/>
			</td>
		</tr>
	</table>
	@endif
</div>