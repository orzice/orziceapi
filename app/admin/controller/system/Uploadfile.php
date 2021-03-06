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


use app\admin\model\SystemUploadfile;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="上传文件管理")
 * Class Uploadfile
 * @package app\admin\controller\system
 */
class Uploadfile extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemUploadfile();
    }
    /**
     * @NodeAnotation(title="修改状态")
     */
    public function type($id)
    {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        $row = $this->model->whereIn('id', $id)->find();
        if (empty($row)) {
          $this->error('数据不存在');
        }
        $state = 1;
        if ($row['state'] == 1) {
           $state = 0;
        }

        try {
            $save = $row->where('id',$row['id'])->update(['state'=>$state]);
        } catch (\Exception $e) {
            $this->error('修改状态失败');
        }
        $save ? $this->success('修改状态成功') : $this->error('修改状态失败');
    }
    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        if(env('orzice.is_demo', false)){
            $this->error('演示环境下不允许修改');
        }
        $row = $this->model->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
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
        
        try {
            $save = $row->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }

}