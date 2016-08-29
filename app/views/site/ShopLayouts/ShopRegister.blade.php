<div class="main-view-post box-register">
	<div class="form-send-register">
		<h1 class="title-head">Đăng ký mở gian hàng <span>/</span> <a href="/dang-nhap.html" class="link-tab" rel="nofollow">Đăng nhập</a></h1>
		@if(isset($error) && sizeof($error))
			@foreach ($error as $key => $msg)
			<span class="red">{{$msg}}</span>
			@endforeach
		@endif
		{{ Form::open(array('class'=>'formSendLogin','method' => 'POST','url' =>"dang-ky.html")) }}
			<div class="form-left-reg">
				<div class="form-group">
					<label class="control-label">Tên đăng nhập<span>(*)</span></label>
					<input id="user_shop" placeholder="Tên đăng nhập"class="form-control" name="user_shop" maxlength="255" value="" type="text">
				</div>
				<div class="form-group">
					<label class="control-label">Mật khẩu<span>(*)</span></label>
					<input id="user_password" placeholder="Mật khẩu"class="form-control" name="user_password" maxlength="255" value="" type="password">
				</div>
				<div class="form-group">
					<label class="control-label">Nhập lại mật khẩu<span>(*)</span></label>
					<input id="rep_user_password" placeholder="Nhập lại mật khẩu" class="form-control" name="rep_user_password" maxlength="255" value="" type="password">
				</div>
			</div>
			<div class="form-right-reg">
				<div class="form-group">
					<label class="control-label">Số điện thoại<span>(*)</span></label>
					<input id="shop_phone" placeholder="Số điện thoại" name="shop_phone" class="form-control" maxlength="255" value="" type="text">
				</div>
				<div class="form-group">
					<label class="control-label">Email<span>(*)</span></label>
					<input id="shop_email" placeholder="Email" name="shop_email" class="form-control" maxlength="255" value="" type="text">
				</div>
				<div class="form-group">
					<label class="control-label">Tỉnh/thành<span>(*)</span></label>
					<select id="shop_province" name="shop_province" class="form-control">
						{{$optionProvince}}
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="agree">
					<input value="true" name="agree" id="agree" type="checkbox">
					<label for="agree" class="checkbox-note">Tôi đã xem và đồng ý với chính sách bảo mật của shopcuatui.com.vn</label>
				</div>
			</div>
			<div class="form-group-action">
				<div class="list-action">
					<input name="txtFormNameRegister" id="txtFormNameRegister" value="txtFormNameRegister" type="hidden">
					<button type="submit" id="submitRegister" class="btn btn-primary">Đăng ký</button>
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>