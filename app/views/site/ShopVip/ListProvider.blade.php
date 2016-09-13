<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Quản trị shop</a>
            </li>
            <li class="active">Danh sách nhà cung cấp của shop</li>
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
                            <label for="category_name">Tên nhà cung cấp</label>
                            <input type="text" class="form-control input-sm" id="provider_name" name="provider_name" placeholder="Tên đăng nhập" @if(isset($search['provider_name']) && $search['provider_name'] != '')value="{{$search['provider_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="category_name">Email nhà cung cấp</label>
                            <input type="text" class="form-control input-sm" id="shop_name" name="shop_name" placeholder="Tên hiển thị của shop" @if(isset($search['shop_name']) && $search['shop_name'] != '')value="{{$search['shop_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="category_name">Phone nhà cung cấp</label>
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

                            <span class="">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('shop.addProvider')}}">
                                <i class="ace-icon fa fa-plus-circle"></i>
                                Thêm mới
                            </a>
                        </span>

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
                            <th width="5%" class="text-center">STT</th>
                            <th width="30%">Tên NCC</th>
                            <th width="30%">Thông tin NCC</th>
                            <th width="20%" class="text-center">Note của NCC</th>
                            <th width="15%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center">
                                    {{ $stt + $key+1 }}<br/>
                                </td>
                                <td>
                                    [<b>{{ $item->provider_id }}</b>] {{ $item->provider_name }}
                                    <br/>{{date('d-m-Y H:i:s',$item->provider_time_creater)}}
                                </td>
                                <td>
                                    @if($item->provider_phone != '')
                                        {{ $item->provider_phone }}
                                    @endif
                                    @if($item->provider_email != '')
                                        <br/>{{ $item->provider_email }}
                                    @endif
                                    @if($item->provider_address != '')
                                        <br/>{{ $item->provider_address }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->provider_note }}
                                </td>
                                <td class="text-center text-middle">
                                    @if($item->provider_status == CGlobal::status_show)
                                        <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @else
                                        <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif
                                    &nbsp;&nbsp; <a href="{{URL::route('shop.editProvider',array('provider_id' => $item->provider_id,'provider_name' => FunctionLib::safe_title($item->provider_name)))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    &nbsp;&nbsp; <a href="javascript:void(0);" onclick="SITE.deleteProvider({{$item->provider_id}})" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_{{$item->provider_id}}">
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