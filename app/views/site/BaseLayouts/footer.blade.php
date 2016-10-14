<div class="top-bg-footer">
	<div class="top-footer">
        <div class="container">
	        <div class="right-top-footer">
				<span>Kết nối với chúng tôi:</span>
				<a href="https://plus.google.com/100693074505743994095" rel="nofollow">
					<i class="icon-google-plus"></i>
				</a>
				<a href="https://www.facebook.com/profile.php?id=100012051900214" rel="nofollow">
					<i class="icon-facebook"></i>
				</a>
	       </div>
	     </div>
    </div>
    <div class="container">
        <div class="midd-footer">
            <ul>
                <li><span>Về chúng tôi</span></li>
                @if(isset($news_intro) && !empty($news_intro))
                    @foreach($news_intro as $v)
                        <li><a title="" href="#" target="_blank" rel="nofollow">@if(isset($v->news_title)){{$v->news_title}}@endif</a></li>
                    @endforeach
                @endif
                <li><a rel="nofollow" target="_blank" href="{{URL::route('site.home')}}/tin-tuc/c3/10-gioi-thieu.html" title="Giới thiệu">Giới thiệu</a></li>
				<li><a rel="nofollow" target="_blank" href="{{URL::route('site.home')}}/tin-tuc/c3/11-lien-he.html" title="Liên hệ">Liên hệ</a></li>
				<li><a rel="nofollow" target="_blank" href="{{URL::route('site.home')}}/tin-tuc/c3/12-chinh-sach-bao-mat.html" title="Chính sách bảo mật">Chính sách bảo mật</a></li>
				<li><a rel="nofollow" target="_blank" href="{{URL::route('site.home')}}/tin-tuc/c3/13-huong-dan-dang-ky-tai-khoan.html" title="Hướng dẫn đăng ký tài khoản">Hướng dẫn đăng ký tài khoản</a></li>
            </ul>
            <ul>
                <li><span>Dành cho người mua</span></li>
                @if(isset($news_customer) && !empty($news_customer))
                    @foreach($news_customer as $v)
                        <li><a title="" href="#" target="_blank" rel="nofollow">@if(isset($v->news_title)){{$v->news_title}}@endif</a></li>
                    @endforeach
                @endif
                <li><a rel="nofollow" target="_blank" href="{{URL::route('site.home')}}/tin-tuc/c1/14-huong-dan-mua-hang.html" title="Hướng dẫn mua hàng">Hướng dẫn mua hàng</a></li>
            </ul>
            <ul>
                <li><span>Dành cho người bán</span></li>
                @if(isset($news_intro) && !empty($news_intro))
                    @foreach($news_intro as $v)
                        <li><a title="" href="#" target="_blank" rel="nofollow">@if(isset($v->news_title)){{$v->news_title}}@endif</a></li>
                    @endforeach
                @endif
                <li><a rel="nofollow" target="_blank" href="{{URL::route('site.home')}}/tin-tuc/c2/15-cac-quy-dinh.html" title="Các quy định">Các quy định</a></li>
            </ul>
            <ul>
                <li>
                    <li><span>ShopCuaTui.com.vn</span></li>
                    <div class="address">
                        Địa chỉ: CT2A-Khu đô thị Nghĩa Đô-Cầu Giấy-Hà Nội<br>
                        <span class="phone">ĐT: {{CGlobal::phoneSupport}}</span><br>
                    </div>
                    <div class="note-footer">
                        Chú ý: Shopcuatui.com.vn là một CHỢ ONLINE. Quý khách mua hàng vui lòng liên hệ trực tiếp với Shop đăng sản phẩm ở mục liên hệ để mua hàng.
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="bottom-footer">
    <div class="container">
        © 2015 - {{date('Y', time())}} Shopcuatui.com.vn - Mua sắm online các mặt hàng: thời trang nam, thời trang nữ, thời trang trẻ em, phụ kiện thời trang, đồ gia dụng... 
    </div>
</div>
<a href="#" class="back-to-top"></a>