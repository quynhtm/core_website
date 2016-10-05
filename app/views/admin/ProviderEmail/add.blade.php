<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.provider_list')}}"> Danh sách NCC gửi mail</a></li>
            <li class="active">@if($id > 0)Cập nhật NCC @else Tạo mới NCC @endif</li>
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
                        <label for="name" class="control-label">Tên NCC</label>
                        <input type="text" placeholder="Tên NCC" id="provider_name" name="provider_name" class="form-control input-sm" value="@if(isset($data['provider_name'])){{$data['provider_name']}}@endif">
                    </div>
                </div>
               
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Email</label>
                        <input type="text" placeholder="Email NCC" id="provider_email" name="provider_email" class="form-control input-sm" value="@if(isset($data['provider_email'])){{$data['provider_email']}}@endif">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Số điện thoại</label>
                        <input type="text" placeholder="Số điện thoại" id="provider_phone" name="provider_phone" class="form-control input-sm" value="@if(isset($data['provider_phone'])){{$data['provider_phone']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="name" class="control-label">Địa chỉ</label>
                        <input type="text" placeholder="Địa chỉ" id="provider_address" name="provider_address" class="form-control input-sm" value="@if(isset($data['provider_address'])){{$data['provider_address']}}@endif">
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
