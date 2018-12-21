<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Wechat\Platform\Http\Controllers;

use iBrand\Wechat\Platform\Models\Clients;
use iBrand\Wechat\Platform\Services\PlatformService;
use iBrand\Wechat\Platform\Repositories\AuthorizerApplicationRepository;

/**
 * Class PlatformController.
 */
class PlatformController extends Controller
{
    protected $platformService;

    protected $authorizerApplicationRepository;

    public function __construct(
        PlatformService $platformService
        , AuthorizerApplicationRepository $authorizerApplicationRepository
    ) {
        $this->platformService = $platformService;
        $this->authorizerApplicationRepository=$authorizerApplicationRepository;
    }

    /**
     * 微信公众号授权页.
     *
     * @return mixed
     */
    public function auth()
    {
        $clientId = request('client_id');

        //$redirectUrl = request('redirect_url');

        $authCode = request('authCode');

        $callback = route('component.auth.result', ['client_id' => $clientId]);

        $url = $this->platformService->authRedirectUrl($callback);

        if (!strstr($url, 'auth_type')) {
            $url .= '&auth_type=1';
        }

        return view('wechat-platform::platform.auth', ['redirect_url' => $url]);
    }

    /**
     * 小程序授权页.
     *
     * @return mixed
     */
    public function authMini()
    {
        $clientId = request('client_id');

        $authCode = request('authCode');

        $application_id=request('application_id');

        $application_type=request('application_type');

        $call_back_url=request('call_back_url');


        $callback_data=['client_id' => $clientId,'application_id'=>$application_id,'application_type'=>$application_type,'call_back_url'=>$call_back_url];

        $callback = route('component.auth.result', $callback_data);

        $url = $this->platformService->authRedirectUrl($callback);

        if (!strstr($url, 'auth_type')) {
            $url .= '&auth_type=2';
        }

        return view('wechat-platform::platform.auth', ['redirect_url' => $url]);
    }

    /**
     * 保存授权信息.
     *
     * @return string
     *
     * @internal param Request $request
     */
    public function authResult()
    {

        $auth_code = request('auth_code');
        $authorizer = $this->platformService->saveAuthorization($auth_code);
        if ($clientId = request('client_id')) {
            $authorizer->client_id = $clientId;
            $authorizer->save();

        }

        if($url=request('call_back_url')
            AND $application_type=request('application_type')
            AND $application_id=request('application_id')){


            $authorizerApplication=$this->authorizerApplicationRepository->firstOrCreate(['appid'=>$authorizer->appid]);

            if($authorizerApplication->application_type AND $authorizerApplication->application_type!=$application_type){
                return $authorizer->appid.'已绑定其他应用，授权失败！';
            }

            $authorizerApplication->application_type=$application_type;

            $authorizerApplication->application_id=$application_id;

            $authorizerApplication->saas_version_code='ibrand_saas_'.$application_type.'_v1';

            $uuid=Hashids_encode($authorizerApplication->id,'ibrand_saas');

            $authorizerApplication->uuid=$uuid;

            $authorizerApplication->save();

            $url=$url.'?authorizer_id='.$authorizerApplication->id
                .'&application_type='.$application_type
                .'&application_id='.$application_id
                .'&appid='.$authorizer->appid
                .'&uuid='.$uuid;

            return redirect($url);
        }

        return '授权成功！';
    }

    /**
     * getToken.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken()
    {

        $clientId = request('client_id');

        $client_secret = request('client_secret');

        $client = Clients::where('id', $clientId)->where('secret', $client_secret)->first();

        if (!$client) {
            return response()
            ->json(['token_type' => 'Bearer', 'access_token' => '']);
        }

        $token = $client->createToken($client->secret)->accessToken;

        return response()
            ->json(['token_type' => 'Bearer', 'access_token' => $token, 'expires_in' => 864000]);
    }
}
