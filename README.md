<h1 align="center">OrziceApi</h1>

<p align="center">
基于HaSog框架独立出的高扩展和安全的API后台管理框架
</p>


## 项目介绍

基于HaSog框架（ThinkPHP6.0 + layui）独立出的高扩展和高安全的API后台管理系统。
后台基于EasyAdmin，对其进行大幅度优化，且附带了自己独特的功能。

## 代码仓库

* GitHub地址：[https://github.com/orzice/orziceapi](https://github.com/orzice/orziceapi)

* Gitee地址：[https://gitee.com/orzice/orziceapi](https://gitee.com/orzice/orziceapi)


## 项目特性
* 自带插件系统
    * 可以自己定制自己的插件
    * 入门简单，5分钟快速上手
* 快速CURD命令行
    * 一键生成控制器、模型、视图、JS文件
    * 支持关联查询、字段设置等等
* 基于`auth`的权限管理系统
    * 通过`注解方式`来实现`auth`权限节点管理
    * 具备一键更新`auth`权限节点，无需手动输入管理
    * 完善的后端权限验证以及前面页面按钮显示、隐藏控制
* 完善的菜单管理
    * 分模块管理
    * 无限极菜单
    * 菜单编辑会提示`权限节点`
* 完善的上传组件功能
    * 本地存储
    * 阿里云OSS`建议使用`
    * 腾讯云COS
    * 七牛云OSS
* 完善的前端组件功能
   * 对layui的form表单重新封装，无需手动拼接数据请求
   * 简单好用的`图片、文件`上传组件
   * 简单好用的富文本编辑器`ckeditor`
   * 对弹出层进行再次封装，以极简的方式使用
   * 对table表格再次封装，在使用上更加舒服
   * 根据table的`cols`参数再次进行封装，提供接口实现`image`、`switch`、`list`等功能，再次基础上可以自己再次扩展
   * 根据table参数一键生成`搜索表单`，无需自己编写
* 完善的后台操作日志
   * 记录用户的详细操作信息
   * 按月份进行`分表记录`
* 一键部署静态资源到OSS上
   * 所有在`public\static`目录下的文件都可以一键部署
   * 一个配置项切换静态资源（oss/本地）
* 上传文件记录管理
* 后台路径自定义，防止别人找到对应的后台地址
