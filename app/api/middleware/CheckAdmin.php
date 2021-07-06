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
// | DateTime: 2021-07-06 19:01:46
// +----------------------------------------------------------------------

namespace app\api\middleware;

use app\common\service\AuthService;
use think\Request;


/**
 * 检测用户登录和节点权限
 * Class CheckAdmin
 * @package app\api\middleware
 */
class CheckAdmin
{

    use \app\common\traits\JumpApi;

    public function handle(Request $request, \Closure $next)
    {

        $MemberConfig = config('member');
 
        $member_id = Sessions("member_id");
        $currentController = parse_name($request->controller());

        // 插件不需要验证登录
        $name = 'plugin.';
        $info = $request->pathinfo();
        if (substr ($info, 0,strlen($name)) == $name) {
            return $next($request);
        }

        // 其他的验证登录
        if (!in_array($currentController, $MemberConfig['no_login_controller'])) {
            empty($member_id) && $this->error('请先登录账号', 'login','login');
        }

        return $next($request);
    }

}