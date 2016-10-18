<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Danh sách NCC và gửi email</li>
        </ul>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div class="panel-body">
                        <div class="form-group col-lg-3">
                            <label for="category_name">Tên</label>
                            <input type="text" class="form-control input-sm" id="supplier_name" name="supplier_name" placeholder="Tên khách hàng" @if(isset($search['supplier_name']) && $search['supplier_name'] != '')value="{{$search['supplier_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="category_name">Email</label>
                            <input type="text" class="form-control input-sm" id="supplier_email" name="supplier_email" placeholder="Tên hiển thị của shop" @if(isset($search['supplier_email']) && $search['supplier_email'] != '')value="{{$search['supplier_email']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="category_name">Phone</label>
                            <input type="text" class="form-control input-sm" id="supplier_phone" name="supplier_phone" placeholder="Tên hiển thị của shop" @if(isset($search['supplier_phone']) && $search['supplier_phone'] != '')value="{{$search['supplier_phone']}}"@endif>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        @if($is_root)
	                    <span class="img_loading" id="img_loading_delete_all"></span>
	                    <a class="btn btn-warning btn-sm" href="javascript:void(0);" onclick="Admin.sendEmailInviteToSupplier();"><i class="fa fa-envelope-o"></i> Gửi Email</a>
	                    @endif
                        @if($is_root)
	                    <a class="btn btn-warning btn-sm" href="javascript:void(0);" onclick="Admin.removeAllItems(6);"><i class="fa fa-trash"></i> Xóa nhiều</a>
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
                    <div class="text-right">
                        {{$paging}}
                    </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT <input type="checkbox" class="check" id="checkAll"></th>
                            <th width="30%">Tên nhà cung cấp</th>
                            <th width="20%">Thông tin khác</th>
                            <th width="10%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center text-middle">{{ $stt + $key+1 }}<br/>
                                	<input class="check" type="checkbox" name="checkItems[]" id="sys_checkItems" value="{{$item->supplier_id}}">
                                </td>
                                <td>{{ $item->supplier_name}}</td>
                                <td>
                                	<b>Email:</b> {{$item->supplier_email}}<br/>
                                	<b>ĐT:</b> {{$item->supplier_phone}}<br/>
                                </td>
                                <td class="text-center text-middle">
                                    @if($is_root || $permission_full ==1 || $permission_delete == 2)
                                       <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->supplier_id}},6)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
                                    <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_{{$item->supplier_id}}">
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
    </div>
</div>
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    $(document).ready(function() {
        $("#checkAll").click(function () {
            $(".check").prop('checked', $(this).prop('checked'));
        });
    });
</script>