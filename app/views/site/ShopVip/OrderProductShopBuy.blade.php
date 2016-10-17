@if($inforShopCart && sizeof($inforShopCart) > 0)
    <h3>Thông tin đặt mua hàng</h3>
    <br>
    <div class="form-group col-lg-3">
        <div class="form-group">
            <label for="category_name">Số điện thoại <span class="red"> (*) </span></label>
            <input type="text" class="form-control input-sm" id="customer_shop_phone" name="customer_shop_phone" placeholder="Số điện thoại khách mua hàng" value="@if(isset($inforCustomer['customer_shop_phone'])){{$inforCustomer['customer_shop_phone']}}@endif">
        </div>
        <div class="form-group">
            <label for="category_name">Tên khách hàng <span class="red"> (*) </span></label>
            <input type="text" class="form-control input-sm" id="customer_shop_full_name" name="customer_shop_full_name" placeholder="Tên khách mua hàng" value="@if(isset($inforCustomer['customer_shop_full_name'])){{$inforCustomer['customer_shop_full_name']}}@endif">
        </div>
        <div class="form-group">
            <label for="category_name">Email khách hàng <span class="red"> (*) </span></label>
            <input type="text" class="form-control input-sm" id="customer_shop_email" name="customer_shop_email" placeholder="Email khách mua hàng" value="@if(isset($inforCustomer['customer_shop_email'])){{$inforCustomer['customer_shop_email']}}@endif">
        </div>
        <div class="form-group">
            <label for="category_name">Địa chỉ khách hàng <span class="red"> (*) </span></label>
            <textarea rows="4" cols="8" name="customer_shop_address" class="form-control input-sm">@if(isset($inforCustomer['customer_shop_address'])){{$inforCustomer['customer_shop_address']}}@endif</textarea>
        </div>
    </div>
    <div class="form-group col-lg-9">
        @if(sizeof($inforShopCart) > 0)
            <table class="table table-bordered table-hover">
                <thead class="thin-border-bottom">
                <tr class="">
                    <th width="5%" class="text-center">STT</th>
                    <th width="10%" class="text-center">Ảnh</th>
                    <th width="40%">Tên sản phẩm</th>
                    <th width="14%">Giá bán</th>
                    <th width="10%" class="text-center">Số lượng</th>
                    <th width="15%" class="text-center">Tổng tiền</th>
                    <th width="6%" class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                <?php $totalMoney = 0;?>
                @foreach ($inforShopCart as $key => $item)
                    <tr>
                        <td class="text-center text-middle">{{ $key+1 }}</td>
                        <td class="text-center text-middle">
                            <img src="{{ ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $item['product_id'], $item['product_image'], CGlobal::sizeImage_100)}}">
                        </td>
                        <td>
                            @if($item['product_status'] == CGlobal::status_show && $item['is_block'] == CGlobal::PRODUCT_NOT_BLOCK)
                                <a href="{{FunctionLib::buildLinkDetailProduct($item['product_id'], $item['product_name'], $item['category_name'])}}" target="_blank" title="Chi tiết sản phẩm">
                                    [<b>{{ $item['product_id']}}</b>] {{ $item['product_name']}}
                                </a>
                            @else
                                [<b>{{ $item['product_id']}}</b>] {{ $item['product_name']}}
                            @endif
                        </td>
                        <td>
                            @if($item['product_type_price'] == CGlobal::TYPE_PRICE_CONTACT)
                                Giá bán: <b class="red"> Liên hệ </b>
                            @else
                                <b>{{ FunctionLib::numberFormat($item['product_price_sell']) }} đ</b>
                            @endif
                        </td>
                        <td class="text-center">
                            <select name="listCart[{{$item['product_id']}}]">
                                @for($i=1; $i<=CGlobal::max_num_buy_item_product; $i++)
                                    <option value="{{$i}}" @if($item['number_buy'] == $i)selected="selected"@endif>{{$i}}</option>
                                @endfor
                            </select>
                        </td>
                        <td class="text-right">
                            <?php
                                $totalMoney = $totalMoney + ($item['product_price_sell']*$item['number_buy']);
                            ?>
                            <b class="red">{{ FunctionLib::numberFormat($item['product_price_sell']*$item['number_buy']) }} đ</b>
                        </td>
                        <td class="text-center">
                            <a data-id="{{$item['product_id']}}" class="delOneItemCart" href="javascript:void(0)"><i class="fa fa-trash fa-2x"></i></a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right" colspan="5"><b>Tổng tiền thanh toán</b></td>
                    <td class="text-right"><b class="red" style="font-size: 14px">{{ FunctionLib::numberFormat($totalMoney) }} đ</b></td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="7">
                        <a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="orderShop.orderBuyShopCart();">
                            <i class="fa fa-search"></i> Bán hàng
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        @endif
    </div>
@else
    <div class="alert">
        Không có dữ liệu
    </div>
@endif