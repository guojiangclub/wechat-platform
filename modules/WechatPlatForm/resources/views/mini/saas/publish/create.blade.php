{{--<script>--}}

    {{--var template_list="{{json_encode($template_list)}}";--}}

{{--</script>--}}


<div class="ibox float-e-margins">

    <div class="ibox-content">

        <form action="{{route('admin.mini.saas.publish.store')}}" method="post" id="store">


            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*小程序模板:</label>

                    <div class="col-sm-8">

                        <select name="template_id" id="template_select" class="col-sm-12" style="height: 35px;">
                        <option  data-info="" data-version="" data-description=""    value=-1>请选择模板</option>
                        @if(count($template_list)>0)
                        @foreach($template_list as $item)
                        <option data-info="{{json_encode($item)}}" data-version="{{$item['user_version']}}" data-description="{{$item['user_desc']}}"   value="{{$item['template_id']}}"
                        data-template_id="{{$item['template_id']}}"
                        >模板ID:{{$item['template_id']}}
                        &nbsp&nbsp&nbsp&nbsp&nbsp版本号:{{$item['user_version']}}</option>

                        @endforeach
                        @endif
                        </select>


                    </div>

                </div>

            </div>


            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*名称:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="name" placeholder="" value="{{request('name')}}" />

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*版本号:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="version" placeholder="" value="" />

                    </div>

                </div>

            </div>


            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*审核的页面地址:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="mini_address" placeholder="pages/index/index/index" value="" />

                    </div>

                </div>

            </div>


            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*审核的页面标题:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="mini_title" placeholder="首页" value="" />

                    </div>

                </div>

            </div>


            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*审核的标签:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="mini_tag" placeholder="商城" value="" />

                    </div>

                </div>

            </div>



            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">体验版本:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="trial_version_img" placeholder="" value="" />

                    </div>

                </div>

            </div>


            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">描述:</label>

                    <div class="col-sm-8">

                        <textarea id="description" rows="8" class="col-sm-12" name="description" ></textarea>

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">备注:</label>

                    <div class="col-sm-8">

                        <textarea rows="8" class="col-sm-12" name="note" ></textarea>

                    </div>

                </div>

            </div>




            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <input type="hidden" name="template_info" value="">

            <input type="hidden" name="saas_version_code" value="{{request('code')}}">

            <input type="hidden" name="saas_version_id" value="{{request('saas_version_id')}}">

            <div class="panel-body">

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                </div>

            </div>


        </form>

    </div>
</div>



<script>

    $("#template_select").change(function(){
        var id=$("#template_select").val();
        var info=$("#template_select").find("option:selected").data('info');
        var info_data=JSON.stringify(info);
        $('input[name=template_info]').val(info_data);

        console.log(info_data);

        var version=$("#template_select").find("option:selected").data('version');

        if( $('input[name=version]').val()=='' || $('#description').val()!=version){

            $('input[name=version]').val(version);
        }

        var description=$("#template_select").find("option:selected").data('description');

        if( $('#description').val()=='' || $('#description').val()!=description){

            $('#description').val(description);
        }


    });


    $('#store').ajaxForm({

        beforeSubmit: function (data) {
            var input = [];
            $.each(data, function (k, v) {
                if (v.name !== "lenght") {
                    input[v.name] = v.value;
                }
            })

            console.log(input);

            if (input['template_id'] == -1) {
                swal("保存失败!", '请选择小程序模板', "error")
                return false;
            }


            if (input['name'] == '') {
                swal("保存失败!", '请输入名称', "error")
                return false;
            }

            if (input['version'] == '') {
                swal("保存失败!", '请输入版本号', "error")
                return false;
            }

            if (input['mini_address'] == '') {
                swal("保存失败!", '请输入审核的页面地址', "error")
                return false;
            }

            if (input['mini_title'] == '') {
                swal("保存失败!", '请输入审核的页面标题', "error")
                return false;
            }


            if (input['mini_tag'] == '') {
                swal("保存失败!", '请输入审核的标签', "error")
                return false;
            }


        },

        success: function (result) {
            if (!result.status) {
                swal("保存失败!", result.message, "error")
            } else {
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location = "{{route('admin.mini.saas.publish.index',['code'=>request('code')])}}";
                });
            }

        }
    });



</script>






