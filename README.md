Autumn理念
======

现有的热门PHP框架各自都有自己的优势，功能也非常地丰富。但在实际项目开发中，（以Yii为例）我却发现只应用了其中的30%的功能，甚至都不到。 我静下心来思考其中的缘由，并不是我们的系统不够“复杂”，也不是开发者的技术水平不足，反而我们在框架之上又扩展设计了许多漂亮的模块和子系统。所以，我越发地觉得，这肯定是那个环节出了问题。

我们设计框架的初衷是什么？答案肯定是为了提升开发效率。那又该怎样设计？当然是在大量重复、可重用的地方做优化。所以，我认为框架的意义是做项目的基石结构。就像是盖房子这样一个简单的道理：混凝土地基、钢筋机构才是真正的“框架”。百分之九十的房子都是基于这个架构的。可在现实中绝大多数的PHP框架不仅提供了结构，更提供了实现方式，已经把每个细节做到了极致。就像是出售的精装修商品房，交付前已经把你不喜欢的牌子电器都布置好了，不管你喜不喜欢他的装修风格。

Autumn框架的出发点是PHP作者的原话：php代码越接近原生，效率越高。php本身就是解释型语言，不合理的逻辑设计、过多的封装都会导致其性能的下降和理解的困难度。而且现有的框架总是会有不同程度的耦合度，总想设计成全能型框架，可惜实际使用的功能很少。

Autumn存在的意义就是提供最小可运行、高效率、高性能、松散零耦合的PHP应用框架。它仅仅只有9个核心类（1.1公测版），但是几乎实现了 Yii中30%的常用功能。基于MVC的分层架构，类Yii的风格实现，可以满足PHP项目对于框架的基础需求。



快速入门
======

项目目录结构
------

/app  应用目录（开发者）<br/>
	/controllers  控制器目录<br/>
	/models  业务模型目录<br/>
	/views  视图目录<br/>
	/statics  静态资源文件目录(js,css)<br/>
/config  配置文件目录<br/>
	/main.php  主配置文件<br/>
/core  框架核心目录<br/>
/library  扩展类库目录（非核心，可自由移除）<br/>
index.php  入口文件<br/>

入口文件index.php
------

在Autumn项目根目录下可以找到index.php文件，源码如下：

define('ROOT', dirname(__FILE__));<br/>
require_once(ROOT . '/config/main.php');<br/>
require_once(ROOT . '/core/Autumn.php');<br/>
core\Autumn::app($config)->run();<br/>

Autumn是单一入口的框架，入口文件基本上不需要改动。源码很简单，主要定义了全局变量ROOT，以及定位配置文件和初始化框架工作。

从hello, world开始
------

我们假设你了解MVC模式，/app/controllers/目录是放置Autumn控制器的地方。我们创建一个HelloController.php的文件，代码如下：

namespace app\controllers;
use core\Controller;

class HelloController extends Controller
{
	public function actionSay()
	{
		echo 'hello,world';
	}
}

我们构建了一个叫做Hello的控制器，并且写了一个say的action，这样我们就能在浏览器上访问：localhost/index.php/hello/say。不出意外的话，页面会输出：hello,world!

用户的控制器都需要继承core\Controller这个控制器基础类。core/Controller基础类负责执行action、提供参数解析等工作。

一般在index.php入口文件名后面，第一个参数就是控制器名称，第二个参数就是action方法名称。后面的参数需要成对出现，并且会被Autumn解析成为$_GET请求参数。我们通过一个例子来看看：

namespace app\controllers;
use core\Controller;

class HelloController extends Controller
{
	public function actionSay()
	{
		$name = $this->getQuery('name');
		echo 'hello: ' . $name;
	}
}

注意：1.2版本后获取GET或POST请求参数，统一使用Request类来实现。例如：\core\Request::inst()->getPost('user_name')

通过访问：localhost/index.php/hello/say/user/bobo这个url后，页面会输出：hongbo。这个就解释了上面所提到的url参数的问题。Autumn默认的url路由是这样的：域名/index.php/控制器名/方法名/参数1/值1/参数2/值2。需要注意的是参数键值要成对出现。对应的，获取post请求参数使用getPost()，获取全部参数使用getParam()。