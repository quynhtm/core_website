<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.provider_list')}}"> Danh sách NCC của shop</a></li>
            <li class="active">@if($id > 0)Cập nhật NCC của shop @else Tạo mới NCC của shop @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
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
                        <label for="name" class="control-label">Tên nhà cung cấp</label>
                        <input type="text" placeholder="Tên nhà cung cấp" id="provider_name" name="provider_name" class="form-control input-sm" value="@if(isset($data['provider_name'])){{$data['provider_name']}}@endif">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Thuộc shop</label>
                        <select name="provider_shop_id" id="provider_shop_id" class="form-control input-sm">
                            {{$optionShop}}
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>


                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Phone nhà cung cấp</label>
                        <input type="text" placeholder="Phone nhà cung cấp" id="provider_phone" name="provider_phone" class="form-control input-sm" value="@if(isset($data['provider_phone'])){{$data['provider_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Email nhà cung cấp</label>
                        <input type="text" placeholder="Email nhà cung cấp" id="provider_email" name="provider_email" class="form-control input-sm" value="@if(isset($data['provider_email'])){{$data['provider_email']}}@endif">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="name" class="control-label">Trạng thái</label>
                        <select name="provider_status" id="provider_status" class="form-control input-sm">
                            {{$optionStatus}}
                        </select>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="name" class="control-label">Địa chi nhà cung cấp</label>
                        <input type="text" placeholder="Địa chi nhà cung cấp" id="provider_address" name="provider_address" class="form-control input-sm" value="@if(isset($data['provider_address'])){{$data['provider_address']}}@endif">
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="name" class="control-label">Ghi chú nhà cung cấp</label>
                        <input type="text" placeholder="Ghi chú nhà cung cấp" id="provider_note" name="provider_note" class="form-control input-sm" value="@if(isset($data['provider_note'])){{$data['provider_note']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-sm-12 text-left">
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                </div>
                {{ Form::close() }}
                <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>
