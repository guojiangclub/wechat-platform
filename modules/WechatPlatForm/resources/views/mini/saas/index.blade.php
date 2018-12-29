<div class="tabs-container">
    <ul class="nav nav-tabs">

        <li class="active">
            <a data-toggle="tab" href="#tab-1" aria-expanded="true">版本列表
            </a>
        </li>
        <a  href="{{route('admin.mini.saas.create')}}" class="btn btn-w-m btn-info pull-right">添加</a>

    </ul>
    <div class="tab-content">

        <div id="tab-1" class="tab-pane active">


            <div class="panel-body">
                <div class="col-sm-10" style="margin-bottom:20px;margin-left:-30px;">

                    <form action="{{route('admin.mini.saas.index')}}" method="get">

                        <div class="col-sm-6">
                            <div class="input-group search_text col-sm-12">
                                <input type="text" name="code" placeholder="code" value="{{request('code')}}" class="form-control">

                            </div>
                        </div>

                        <div class="col-sm-2">
                            <input class="btn btn-info" type="submit" value="查询">
                        </div>

                    </form>

                </div>

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="col-sm-2">名称</th>
                        <th class="col-sm-2">显示标题</th>
                        <th class="col-sm-2">类型</th>
                        <th class="col-sm-2">code</th>
                        <th class="col-sm-2">操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(count($lists)>0)
                        @foreach ($lists as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->title}}</td>
                                <td>{{$item->type}}</td>
                                <td>{{$item->code}}</td>
                                <td>

                                    <a class="btn btn-xs btn-info add"
                                       href="{{route('admin.mini.saas.publish.index',['code'=>$item->code,'saas_version_id'=>$item->id,'name'=>$item->title])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-eye"
                                           title="查看发布记录"></i></a>

                                    <a class="btn btn-xs btn-info add"
                                       href="{{route('admin.mini.saas.publish.create',['code'=>$item->code,'saas_version_id'=>$item->id,'name'=>$item->title])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-send"
                                           title="发布版本"></i></a>

                                    <a class="btn btn-xs btn-info" href="{{route('admin.mini.saas.edit',['id'=>$item->id])}}" >
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-edit"
                                           title="修改"></i>
                                    </a>

                                    <a class="btn btn-xs btn-danger delete"
                                       data-href="{{route('admin.mini.saas.delete',['id'=>$item->id])}}">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-trash"
                                           title="删除"></i></a>
                                </td>


                            </tr>
                        @endforeach
                    @endif


                    </tbody>
                </table>

                <div class="clearfix"></div>

                <tfoot>
                <tr>
                    <td colspan="6" class="footable-visible">
                        {!!$lists->appends(['limit'=>request('limit')])->render()!!}
                    </td>
                </tr>
                </tfoot>
            </div>
        </div>

    </div>
</div>

<script>
    $('.delete').on('click', function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };
        swal({
            title: "确定要删除么?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $.post(postUrl, body, function (result) {
                if (result.status) {
                    swal({
                        title: "删除成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '';
                    });
                } else {
                    swal({
                        title: "删除失败",
                        text: result.message,
                        type: "error"
                    });
                }
            });
        });
    });







</script>



