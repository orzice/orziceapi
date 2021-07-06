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
// | DateTime: 2021-07-06 19:03:06
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\admin\model\SystemUploadfile;
use app\common\controller\ApiController;
use app\common\service\MenuService;
use Hasog\upload\Uploadfile;
use think\db\Query;
use think\facade\Cache;

class Ajax extends ApiController
{
    /**
     * 上传文件
     */
    public function upload()
    {
        $adminId = $this->MemberId();
        //================================================
        //  每一次上传 都会监控未使用的图片 进行删除处理
        //================================================
        UserUploadfileDelete();
        //================================================
        // 处理完成
        //================================================

        $data = [
            'upload_type' => $this->request->post('upload_type'),
            'file'        => $this->request->file('file'),
        ];
        $uploadConfig = sysconfig('upload');
        empty($data['upload_type']) && $data['upload_type'] = $uploadConfig['upload_type'];
        $rule = [
            'upload_type|指定上传类型有误' => "in:{$uploadConfig['upload_allow_type']}",
            'file|文件'              => "require|file|fileExt:{$uploadConfig['upload_allow_ext']}|fileSize:{$uploadConfig['upload_allow_size']}",
        ];
        $validate = $this->validate($data, $rule);

        if ($validate !== true) {
            $this->error('此类文件不允许上传');
        }

        try {
            $upload = Uploadfile::instance()
                ->setUploadType($data['upload_type'])
                ->setUploadConfig($uploadConfig)
                ->setFile($data['file'])
                ->setUid($adminId)
                ->setState(0)
                ->save();

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if ($upload['save'] == true) {
            $this->success($upload['msg'], ['url' => $upload['url']]);
        } else {
            $this->error($upload['msg']);
        }
    }

}