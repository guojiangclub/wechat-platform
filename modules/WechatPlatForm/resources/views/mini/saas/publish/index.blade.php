
<script>
    function pre(pre,pre_show) {

        var text = document.getElementById(pre).innerText;

        var result = JSON.stringify(JSON.parse(text), null, 2);

        console.log(result);

        document.getElementById(pre_show).innerText= result ;

    }
</script>

<div class="tabs-container">
    <ul class="nav nav-tabs">

        <li class="active">
            <a data-toggle="tab" href="#tab-1" aria-expanded="true">发布记录
            </a>
        </li>
        <a  href="{{route('admin.mini.saas.publish.create',['code'=>request('code'),'saas_version_id'=>request('saas_version_id'),'name'=>request('name')])}}" class="btn btn-w-m btn-info pull-right">发布版本</a>

    </ul>
    <div class="tab-content">

        <div id="tab-1" class="tab-pane active">

            <div class="panel-body">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="col-sm-2">名称</th>
                        <th class="col-sm-2">code</th>
                        <th class="col-sm-2">版本号</th>
                        <th class="col-sm-3">模板详情</th>
                        <th class="col-sm-2">描述</th>
                        <th class="col-sm-2">发布时间</th>
                        <th class="col-sm-1"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(count($lists)>0)
                        @foreach ($lists as $key=> $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->saas_version_code}}</td>
                                <td>{{$item->version}}</td>
                                <td>
                                    <span style="display: none" id="pre{{$item->id}}">{!!$item->template_info!!}</span>
                                    <pre id="pre_show_{{$item->id}}"></pre>
                                    <script>
                                        pre("pre{{$item->id}}","pre_show_{{$item->id}}")
                                    </script>

                                </td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->created_at}}</td>
                                @if($key==0)
                                <td><span class="label label-danger">当前版本</span></td>
                                @endif

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







