<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Wechat\Platform\Http\Controllers\MiniProgram;

use iBrand\Wechat\Platform\Http\Controllers\Controller;
use iBrand\Wechat\Platform\Services\PlatformService;
use iBrand\Wechat\Platform\Models\AuthorizerApplication;
use iBrand\Wechat\Platform\Models\SaasVersionPublish;
use iBrand\Wechat\Platform\Services\DomainService;
use iBrand\Wechat\Platform\Repositories\ThemeTemplateRepository;
use iBrand\Wechat\Platform\Repositories\TesterRepository;
use iBrand\Wechat\Platform\Services\CodeService;
use iBrand\Wechat\Platform\Models\CodePublish;
use iBrand\Wechat\Platform\Repositories\CodePublishRepository;
use Carbon\Carbon;

/**
 * Class SaasController
 * @package iBrand\Wechat\Platform\Http\Controllers\MiniProgram
 */
class SaasController extends Controller
{
    protected $platform;

    protected $domainService;

    protected $themeTemplateRepository;

    protected $testerRepository;

    protected $codeService;

    public function __construct(

        PlatformService $platformService

        ,DomainService $domainService

        ,ThemeTemplateRepository $themeTemplateRepository

        ,TesterRepository $testerRepository

        , CodeService $codeService

        ,CodePublishRepository $codePublishRepository

    )
    {
        $this->platform = $platformService;

        $this->domainService = $domainService;

        $this->themeTemplateRepository=$themeTemplateRepository;

        $this->testerRepository=$testerRepository;

        $this->codeService=$codeService;

        $this->codePublishRepository=$codePublishRepository;
    }

    /**
     * 获取saas版本信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVersion()
    {

        $version=null;

        $appid = request('appid');

        $application = AuthorizerApplication::where('appid', $appid)->first();

        if ($application AND $application->saas_version_code) {

            $version = SaasVersionPublish::where('saas_version_code', $application->saas_version_code)->orderBy('id', 'desc')->first();

        }

        if(!$version){

            return $this->api([],false,400,'获取版本信息失败');
        }


        $data = [
            'version'=>$version,
            'domain'=>'',
        ];

        //获取小程序服务器域名
        $domain = $this->domainService->action($appid);

        if (null == $domain || (isset($domain['errcode']) and 0 != $domain['errcode'])) {

            $data['domain']='获取微信小程序服务器域名失败';

            return $this->api($data);

        }

        $local = $this->domainService->local();

        if (count($local)==0) {
            $data['domain']='获取微信第三方后台小程序服务器域名失败';
            return $this->api($data);
        }

        $filter = $this->domainService->filterDomain($domain, $local);

        if (count($filter) > 0) {
            //修改服务器域名覆盖
            $filter['action'] = 'set';

            $send_domain = $this->domainService->action($appid, $filter);

            if (null == $send_domain || (isset($send_domain['errcode']) and 0 != $send_domain['errcode'])) {

                $data['domain']='修改微信小程序服务器域名失败';

                return $this->api($data);
            }
        }

        //获取模板主题
        $theme = $this->themeTemplateRepository->getThemeItemByTemplateID($version->template_id);

        //获取体验者微信
        $testers = $this->testerRepository->getListByAppId($appid);

        $data['testers']=$testers;

        //获取授权小程序帐号的可选类目
        $category = $this->codeService->getCategory($appid);

        $data['category']=$category;

        $audit = $this->codeService->getAppAuditStatus($appid);

        //审核状态查询
        $status_message = '';

        if ($audit and ibrand_count($audit) > 0) {

            switch ($audit->status) {

                case CodePublish::AUDIT_STATUS:
                    $status_message = '待审核版本';
                    break;

                case  CodePublish::SUCCESS_STATUS  :
                    $status_message = '待发布版本';
                    break;
                case  CodePublish::ERROR_STATUS  :
                    $status_message = '审核失败';
                    break;

                default :
                    $audit = [];
                    $status_message = '';
            }

        }

        if ($status_message) {

            $system_mini_template = collect(json_decode($audit->template))->toArray();

            $system_mini_template['type'] = 'audit';

            $theme = $this->themeTemplateRepository->getThemeItemByTemplateID($system_mini_template['template_id']);

        }


        $data['audit']=$audit;

        $data['theme']=$theme;

        $data['status_message']=$status_message;

        $data['appid']=$appid;

//        审核状态，其中0为审核成功，1为审核失败，2为审核中 3已发布 4撤回审核

        $publish=$this->codePublishRepository->getVersionByAppID($appid,[3],$limit=null);

        $data['publish']=$publish;

        return $this->api($data);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function testerBind()
    {
        $appid = request('appid');

        $data = request()->json()->all();

        $wechatid=$data['wechatid'];

        $server = $this->platform->getAccount($appid);

        if (null == $server) {
            return $this->api([], false, 400, '该小程序不存在');
        }

        $info = $this->testerRepository->getTesterByWechatId($appid, $wechatid);

        if ($info) {
            return $this->api([], false, 400, '体验者已经存在');
        }

        $res = $server->tester->bind($wechatid);

        if ((isset($res['errcode']) and 85004 == $res['errcode']) || 0 == $res['errcode']) {
            $tester=$this->testerRepository->ensureTester($appid, $wechatid);

            return $this->api($tester, true);
        }

        return $this->admin_wechat_api($res);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function testerunBind()
    {
        $appid = request('appid');

        $data = request()->json()->all();

        $id=$data['id'];

        $server = $this->platform->getAccount($appid);

        if (null == $server) {
            return $this->api([], false, 400, '该小程序不存在');
        }

        $res = $server->tester->unbind($id);

        if (isset($res['errcode']) and 0 == $res['errcode']) {
            $this->testerRepository->deleteWhere(['appid' => $appid, 'wechatid' => $id]);

            return $this->api([], true);
        }

        return $this->admin_wechat_api($res);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function themeModel(){

        $template_id = request('template_id');

        //获取模板主题
        $theme = $this->themeTemplateRepository->getThemeItemByTemplateID($template_id);

        return $this->api($theme, true);

    }

    public function submitAudit()
    {
        // 参数
        $new_data = [];

        $appid = request('appid');

        $data = request()->json()->all();

        $new_data[0] = $data['item_list'];
        //授权
        $server = $this->platform->getAccount($appid);

        //调用接口

        $result = $server->code->submitAudit($new_data);

        if (isset($result['auditid'])) {

            $data['log']['template'] = json_encode($data['log']['template'], true);

            $data['log']['theme'] = isset($data['log']['theme']) ? json_encode($data['log']['theme'], true) : '';

            $data['log']['category'] = isset($data['item_list']) ? json_encode($data['item_list'], true) : '';

            $data['log']['bars'] = isset($data['log']['bars']) ? json_encode($data['log']['bars'], true) : '';

            $data['log']['ext_json'] = json_encode($data['log']['ext_json'], true);

            $data['log']['status'] = CodePublish::AUDIT_STATUS;

            $data['log']['auditid'] = $result['auditid'];

            $data['log']['audit_time'] = date('Y-m-d H:i:s', Carbon::now()->timestamp);

            $data['log']['saas_version_publish_id'] = isset($data['log']['saas_version_publish_id']) ? $data['log']['saas_version_publish_id'] : '';

            $this->codePublishRepository->getItemOrCreate($data['log']);
        }

        // 返回JSON
        return $this->admin_wechat_api($result);
    }


    /**
     * 撤回审核.
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function withdrawAudit()
    {
        // 参数
        $appid = request('appid');

        // 授权
        $server = $this->platform->getAccount($appid);

        // 调用接口
        $result = $server->code->withdrawAudit();

        if (isset($result['errcode']) and 0 == $result['errcode']) {

            $item = $this->codePublishRepository->getAuditByAppID($appid);

            if ($item and CodePublish::WITHDRW_STATUS != $item->status) {

                $data['status'] = CodePublish::WITHDRW_STATUS;

                $data['withdraw_audit_time'] = date('Y-m-d H:i:s', Carbon::now()->timestamp);

                $this->codePublishRepository->update($data, $item->id);
            }
        }

        // 返回JSON
        return $this->admin_wechat_api($result);
    }


    /**
     * 取消发布
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function Reexamination(){

        $id=request('id');

        $appid=request('appid');

        $publish=$this->codePublishRepository->find($id);

        if($publish->appid!=$appid){

            return $this->api([], false, 400,'无权限');
        }

        $result=$this->codePublishRepository->update(['status'=>5],$id);

        return $this->admin_wechat_api($result);
    }


    /**
     * 发布已通过审核的小程序.
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function release()
    {
        // 参数
        $appid = request('appid');

        // 授权
        $server = $this->platform->getAccount($appid);

        // 调用接口
        $result = $server->code->release();

//        $result = [
//            "errcode" => 0,
//            "errmsg" => "ok",
//        ];

        if (isset($result['errcode']) and 0 == $result['errcode']) {
            $item = $this->codePublishRepository->getAuditByAppID($appid);

            if ($item and CodePublish::PUBLISH_STATUS != $item->status) {
                $data['status'] = CodePublish::PUBLISH_STATUS;

                $data['release_time'] = date('Y-m-d H:i:s', Carbon::now()->timestamp);

                $this->codePublishRepository->update($data, $item->id);
            }
        }

        // 返回JSON
        return $this->admin_wechat_api($result);
    }



    /**
     * @return Content
     */
    public function sendLog()
    {
        $appid = request('appid');

        $limit = request('limit') ? request('limit') : 20;

        $lists = $this->codePublishRepository->getVersionByAppID($appid,$status=[],true);

        return $this->api($lists);
    }

}
