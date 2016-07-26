现有的热门PHP框架各自都有自己的优势，功能也非常地丰富。但在实际项目开发中，（以Yii为例）我却发现只应用了其中的30%的功能，甚至都不到。 我静下心来思考其中的缘由，并不是我们的系统不够“复杂”，也不是开发者的技术水平不足，反而我们在框架之上又扩展设计了许多漂亮的模块和子系统。所以，我越发地觉得，这肯定是那个环节出了问题。

我们设计框架的初衷是什么？答案肯定是为了提升开发效率。那又该怎样设计？当然是在大量重复、可重用的地方做优化。所以，我认为框架的意义是做项目的基石结构。就像是盖房子这样一个简单的道理：混凝土地基、钢筋机构才是真正的“框架”。百分之九十的房子都是基于这个架构的。可在现实中绝大多数的PHP框架不仅提供了结构，更提供了实现方式，已经把每个细节做到了极致。就像是出售的精装修商品房，交付前已经把你不喜欢的牌子电器都布置好了，不管你喜不喜欢他的装修风格。

Autumn框架的出发点是PHP作者的原话：php代码越接近原生，效率越高。php本身就是解释型语言，不合理的逻辑设计、过多的封装都会导致其性能的下降和理解的困难度。而且现有的框架总是会有不同程度的耦合度，总想设计成全能型框架，可惜实际使用的功能很少。

Autumn存在的意义就是提供最小可运行、高效率、高性能、松散零耦合的PHP应用框架。它仅仅只有9个核心类（1.1公测版），但是几乎实现了 Yii中30%的常用功能。基于MVC的分层架构，类Yii的风格实现，可以满足PHP项目对于框架的基础需求。

======

项目目录结构

/app  应用目录（开发者）
    /controllers  控制器目录
    /models  业务模型目录
    /views  视图目录
    /statics  静态资源文件目录(js,css)
/config  配置文件目录
    /main.php  主配置文件
/core  框架核心目录
/library  扩展类库目录（非核心，可自由移除）
index.php  入口文件

======

在Autumn项目根目录下可以找到index.php文件，源码如下：

define('ROOT', dirname(__FILE__));
require_once(ROOT . '/config/main.php');
require_once(ROOT . '/core/Autumn.php');
core\Autumn::app($config)->run();

Autumn是单一入口的框架，入口文件基本上不需要改动。源码很简单，主要定义了全局变量ROOT，以及定位配置文件和初始化框架工作。