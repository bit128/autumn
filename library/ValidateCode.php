<?php
/**
* 图片验证码
* ======
* @author 洪波
* @version 16.02.29
*/
namespace library;

class ValidateCode
{
	const CHARS 		= '2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,T,U,V,W,X,Y,Z';
	const LENGTH_MIN 	= 4;
	const LENGTH_MAX	= 6;

	//渲染图片
	private $image;
	//验证码宽度
	private $width;
	//验证码高度
	private $height;

	/**
	* 设置验证码尺寸
	* ======
	* @param $width 	宽度
	* @param $height 	高度
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function setSize($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}

	/**
	* 获取随机颜色
	* ======
	* @param $offset 	阈值起点
	* @param $offset 	阈值终点
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function randColor($offset = 0, $limit = 100)
	{
		return imagecolorallocate($this->image, rand($offset, $limit), rand($offset, $limit), rand($offset, $limit));
	}

	/**
	* 获取验证码
	* ======
	* @param $count 位数 4 - 6
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function getCode($count)
	{
		if($count < self::LENGTH_MIN)
		{
			$count = self::LENGTH_MIN;
		}
		if($count > self::LENGTH_MAX)
		{
			$count = self::LENGTH_MAX;
		}
		$source = explode(',', self::CHARS);
		$source_length = count($source) - 1;
		$code = '';
		for($i=0; $i<$count; $i++)
		{
			$code .= $source[rand(0, $source_length)];
		}
		return $code;
	}

	/**
	* 绘制线条
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function renderLine($line)
	{
		for ($i=0; $i<$line; $i++)
		{
			imageline($this->image, rand(0, $this->width), rand(0, $this->height), rand(0, $this->width),
				rand(0, $this->height), $this->randColor(100, 200));
		}
	}

	/**
	* 绘制噪点
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function renderPoint($point)
	{
		for ($i=0; $i<$point; $i++)
		{
			imagestring($this->image, 1, rand(0, $this->width), rand(0, $this->height), '*', $this->randColor(120, 200));
		}
	}

	/**
	* 显示验证码
	* ======
	* @param $width 	宽度
	* @param $height 	高度
	* @param $length 	字符数量
	* @param $line 		线条数量
	* @param $point 	噪点数量
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function show($width = 60, $height = 30, $length = 4, $line = 10, $point = 50)
	{
		$this->width = $width;
		$this->height = $height;
		//创建画布
		$this->image = imagecreate($this->width, $this->height);
		//设置背景色
		imagefill($this->image, 0, 0, imagecolorallocate($this->image, 255, 255, 255));
		//绘制线条
		$this->renderLine($line);
		//绘制雪花
		$this->renderPoint($point);
		//绘制验证码
		imagestring($this->image, 5, rand(6,12), rand(4,8), $this->getCode($length), $this->randColor());
		//生成图片
		header("Content-type: image/png");
		imagepng($this->image);
		imagedestroy($this->image);
	}
}