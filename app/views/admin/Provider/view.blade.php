<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Danh sách Shop</li>
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
                            <label for="category_name">Tên đăng nhập</label>
                            <input type="text" class="form-control input-sm" id="user_shop" name="user_shop" placeholder="Tên đăng nhập" @if(isset($search['user_shop']) && $search['user_shop'] != '')value="{{$search['user_shop']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="category_name">Tên shop</label>
                            <input type="text" class="form-control input-sm" id="shop_name" name="shop_name" placeholder="Tên hiển thị của shop" @if(isset($search['shop_name']) && $search['shop_name'] != '')value="{{$search['shop_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="shop_status">Trạng thái</label>
                            <select name="shop_status" id="shop_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        @if($is_root || $permission_full ==1 || $permission_create == 1)
                        <span class="">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('admin.userShop_edit')}}">
                                <i class="ace-icon fa fa-plus-circle"></i>
                                Thêm mới
                            </a>
                        </span>
                        @endif
                        <span class="">
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </span>
                    </div>
                    {{ Form::close() }}
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> danh mục @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT <input type="checkbox" class="check" id="checkAll"></th>
                            <th width="35%">Thông tin shop</th>
                            <th width="15%">Địa chỉ</th>
                            <th width="10%" class="text-center">Loại gian hàng</th>
                            <th width="8%" class="text-center">Online</th>
                            <th width="8%" class="text-center">Ngày tạo</th>
                            <th width="15%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center">
                                    {{ $stt + $key+1 }}<br/>
                                    <input class="check" type="checkbox" name="checkItems[]" id="sys_checkItems" value="{{$item->shop_id}}">
                                </td>
                                <td>
                                    [<b>{{ $item->shop_id }}</b>] {{ $item->user_shop }}
                                    @if($item->shop_name != '')
                                        <br/>{{ $item->shop_name }}
                                    @endif
                                    @if($item->user_shop != '')
                                        <br/>{{ $item->shop_phone }}
                                    @endif
                                    @if($item->shop_email != '')
                                        <br/>{{ $item->shop_email }}
                                    @endif
                                </td>
                                <td>{{ $item->shop_address }}</td>
                                <td class="text-center">
                                    @if(isset($arrIsShop[$item->is_shop])){{ $arrIsShop[$item->is_shop] }}@else --- @endif
                                    @if($item->is_shop != CGlobal::SHOP_VIP)
                                        <br/>Limit up:{{ $item->number_limit_product }}
                                        <br/>∑ up:{{ $item->total_product_up }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->is_login == CGlobal::SHOP_ONLINE)
                                        <i class="fa fa-smile-o fa-2x green"></i>
                                        <br/>{{date('H:i:s d-m-Y',$item->shop_time_login)}}
                                    @else
                                        <i class="fa fa-meh-o fa-2x red"></i>
                                        <br/>{{date('H:i:s d-m-Y',$item->shop_time_logout)}}
                                    @endif
                                </td>
                                <td class="text-center">{{date('H:i:s d-m-Y',$item->shop_created)}}</td>

                                <td class="text-center text-middle">
                                    @if($item->shop_status == 1)
                                        <a href="javascript:void(0);" onclick="Admin.updateStatusItem({{$item->shop_id}},{{$item->shop_status}},2)"title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @endif
                                    @if($item->shop_status == 0)
                                        <a href="javascript:void(0);" onclick="Admin.updateStatusItem({{$item->shop_id}},{{$item->shop_status}},2)"style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif
                                    @if($item->shop_status == -2)
                                        <a href="javascript:void(0);" style="color: red" title="Khóa"><i class="fa fa-close fa-2x"></i></a>
                                    @endif

                                    @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                        <a href="{{URL::route('admin.userShop_edit',array('id' => $item->shop_id))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_root || $permission_full ==1 || $permission_delete == 2)
                                       <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->shop_id}},2)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
                                    <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_{{$item->shop_id}}">
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
                            <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    $(document).ready(function() {
        $("#checkAll").click(function () {
            $(".check").prop('checked', $(this).prop('checked'));
        });
    });
</script>