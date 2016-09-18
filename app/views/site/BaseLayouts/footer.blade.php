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
                <li><a rel="nofollow" target="_blank" href="" title="">Giới thiệu</a></li>
				<li><a rel="nofollow" target="_blank" href="" title="">Liên hệ</a></li>
				<li><a rel="nofollow" target="_blank" href="" title="">Chính sách bảo mật</a></li>
				<li><a rel="nofollow" target="_blank" href="" title="">Hướng dẫn đăng ký tài khoản</a></li>
            </ul>
            <ul>
                <li><span>Dành cho người mua</span></li>
                @if(isset($news_customer) && !empty($news_customer))
                    @foreach($news_customer as $v)
                        <li><a title="" href="#" target="_blank" rel="nofollow">@if(isset($v->news_title)){{$v->news_title}}@endif</a></li>
                    @endforeach
                @endif
                <li><a rel="nofollow" target="_blank" href="" title="">Hướng dẫn mua hàng</a></li>
            </ul>
            <ul>
                <li><span>Dành cho người bán</span></li>
                @if(isset($news_intro) && !empty($news_intro))
                    @foreach($news_intro as $v)
                        <li><a title="" href="#" target="_blank" rel="nofollow">@if(isset($v->news_title)){{$v->news_title}}@endif</a></li>
                    @endforeach
                @endif
                <li><a rel="nofollow" target="_blank" href="" title="">Các quy định</a></li>
            </ul>
            <ul>
                <li>
                    <li><span>ShopCuaTui.com.vn</span></li>
                    <div class="address">
                        Địa chỉ: Tòa nhà F4 Trung Kính - Cầu Giấy - Hà Nội<br>
                        <span class="phone">ĐT: 0985.10.10.26 - 0913.922.986</span><br>
                    </div>
                    <div class="note-footer">
                        Chú ý: Shopcuatui.com.vn không bán hàng trực tiếp, quý khách mua hàng xin vui lòng liên lạc với shop.
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