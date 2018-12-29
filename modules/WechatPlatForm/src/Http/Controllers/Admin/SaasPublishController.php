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
use iBrand\Wechat\Platform\Repositories\SaasVersionPublishRepository;

use iBrand\Wechat\Platform\Services\CodeTemplateService;
/**
 * Class SaasPublishController
 */
class SaasPublishController extends Controller
{


    protected $saasVersionPublishRepository;

    protected $codeTemplateService;

    public function __construct(

         SaasVersionPublishRepository $saasVersionPublishRepository

        ,CodeTemplateService $codeTemplateService

    )
    {
        $this->saasVersionPublishRepository=$saasVersionPublishRepository;

        $this->codeTemplateService=$codeTemplateService;

    }


    /**
     * @return Content
     */
    public function index()
    {

        if(!request('code')){

            return redirect(route('admin.mini.saas.index'));
        }

        $lists = [];

        $limit = request('limit') ? request('limit') : 20;

        $lists = $this->saasVersionPublishRepository->getAll($limit,request('code'));

        return LaravelAdmin::content(function (Content $content) use ($lists) {

            $content->header('SAAS版本发布记录列表');

            $content->breadcrumb(
                ['text' => '小程序管理', 'url' => 'wechat_platform/wechat?type=2', 'no-pjax' => 1],
                ['text' => 'SAAS版本管理', 'url' => 'wechat_platform/mini/saas', 'no-pjax' => 1],
                ['text' => '发布记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => 'SAAS']
            );

            $content->body(view('wechat-platform::mini.saas.publish.index', compact('lists')));
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
                ['text' => 'SAAS版本管理', 'url' => 'wechat_platform/mini/saas', 'no-pjax' => 1],
                ['text' => '发布记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => 'SAAS']
            );

            $content->body(view('wechat-platform::mini.saas.edit',compact('saas')));
        });
    }


    public function create()

    {
        if(!request('code')){

        return redirect(route('admin.mini.saas.index'));
       }
            $template_list =[];

            $template_list_arr = $this->codeTemplateService->getCodeTemplateList();

            if (isset($template_list_arr['template_list'])) {
                $template_list = $template_list_arr['template_list'];
            }

        return LaravelAdmin::content(function (Content $content) use($template_list) {

            $content->header('发布SAAS版本');

            $content->breadcrumb(
                ['text' => '小程序管理', 'url' => 'wechat_platform/wechat?type=2', 'no-pjax' => 1],
                ['text' => 'SAAS版本管理', 'url' => 'wechat_platform/mini/saas', 'no-pjax' => 1],
                ['text' => '发布记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => 'SAAS']
            );

            $content->body(view('wechat-platform::mini.saas.publish.create',compact('template_list')));
        });
    }



    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store()

    {
        $input=request()->except('_token');

        if($this->saasVersionPublishRepository->create($input)){

            return $this->api([], true ,200, '创建成功');
        }

        return $this->api([], false ,400, '创建失败');

    }

//    /**
//     * @param $id
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function delete($id)
//    {
//
//        if ($this->saasVersionPublishRepository->delete($id)) {
//
//            return $this->api([], true);
//        };
//
//        return $this->api([], false, 400, '删除失败');
//    }



}
