<?php
// +----------------------------------------------------------------------
// | Author: Orzice(小涛)
// +----------------------------------------------------------------------
// | 联系我: https://i.orzice.com
// +----------------------------------------------------------------------
// | gitee: https://gitee.com/orzice
// +----------------------------------------------------------------------
// | github: https://github.com/orzice
// +----------------------------------------------------------------------
// | DateTime: 2021-07-06 19:00:54
// +----------------------------------------------------------------------
// 应用公共文件

use think\exception\HttpResponseException;
use app\common\service\AuthService;
use think\facade\Cache;
use think\facade\Config;


if (!function_exists('UserUploadfile')) {
    /**
     *  标记 附件已使用 【用户端专用】
     */
    function UserUploadfile($url)
    {
        \app\admin\model\MemberUploadfile::where("uid",Sessions('id'))->where("state",0)->where("url",$url)->update(["state" => 1]);
        return true;
    }
}
if (!function_exists('UserUploadfileDelete')) {
    /**
     *  清除未使用附件  每一次上传 都会监控未使用的图片 进行删除处理【用户端专用】
     */
    function UserUploadfileDelete()
    {
        $row = \app\admin\model\MemberUploadfile::where('file','<>',"")->where('state',0)->select();
        for ($i=0; $i < count($row); $i++) { 
              if ($row[$i]['file'] == '') {
                continue;
              }
            //进行删除文件操作
             $wjm = root_path() . 'public/' . $row[$i]['file'];
             $wjm = str_replace(DIRECTORY_SEPARATOR, '/', $wjm);
             $wjm = str_replace(DIRECTORY_SEPARATOR, '\/', $wjm);

            if(file_exists($wjm)){
              unlink($wjm);
            }
        }
        //删除数据库记录
        \app\admin\model\SystemUploadfile::where('file','<>',"")->where('state',0)->delete();
        return true;
    }
}


if (!function_exists('http_query')) {
function http_query($url, $post = null)
   {
        // 初始化一个cURL会话
        $ch = curl_init($url);
        if (isset($post)) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        //忽略证书
        if (substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $curl_result = curl_exec($ch);
        if ($curl_result) {
            $data = $curl_result;
        } else {
            $data = curl_error($ch);
        }
        curl_close($ch);    #关闭cURL会话
        return $data;
    }
 }
 
/**
 * 插件请使用这个进行拦截系统操作  0是失败  1是成功！
 * 
 * 建议只在必要的时候再使用此函数，不然可能发生无法预料的问题。
 */
if (!function_exists('api_return')) {

    function api_return($code = 0,$msg = '', $data = '', $url = null, $wait = 2)
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ];
        $response = json($result);
        throw new HttpResponseException($response);
    }
}


if (!function_exists('random')) {
    function random($length, $numeric = FALSE) {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }
}