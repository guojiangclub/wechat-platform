<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function FilterHttpsAndWss($http)
{
    if ($http) {
        $arr = explode('//', $http);
        $http = $arr[1];
    }

    return $http;
}

function get_host()
{
    $scheme = $_SERVER['HTTPS'] == 'off' ? 'http://' : 'https://';
    $url = $scheme . $_SERVER['HTTP_HOST'];
    return $url;
}

function is_color($str){
   $exp="/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/";

   return preg_match($exp,$str);

}


if (!function_exists('Hashids_encode')) {

    function Hashids_encode($id,$connections='main')
    {
        $salt=config('hashids.connections.'.$connections.'.salt');

        if(!$salt) return null;

        $prefix=config('hashids.connections.'.$connections.'.prefix');

        $code=\Vinkla\Hashids\Facades\Hashids::connection($connections)->encode($id);

        if($prefix){

            return $prefix.$code;
        }

        return $code;

    }
}


if (!function_exists('Hashids_decode')) {

    function Hashids_decode($str,$connections='main')
    {
        $salt=config('hashids.connections.'.$connections.'.salt');

        if(!$salt) return null;

        $prefix=config('hashids.connections.'.$connections.'.prefix');

        if($prefix){

            $str=substr($str,strlen($prefix),strlen($str));
        }

        $decode=\Vinkla\Hashids\Facades\Hashids::connection($connections)->decode($str);

        return isset($decode[0])?$decode[0]:null;

    }
}

function ibrand_count($obj){

    if(is_array($obj)){
        return count($obj);
    }

    if(is_object($obj)){

        return $obj->count();
    }

    if($obj){

        return 1;
    }

    return 0;

}

