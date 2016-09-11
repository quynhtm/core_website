<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Quản lý banner quảng cáo</li>
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
                            <label for="banner_name">Tên banner</label>
                            <input type="text" class="form-control input-sm" id="banner_name" name="banner_name" placeholder="Tiêu đề banner" @if(isset($search['banner_name']) && $search['banner_name'] != '')value="{{$search['banner_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="category_status">Trạng thái</label>
                            <select name="banner_status" id="banner_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        @if($is_root || $permission_full ==1 || $permission_create == 1)
                        <span class="">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('admin.banner_edit')}}">
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
                @if($data && sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> item @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT</th>
                            <th width="10%" class="text-center">Ảnh</th>
                            <th width="30%">Tên banner</th>
                            <th width="20%">Thông tin banner</th>
                            <th width="10%" class="text-center">Ngày chạy</th>
                            <th width="10%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center text-middle">{{ $stt + $key+1 }}</td>
                                <td class="text-center text-middle">
                                    <img src="{{ ThumbImg::getImageThumb(CGlobal::FOLDER_BANNER, $item->banner_id, $item->banner_image, CGlobal::sizeImage_100)}}">
                                </td>
                                <td>
                                    [<b>{{ $item->banner_id }}</b>] {{ $item->banner_name }}
                                </td>
                                <td>
                                    <b>Loại: </b>@if(isset($arrTypeBanner[$item->banner_type])){{$arrTypeBanner[$item->banner_type]}}@else ---- @endif
                                    <br/><b>Page: </b>@if(isset($arrPage[$item->banner_page])){{$arrPage[$item->banner_page]}}@else ---- @endif
                                    <br/><b>Shop: </b>@if(isset($arrIsShop[$item->banner_is_shop])){{$arrIsShop[$item->banner_is_shop]}}@else ---- @endif
                                    <br/><b>Danh mục: </b>{{$item->banner_category_id}}

                                    <br/>@if($item->banner_is_rel == 1)Follow @else Nofollow @endif
                                </td>
                                <td class="text-center text-middle">
                                    @if($item->banner_is_run_time == CGlobal::BANNER_IS_RUN_TIME)
                                        S:{{date('d-m-Y h:i:s',$item->banner_start_time)}}
                                        <br/>E:{{date('d-m-Y h:i:s',$item->banner_end_time)}}
                                    @else
                                        Không giới hạn ngày chạy
                                    @endif
                                </td>
                                <td class="text-center text-middle">
                                    @if($item->banner_status  == CGlobal::status_show)
                                        <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @else
                                        <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif

                                    @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                        &nbsp;&nbsp;&nbsp; <a href="{{URL::route('admin.banner_edit',array('id' => $item->banner_id))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_root || $permission_full ==1 || $permission_delete == 1)
                                       &nbsp;&nbsp;&nbsp;
                                       <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->banner_id}},1)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
                                    <span class="img_loading" id="img_loading_{{$item->banner_id}}"></span>
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