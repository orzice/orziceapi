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
// | DateTime: 2021-07-06 17:12:01
// +----------------------------------------------------------------------
namespace app\admin\middleware;

use app\admin\service\SystemLogService;
use EasyAdmin\tool\CommonTool;

/**
 * 系统操作日志中间件
 * Class SystemLog
 * @package app\admin\middleware
 */
class SystemLog
{

    public function handle($request, \Closure $next)
    {

        if ($request->isAjax()) {
            $method = strtolower($request->method());
            if (in_array($method, ['post', 'put', 'delete'])) {
                if(Sessions("id")){
                    
                    $url = $request->url();
                    $ip = CommonTool::getRealIp();
                    $params = $request->param();
                    if (isset($params['s'])) {
                        unset($params['s']);
                    }
                    $data = [
                        'admin_id'    => Sessions("id"),
                        'admin_name'  => Sessions("username"),
                        'url'         => $url,
                        'method'      => $method,
                        'ip'          => $ip,
                        'content'     => json_encode($params, JSON_UNESCAPED_UNICODE),
                        'useragent'   => $_SERVER['HTTP_USER_AGENT'],
                        'create_time' => time(),
                    ];
                    SystemLogService::instance()->save($data);
                }
            }
        }
        return $next($request);
    }

}