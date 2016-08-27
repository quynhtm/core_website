<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Home</a>
            </li>
            <li class="active">Đơn hàng</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div class="panel-body">
                        <div class="form-group col-lg-3">
                            <label for="order_product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control input-sm" id="order_product_name" name="order_product_name" placeholder="Tên sản phẩm" @if(isset($search['order_product_name']) && $search['order_product_name'] != '')value="{{$search['order_product_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Trạng thái</label>
                            <select name="order_status" id="order_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <span class="">
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </span>
                    </div>
                    {{ Form::close() }}
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> đơn hàng @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT</th>
                            <th width="25%">Thông tin sản phẩm</th>
                            <th width="30%" class="text-left">Thông tin khách hàng</th>
                            <th width="20%" class="text-left">Ghi chú của khách</th>
                            <th width="10%" class="text-center">Ngày đặt</th>
                            <th width="10%" class="text-center">Tình trạng ĐH</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center">{{ $stt + $key+1 }}</td>
                                <td>
                                    [<b>{{ $item->order_id }}</b>] {{ $item->order_product_name }}
                                    <br/>Giá bán: <b class="red">{{ FunctionLib::numberFormat($item->order_product_price_sell) }} đ</b>
                                    <br/>SL: <b>{{ $item->order_quality_buy }}</b> sản phẩm
                                </td>
                                <td>
                                    @if($item->order_customer_name != '')Tên KH: <b>{{ $item->order_customer_name }}</b><br/>@endif
                                    @if($item->order_customer_phone != '')Phone: {{ $item->order_customer_phone }}<br/>@endif
                                    @if($item->order_customer_email != '')Email: {{ $item->order_customer_email }}<br/>@endif
                                    @if($item->order_customer_address != '')Địa chỉ: {{ $item->order_customer_address }}<br/>@endif
                                </td>
                                <td>
                                    @if($item->order_customer_note != ''){{ $item->order_customer_note }}@endif
                                </td>
                                <td class="text-center">{{ date ('d-m-Y H:i:s',$item->order_time) }}</td>
                                <td class="text-center">
                                    @if(isset($arrStatus[$item->order_status])){{$arrStatus[$item->order_status]}}@else --- @endif
                                    <!--<a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->order_id}},3)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    <span class="img_loading" id="img_loading_{{$item->order_id}}"></span>-->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        {{$paging}}
                    </div>
                @else
                    <div class="alert">
                        Không có dữ liệu
                    </div>
                    @endif
            </div>
        </div>
    </div><!-- /.page-content -->
</div>