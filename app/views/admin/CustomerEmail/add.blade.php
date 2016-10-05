<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.provider_list')}}"> Danh sách Khách hàng gửi mail</a></li>
            <li class="active">@if($id > 0)Cập nhật khách hàng @else Tạo mới khách hàng @endif</li>
        </ul>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                @if(isset($error) && sizeof($error) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Tên khách hàng</label>
                        <input type="text" placeholder="Tên khách hàng" id="customer_full_name" name="customer_full_name" class="form-control input-sm" value="@if(isset($data['customer_full_name'])){{$data['customer_full_name']}}@endif">
                    </div>
                </div>
               
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Email</label>
                        <input type="text" placeholder="Email khách hàng" id="customer_master_email" name="customer_master_email" class="form-control input-sm" value="@if(isset($data['customer_master_email'])){{$data['customer_master_email']}}@endif">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Số điện thoại</label>
                        <input type="text" placeholder="Số điện thoại" id="customer_phone" name="customer_phone" class="form-control input-sm" value="@if(isset($data['customer_phone'])){{$data['customer_phone']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="name" class="control-label">Địa chỉ</label>
                        <input type="text" placeholder="Địa chỉ" id="customer_address" name="customer_address" class="form-control input-sm" value="@if(isset($data['customer_address'])){{$data['customer_address']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-sm-12 text-left">
                	<input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
