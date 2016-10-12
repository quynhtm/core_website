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
				<p>{{$data['textMail']}}</p>
				@if(sizeof($data['listProduct']) > 0)
				<ul style="clear:both;display:inline-block;width:100%;margin-top:20px;margin-bottom:20px;">
					@foreach($data['listProduct'] as $item)
					<li style="width:25%;display:inline-block;margin-right:10px; height:295px;vertical-align: top;margin-bottom:5px;">
						<div style="height:200px;width:100%; overflow: hidden;">
							<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}">
								<img style="width:100%" alt="{{$item->product_name}}" src="{{ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], CGlobal::sizeImage_300)}}">
							</a>
						</div>
						<div style="height:40px;width:100%;margin-top:5px;overflow: hidden;clear:both">
							<a title="{{$item->product_name}}" href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}">{{$item->product_name}}</a>
						</div>
						<div style="height:40px;width:100%;margin-top:5px;overflow: hidden;clear:both;line-height: 40px;">
							@if($item->product_type_price == 1)
								@if($item->product_price_sell > 0)
								<span style="float:left;color: #c63829;font-size: 18px;font-weight: 400; margin-right: 5px;">{{FunctionLib::numberFormat($item->product_price_sell)}}đ</span>
								@endif
								@if($item->product_price_market > 0)
								<span  style="float:right;color: #666;position: relative;text-decoration: line-through;">{{FunctionLib::numberFormat($item->product_price_market)}}đ</span>
								@endif
							@else
								<span style="float:left;color: #c63829;font-size: 18px;font-weight: 400; margin-right: 5px;">Liên hệ</span>
							@endif
						</div>
					</li>
					@endforeach
				</ul>
				@endif
	    	</div>
	  		<div style="max-height: 34px; height:34px; width: 100%;">
		        <div style="margin: 0 auto;width: 100%;">
		            <span style="color:#fff; padding-right: 15px;float: right; padding-top: 10px;">&copy; <a style="text-decoration: none; color:#fff;" href="{{URL::route('site.home')}}">{{ucwords(CGlobal::web_name)}}</a>, 2015-{{date('Y', time())}}.</span>
		        </div>
    		</div>
	  	</div>
	</body>
</html>
