### Autumn理念

Autumn是一款轻巧灵活的、容器式的PHP Web框架，它可以像Java的Spring那样优美地处理依赖注入。

Autumn框架本身不关心具体模块的实现和类库工具，而是致力于提供一个运行容器，用来配置、构建、运行和维护全部的程序组件。

Autumn当然也提供了MVC分层设计和Orm等功能的封装，但不是必须要使用，你随时可以用你更熟悉的类库和工具来替换它们。

### 目录说明

* app - __项目相关__
    * __controllers__ - 控制器
    * __models__ - 模型
    * __views__ - 视图
    * __runtimes__ - 运行时
    * __statics__ - 静态资源
* __config__ - 配置相关
    * __main.php__ 主配置文件
* __core__ - 核心相关
* __index.php__ - 项目入口文件
* __.htaccess__ 基于apache的友好路由配置

### 部署

当你下载了Autumn框架之后，你就可以看到如上的项目目录结构，你暂时不需要配置什么，把它部署到apache里面就可以看到如下的提示了：

__Layout Page__
欢迎使用：__Autumn Framework for PHP__
version: 1.8.8

恭喜，Autumn框架已经准备好了！

###功能快速索引

模块|说明
-|-
core.web.Controller|控制器
core.http.Request|请求
core.http.Response|响应
core.web.View|视图渲染器
core.web.Model|业务模型
core.Config|配置管理器