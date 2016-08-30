<div class="link-top-head">
    <div class="container">
        <div class="box-login">
            @if(isset($user_shop) && sizeof($user_shop) > 0)
                <a href="{{URL::route('shop.adminShop')}}" rel="nofollow" class="btnLog"><i class="config"></i> Quản lý gian hàng</a>
                <a href="{{URL::route('site.shopLogout')}}" rel="nofollow" class="btnLog"><i class="logout"></i> Thoát</a>
            @else
                <a href="{{URL::route('site.shopRegister')}}" class="btnLog register" rel="nofollow"><i class="register"></i>Mở Shop</a>
                <a href="{{URL::route('site.shopLogin')}}" class="btnLog" rel="nofollow"><i class="login"></i>Vào Shop</a>
            @endif
        </div>
    </div>
</div>
<div class="center-header">
    <div class="container">
        <div class="top-header">
            <h1 id="logo"><a href="{{Config::get('config.WEB_ROOT')}}"><img src="{{Config::get('config.WEB_ROOT')}}assets/frontend/img/logo.png" alt="ShopCuaTui" /></a></h1>
            <div class="box-top-header-right">
                <div class="search-top-center">
                    <div class="box-search">
                        <form name="frmsearch" id="frmsearch" class="frmsearch" method="GET" action="#">
                            <select name="province_id" class="province_id">
                                {{$optionProvince}}
                            </select>
                            <select name="category_id" class="category_id">
                                {{$optionParentCate}}
                            </select>
                            <input type="submit" class="btn-search" value="Tìm kiếm"/>
                        </form>
                    </div>
                </div>
                <div class="box-right-focus">
                    <div class="support-contact">
                        <i class="icon-phone"></i> Hỗ trợ
                        <i class="idrop"></i>

                        <div class="box-hover-support-contact">
                            <div class="top-arrow-box"><i></i></div>
                            <div class="custommer">
                                <b>Dành cho khách hàng:</b> Để mua sản phẩm bạn vui lòng liên hệ theo số điện thoại trong tin đăng của các shop.
                            </div>
                            <div class="support-user-shop">
                                <b>Dành cho chủ shop:</b>
                                <ul>
                                    <li>
                                        <i></i>
                                        CSKH: <b>094.11.99.656</b>
                                    </li>
                                    <li>
                                        <i></i>
                                        Đăng ký quảng cáo: <b>094.11.99.656</b>
                                    </li>
                                    <li>
                                        <i></i>
                                        Hỗ trợ trực tuyến:
                                        <a title="Hỗ trợ trực tuyến qua Skype!" href="skype:nguyenduypt86?chat" class="chat-sky" rel="nofollow"></a>
                                        <a title="Hỗ trợ trực tuyến qua Skype!" href="skype:mercury_0206?chat" class="chat-sky" rel="nofollow"></a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if(isset($numCart) && $numCart > 0)
                    <a href="#" title="Giỏ hàng" rel="nofollow">
                        <div class="shopping-cart">
                            <span class="num-item">{{$numCart}}</span>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="box-header-link">
            <div class="box-menu-title box-menu-hover">
                <div class="title-cat-menu">
                    <div class="icon-cat-title">
                        <span class="ic-line"></span>
                        <span class="ic-line"></span>
                        <span class="ic-line"></span>
                    </div>
                    Danh mục sản phẩm
                </div>
                <?php if(isset($arrCategory) && !empty($arrCategory)){?>
                <div class="content-box-menu header-menu-other">
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
                </div>
                <?php } ?>
            </div>
            <div class="right-ultity">
            	<div class="part1-right-ultity">
	            	<div class="list-product-new">
		                <i class="icon-list-new"></i> <a href="#" title="Sản phẩm mới">Sản phẩm mới</a>
		            </div>
		            <div class="list-giai-tri">
		                <i class="fa fa-bullhorn"></i> <a href="{{URL::route('site.listNew')}}" title="Tin tức">Tin tức</a>
		            </div>
		         </div>
                 <div class="part2-right-ultity">
	                <div class="shop-create">
	                    <i></i>
	                    <b>Tạo shop online:</b><br>
	                    <span>Tiện ích - Đơn giản - Dễ dùng</span>
	                </div>
	                <div class="shop-transfer">
	                    <i></i>
	                    <b>Ship hàng tận nơi:</b><br>
	                    <span>Giao ngay trong ngày</span>
	                </div>
	                <div class="shop-diversity">
	                    <i></i>
	                    <b>Sản phẩm đa dạng:</b><br>
	                    <span>Đủ các mặt hàng gọi là có ngay</span>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>