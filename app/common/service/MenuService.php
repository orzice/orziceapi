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
// | DateTime: 2021-07-06 17:03:35
// +----------------------------------------------------------------------

namespace app\common\service;

use app\common\constants\MenuConstant;
use think\facade\Db;

class MenuService
{

    /**
     * 管理员ID
     * @var integer
     */
    protected $adminId;

    public function __construct($adminId)
    {
        $this->adminId = $adminId;
        return $this;
    }

    /**
     * 获取首页信息
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getHomeInfo()
    {
        $data = Db::name('system_menu')
            ->field('title,icon,href')
            ->where("delete_time is null")
            ->where('pid', MenuConstant::HOME_PID)
            ->find();
        !empty($data) && $data['href'] = __url($data['href']);
        return $data;
    }

    /**
     * 获取后台菜单树信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenuTree()
    {
        $menuTreeList = $this->buildMenuChild(0, $this->getMenuData());
        return $menuTreeList;
    }

    private function buildMenuChild($pid, $menuList)
    {
        $treeList = [];
        $authServer = (new AuthService($this->adminId));

        foreach ($menuList as &$v) {
            //插件系统调优
            if ($v['pid'] !== 1) {
               $check = empty($v['href']) ? true : $authServer->checkNode($v['href']);
            }else{
                $check = true;
            }
            if($v['id'] == 1){
                $check = true;
            }
        

            $controller = config_plus("orzice.Admin");
       
            if (!empty($v['href'] && substr ($v['href'], 0,strlen('plugins.')) !== 'plugins.')) {
                $v['href'] = __url($v['href']);
            }else if(substr ($v['href'], 0,strlen('plugins.')) == 'plugins.'){
                $v['href'] = '/'.$controller.'/'.$v['href'];
            }

            if ($pid == $v['pid'] && $check) {
                $node = $v;
                $child = $this->buildMenuChild($v['id'], $menuList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                if (!empty($v['href']) || !empty($child)) {
                    $treeList[] = $node;
                }
            }
        }
        return $treeList;
    }

    /**
     * 获取所有菜单数据
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getMenuData()
    {
        $menuData = Db::name('system_menu')
            ->field('id,pid,title,icon,href,target')
            ->where("delete_time is null")
            ->where([
                ['status', '=', '1'],
                ['pid', '<>', MenuConstant::HOME_PID],
            ])
            ->order([
                'sort' => 'desc',
                'id'   => 'asc',
            ])
            ->select();
        return $menuData;
    }

}