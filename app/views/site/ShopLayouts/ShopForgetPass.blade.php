<div class="main-view-post box-forgetpass">
	<div class="form-send-forgetpass">
		<h1 class="title-head">Quên mật khẩu</h1>
		@if(isset($error) && sizeof($error))
			@foreach ($error as $key => $msg)
				<div class="line-error">{{$msg}}</div>
			@endforeach
		@endif
		@if(isset($message) && $message != '')
		<div class="alert-admin alert alert-success">{{$message}}</div>
		@endif
		{{ Form::open(array('class'=>'formForgotPass','method' => 'POST','url' =>"quen-mat-khau.html")) }}
			<div class="restore-pass-text">
				Nhập đầy đủ thông tin để nhận lại mật khẩu mới. Bạn sẽ được hệ thống gửi một thư với các chỉ dẫn để phục hồi mật khẩu.
			</div>
			<div class="form-group">
				<label class="control-label">Tên đăng nhập shop<span>(*)</span></label>
				<input id="user_shop" placeholder="Tên đăng nhập"class="form-control" name="user_shop" maxlength="255" value="@if(isset($data['user_shop'])){{$data['user_shop']}}@endif" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">Email đăng ký shop<span>(*)</span></label>
				<input id="shop_email" placeholder="Email" name="shop_email" class="form-control" maxlength="255" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif" type="text">
			</div>
			<div class="form-group-action">
				<div class="list-action">
					<button type="submit" id="submitForgotPass" class="btn btn-primary">Nhận mật khẩu mới</button>
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>