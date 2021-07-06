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
// | DateTime: 2021-07-06 17:21:48
// +----------------------------------------------------------------------

namespace app\admin\controller;


use app\common\controller\AdminController;
use think\captcha\facade\Captcha;
use think\facade\Env;

use app\admin\model\PlusUser;
use app\admin\model\SystemAdmin;
/**
 * Class Login
 * @package app\admin\controller
 */
class Login extends AdminController
{

    /**
     * 初始化方法
     */
    public function initialize()
    {
        parent::initialize();
        $action = $this->request->action();
        if (!empty(Sessions("id")) && !in_array($action, ['out'])) {
            $adminModuleName = config_plus("orzice.Admin");
            $this->success('已登录，无需再次登录', [], __url("@{$adminModuleName}"));
        }
    }

    /**
     * 用户登录
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                'username|用户名'      => 'require|alphaNum|length:4,20',
                'password|密码'       => 'require|alphaNum|length:4,20',
                'keep_login|是否保持登录' => 'require|number',
                'captcha|验证码' => 'require|captcha',
            ];
            $this->validate($post, $rule);
            $admin = SystemAdmin::where(['username' => $post['username']])->find();
            if (empty($admin)) {
                $this->error('账号不存在或密码输入有误');
            }
            if (password($post['password']) != $admin->password) {
                $this->error('账号不存在或密码输入有误');
            }
            if ($admin->status == 0) {
                $this->error('账号已被禁用');
            }
            $admin->login_num += 1;
            $admin->save();
            $admin = $admin->toArray();
            unset($admin['password']);
            $admin['expire_time'] = $post['keep_login'] == 1 ? true : time() + 14400;
            Sessions(null,$admin);
            $this->success('登录成功');
        }

        return $this->fetch();
    }
    /**
     * 用户退出
     * @return mixed
     */
    public function out()
    {
        Sessions(null,null);
        $this->success('退出登录成功');
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        return Captcha::create();
    }
}
