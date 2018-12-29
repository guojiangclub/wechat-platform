<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Wechat\Platform\Http\Controllers\Admin;

use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use iBrand\Wechat\Platform\Http\Controllers\Controller;
use iBrand\Wechat\Platform\Repositories\SaasVersionRepository;


/**
 * Class SaasController.
 */
class SaasController extends Controller
{
    protected $saasVersionRepository;

    public function __construct(

        SaasVersionRepository $saasVersionRepository

    )
    {

        $this->saasVersionRepository=$saasVersionRepository;


    }


    /**
     * @return Content
     */
    public function index()
    {

        $lists = [];

        $limit = request('limit') ? request('limit') : 20;

        $lists = $this->saasVersionRepository->getAll($limit,request('code'));

        return LaravelAdmin::content(function (Content $content) use ($lists) {

            $content->header('SAAS版本管理');

            $content->breadcrumb(
                ['text' => '小程序管理', 'url' => 'wechat_platform/wechat?type=2', 'no-pjax' => 1],
                ['text' => 'SAAS版本管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => 'SAAS']
            );

            $content->body(view('wechat-platform::mini.saas.index', compact('lists')));
        });
    }

    /**
     * @return Content
     */
    public function edit($id)

    {
        $saas=$this->saasVersionRepository->findByField('id',$id)->first();

        return LaravelAdmin::content(function (Content $content) use($saas) {

            $content->header('编辑SAAS版本');

            $content->breadcrumb(
                ['text' => '小程序管理', 'url' => 'wechat_platform/wechat?type=2', 'no-pjax' => 1],
                ['text' => 'SAAS版本管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => 'SAAS']
            );

            $content->body(view('wechat-platform::mini.saas.edit',compact('saas')));
        });
    }


    public function create()

    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('创建SAAS版本');

            $content->breadcrumb(
                ['text' => '小程序管理', 'url' => 'wechat_platform/wechat?type=2', 'no-pjax' => 1],
                ['text' => 'SAAS版本管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => 'SAAS']
            );

            $content->body(view('wechat-platform::mini.saas.create'));
        });
    }



    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store()

    {
        $code = request('code');

        $saas_version = $this->saasVersionRepository->findWhere(['code' => $code])->first();

        if ($saas_version) {

            return $this->api([], false, 400, 'code已经存在');

        }

        $input=request()->except('_token');


        if($this->saasVersionRepository->create($input)){

            return $this->api([], true ,200, '创建成功');
        }

        return $this->api([], false ,400, '创建失败');

    }


    public function update()

    {
        $code = request('code');

        $input=request()->except('_token');

        $saas_version = $this->saasVersionRepository->findWhere(['code' => $code])->first();

        if ($saas_version AND $saas_version->id!=$input['id']) {

            return $this->api([], false, 400, 'code已经存在');

        }

        if($this->saasVersionRepository->update($input,$input['id'])){

            return $this->api([], true ,200, '修改成功');
        }

        return $this->api([], false ,400, '修改失败');


    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {

        if ($this->saasVersionRepository->delete($id)) {

            return $this->api([], true);
        };

        return $this->api([], false, 400, '删除失败');
    }



}
