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

namespace app\admin\controller\plugin;

use app\common\controller\AdminController;
use app\common\Plugins;
use app\common\model\PluginsData;

use app\admin\service\TriggerService;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

use app\admin\model\SystemMenu;

/**
 * @ControllerAnnotation(title="插件系统管理")
 * Class Node
 * @package app\admin\controller\plugin
 */

class Index extends AdminController
{
    /**
     * @NodeAnotation(title="我的插件")
     */
    public function index()
     {
        //获取插件配置
        $PluginOn = Plugins::GetPluginList(1);
        $PluginOff = Plugins::GetPluginList(0);
        $Install = Plugins::GetInstallPlugin();

        $this->assign('PluginOn', $PluginOn);
        $this->assign('PluginOff', $PluginOff);
        $this->assign('Install', $Install);

        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="安装插件")
     */
    public function install($name=null)
     {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        if (!$name) {
            $this->error('请输入插件名称！', [], __url('admin/plugin.index/index'));
        }
        $a = Plugins::Install($name);
        if (!$a) {
            $this->error('插件安装失败！', [], __url('admin/plugin.index/index'));
        }
        $this->success('插件安装成功！', [], __url('admin/plugin.index/index'));
     }
    /**
     * @NodeAnotation(title="开启插件")
     */
    public function on($name=null)
     {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        if (!$name) {
            $this->error('请输入插件名称！', [], __url('admin/plugin.index/index'));
        }
//        try {
            PluginsData::where("dir",$name)->update(['state' => 1]);
            Plugins::PluginNode();
            Plugins::PluginMenu();
//        } catch (\Exception $e) {
//            $this->error('开启插件失败！', [], __url('admin/plugin.index/index'));
//        }
        
        $this->success('开启插件成功！', [], __url('admin/plugin.index/index'));
     }
    /**
     * @NodeAnotation(title="更新本地插件")
     */
    public function update_dir($name=null)
     {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        if (!$name) {
            $this->error('请输入插件名称！', [], __url('admin/plugin.index/index'));
        }
        try {
            $a = Plugins::Update($name);
        } catch (\Exception $e) {
            $this->error('插件更新失败！', [], __url('admin/plugin.index/index')); 
        }
        if($a){
            $this->success('插件更新成功！', [], __url('admin/plugin.index/index'));
        }else{
            $this->error('插件更新失败！', [], __url('admin/plugin.index/index')); 
        }
     }
    /**
     * @NodeAnotation(title="关闭插件")
     */
    public function off($name=null)
     {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        if (!$name) {
            $this->error('请输入插件名称！', [], __url('admin/plugin.index/index'));
        }
        try {
            PluginsData::where("dir",$name)->update(['state' => 0]);
            Plugins::PluginNodeDel($name);
            Plugins::PluginMenuDel($name);
        } catch (\Exception $e) {
            $this->error('插件关闭失败！', [], __url('admin/plugin.index/index')); 
        }
        
        $this->success('插件关闭成功！', [], __url('admin/plugin.index/index'));
     }
    /**
     * @NodeAnotation(title="卸载插件")
     */
    public function del($name=null)
     {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        if (!$name) {
            $this->error('请输入插件名称！', [], __url('admin/plugin.index/index'));
        }
        try {
            $a = Plugins::PluginDel($name);
        } catch (\Exception $e) {
            $this->error('插件卸载失败！', [], __url('admin/plugin.index/index')); 
        }
        if($a){
            $this->success('插件卸载成功！', [], __url('admin/plugin.index/index'));
        }else{
            $this->error('插件卸载失败！', [], __url('admin/plugin.index/index')); 
        }
        
       
     }
}