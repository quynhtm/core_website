<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div style=" border: 1px solid #166ead;margin: 0 auto;min-height: 100%;width: 100%; display:inline-block; background:#166ead">
   			<div style="height: 50px;margin: 0 auto;width: 100%; margin-bottom: 2px; display: inline-block; color: #fff;">
  				 <div style="float: left;margin: 0 auto;width: 25%;">
			        <div style="padding-top: 10px;padding-left: 15px;">
			           <a href="{{URL::route('site.home')}}"><img style="margin-top:5px; max-height: 30px; height:30px" id="logo" src="{{Config::get('config.WEB_ROOT')}}assets/frontend/img/logo-mail.png" /></a>
				    </div>
				 </div>
    			<div style="display:inline-block;float:right;color:#fff; line-height:50px;padding-right:20px; font-style: italic;">{{CGlobal::phoneSupport}}</div>
	    	</div>
	    	<div style="background: #fff;margin: 0 auto;min-height: 200px;padding: 3% 2%;width: 88%;">
				Chào: {{$data['user_shop']}}<br/>
				Bạn nhận được email này do khách hàng đã đặt mua sản phẩm từ shop của bạn trên website {{CGlobal::web_name}}<br/><br/>
				<a href="{{URL::route('shop.listOrder')}}">Bấm vào đây để xem đơn hàng.</a><br/><br/>
				<b>Thông tin đơn hàng!</b><br/><br/>

				<table width="100%" style="border-collapse:collapse">
					<tr class="first">
						<th style="border:1px solid #c8c8c8; padding:5px; text-align:center">STT</th>
						<th style="border:1px solid #c8c8c8; padding:5px; text-align:left">Tên sản phẩm</th>
						<th style="border:1px solid #c8c8c8; padding:5px; text-align:center">Số lượng</th>
						<th style="border:1px solid #c8c8c8; padding:5px; text-align:right">Thành tiền</th>
					</tr>
					@if(sizeof($data['items']) != 0)
						@foreach($data['items'] as $k=>$item)
							<?php if($item['order_product_price_sell'] > 0)
								$total = FunctionLib::numberFormat($item['order_quality_buy'] * (int)$item['order_product_price_sell']).'đ';
							else
								$total = 'Liên hệ';
							?>
							<tr>
									<td style="border:1px solid #c8c8c8; padding:5px; text-align:center">{{$k+1}}</td>
									<td style="border:1px solid #c8c8c8; padding:5px; text-align:left"><a href="{{FunctionLib::buildLinkDetailProduct($item['order_product_id'], $item['order_product_name'], $item['order_category_name'])}}">{{$item['order_product_name']}}</a></td>
									<td style="border:1px solid #c8c8c8; padding:5px; text-align:center">{{$item['order_quality_buy']}}</td>
									<td style="border:1px solid #c8c8c8; padding:5px; text-align:right">{{$total}}</td>
								</tr>
						@endforeach
						</table><br/><br/>
						<b>Thông tin khách hàng</b><br/><br/>
						Họ và tên: {{$data['txtName']}}<br/>
						Di động: {{$data['txtMobile']}}<br/>
						Email: {{$data['txtEmail']}}<br/>
						Địa chỉ: {{$data['txtAddress']}}<br/>
						Yêu cầu: {{$data['txtMessage']}}<br/>
					@endif
	    	</div>
	  		<div style="max-height: 34px; height:34px; width: 100%;">
		        <div style="margin: 0 auto;width: 100%;">
		            <span style="color:#fff; padding-right: 15px;float: right; padding-top: 10px;">&copy; <a style="text-decoration: none; color:#fff;" href="{{URL::route('site.home')}}">{{CGlobal::web_name}}</a> 2015-{{date('Y')}}.</span>
		        </div>
    		</div>
	  	</div>
	</body>
</html>
