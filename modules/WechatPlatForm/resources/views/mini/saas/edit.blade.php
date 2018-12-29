
<div class="ibox float-e-margins">

    <div class="ibox-content">

        <form action="{{route('admin.mini.saas.update')}}" method="post" id="store">

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*名称:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="name" placeholder="" value="{{$saas->name}}" />

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*类型:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="type" placeholder="" value="{{$saas->type}}" />

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*code:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="code" placeholder="" value="{{$saas->code}}" />

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">标题:</label>

                    <div class="col-sm-8">

                        <input type="text" class="form-control taginput" name="title" placeholder="" value="{{$saas->title}}" />

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">描述:</label>

                    <div class="col-sm-8">
                        <textarea rows="8" class="col-sm-12" name="description" >{{$saas->description}}</textarea>
                    </div>

                </div>

            </div>


            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="id" value="{{$saas->id}}">

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





    $('#store').ajaxForm({

        beforeSubmit: function (data) {
            var input = [];
            $.each(data, function (k, v) {
                if (v.name !== "lenght") {
                    input[v.name] = v.value;
                }
            })

            if (input['name'] == '') {
                swal("保存失败!", '请输入名称', "error")
                return false;
            }

            if (input['type'] == '') {
                swal("保存失败!", '请输入类型', "error")
                return false;
            }

            if (input['code'] == '') {
                swal("保存失败!", '请输入code', "error")
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
                    location = "{{route('admin.mini.saas.index')}}";
                });
            }

        }
    });



</script>






