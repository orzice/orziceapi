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
// | DateTime: 2021-07-08 11:23:00
// +----------------------------------------------------------------------

namespace app\admin\controller\demo;


use app\common\controller\AdminController;
use think\App;

class Welcome extends AdminController
{
    public function welcome1()
    {

        $serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
        $serverinfo .= @ini_get('safe_mode') ? ' Safe Mode' : NULL;
        $serversoft = $_SERVER['SERVER_SOFTWARE'];

        $mysqlinfo = \think\facade\Db::query("select VERSION()");
        $dbversion = $mysqlinfo[0]['VERSION()'];

        $this->assign('serverinfo', $serverinfo);
        $this->assign('serversoft', $serversoft);
        $this->assign('dbversion', $dbversion);
        return $this->fetch();
    }
    public function button()
    {
        return $this->fetch();
    }
    public function layer()
    {
        return $this->fetch();
    }

}
