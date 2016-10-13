<div class="container">
	<div class="main-view-post shopcart">
		<div class="wrap-main-view">
			<div class="view-content-post">
				<div class="pay">
					<div class="left-order-send">
						<div class="content-post-cart">
							<div class="title-pay-cart">Chi tiết đơn hàng</div>
							<table class="list-pay">
					            <tbody>
									@if(sizeof($dataItem) != 0)
									<?php $total = 0; ?>
									@foreach($dataItem as $key=>$item)
									@foreach($dataCart as $k=>$v)
									@if($item->product_id == $k)
									<?php 
							         	if($item->product_price_sell > 0){
							         		$total += (int)$item->product_price_sell * $v;
							         	}
							         ?>
									<tr>
										<td width="10%">
											<a class="img" target="_blank" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}">
												<img alt="{{$item->product_name}}" src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], CGlobal::sizeImage_80)}}">
											</a>
										</td>
										<td>
											<span class="title">{{$item->product_name}}</span>
										</td>
										<td width="20%">
											<span class="price">
												@if($item->product_price_sell > 0)
								          			{{FunctionLib::numberFormat((int)$item->product_price_sell)}}<sup>đ</sup> x {{$v}}
									          	@else
									          		Liên hệ
									          	@endif
								          	</span>
										</td>
									</tr>
									@endif
									@endforeach
									@endforeach
									<tr>
							            <td colspan="2"><b>Tổng số tiền thanh toán:</b></td>
							            <td colspan="1"><b>{{FunctionLib::numberFormat((int)$total)}}</b><sup>đ</sup></td>
							         </tr>
							         @endif
								</tbody>
							</table>
					    </div>
					</div>
					<div class="right-order-send">
						<div class="content-post-cart">
							<div class="title-pay-cart">Địa chỉ giao hàng</div>
							{{Form::open(array('method' => 'POST', 'id'=>'txtFormPaymentCart', 'class'=>'txtFormPaymentCart', 'name'=>'txtFormPaymentCart'))}}
								<div class="form-group">
									<label class="control-label">Số điện thoại<span>(*)</span></label>
									<input id="txtMobile" name="txtMobile" class="form-control" maxlength="255" type="text"><span class="loadInfoPhone" title="Lấy Thông tin"><i class="fa fa-refresh"></i></span>
								</div>
								<div class="form-group">
									<label>Họ và tên<span>(*)</span></label>
									<input id="txtName" class="form-control" name="txtName" maxlength="255" type="text">
								</div>
								<div class="form-group">
									<label class="control-label">Email</label>
									<input id="txtEmail" name="txtEmail" class="form-control" maxlength="255" type="text">
								</div>
								<div class="form-group">
									<label class="control-label">Địa chỉ<span>(*)</span></label>
									<input id="txtAddress" name="txtAddress" class="form-control" maxlength="255" type="text">
								</div>
								<div class="form-group">
									<label>Ghi chú</label>
									<textarea id="txtMessage" class="form-control" rows="5" name="txtMessage" maxlength="1000"></textarea>
									<span class="des">VD: thời gian nhận hàng...</span>
								</div>
								<button type="submit" id="submitPaymentOrder" class="btndefault btn btn-primary">Gửi đơn hàng</button>
							{{Form::close()}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>