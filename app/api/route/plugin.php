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
// | DateTime: 2021-07-06 19:01:39
// +----------------------------------------------------------------------
use think\facade\Route;


// 绑定到类
/**
 * plugin.a-index-index-index    或者 home/ 都可以
 */
Route::rule('plugin.<p1>-<p2>-<p3>-<p4>','Plugin/call');