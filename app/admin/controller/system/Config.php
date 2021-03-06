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

namespace app\admin\controller\system;


use app\admin\model\SystemConfig;
use app\admin\service\TriggerService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Config
 * @package app\admin\controller\system
 * @ControllerAnnotation(title="系统配置管理")
 */
class Config extends AdminController
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemConfig();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="保存")
     */
    public function save()
    {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        $post = $this->request->post();

        if(env('orzice.is_saas', false)){
            if (isset($post['upload_type'])) {
                if ($post['upload_type'] == 'local') {
                    $post['upload_allow_ext'] = 'gif,jpg,mp3,mp4,pem,png,jpeg';
                    $post['upload_allow_size'] = '1024000';
                }
            }
        }

        try {
            foreach ($post as $key => $val) {
                $cz = $this->model->where('name', $key)->find();
                if (empty($cz)) {
                    $this->model
                        ->insert([
                            'name' => $key,
                            'group' => 'default',//分组
                            'value' => $val,
                        ]);
                }else{
                    $this->model
                        ->where('name', $key)
                        ->update([
                            'value' => $val,
                        ]);
                }
            }
            TriggerService::updateMenu();
            TriggerService::updateSysconfig();
        } catch (\Exception $e) {
            $this->error('保存失败');
        }
        $this->success('保存成功');
    }

}