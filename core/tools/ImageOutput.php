<?php
/**
* 图像输出
* ======
* @author 洪波
* @version 16.11.10
*/
namespace core\tools;

class ImageOutput
{

    //缩略图文件夹名称
    const THUMB_FOLDER = '/thumb/';

    /**
    * 渲染图片
    * ======
    * @param $uri   图片路径
    * ======
    * @author 洪波
    * @version 16.11.10
    */
    public function render($uri)
    {
        //分析尺寸属性
        $uri_attr = strrchr($uri, '@');
        if($uri_attr)
        {
            //获取存储文件夹目录
            $true_uri = '.' . str_replace($uri_attr, '', $uri);
            if (is_file($true_uri))
            {
                $file_name = strrchr($true_uri, '/');
                $save_path = str_replace($file_name, '', $true_uri) . self::THUMB_FOLDER;
                if(! file_exists($save_path))
                {
                    mkdir($save_path, 0777, true);
                }
                //获取宽度和高度
                $sizes = explode('_', substr($uri_attr, 1));
                //拼合缓存图片uri
                $thumb_uri = $save_path . implode('_', $sizes) . '_' . substr($file_name, 1);
                if(! is_file($thumb_uri))
                {
                    //约束宽度
                    if(count($sizes) == 1)
                    {
                        $this->modifyWidth($true_uri, $thumb_uri, $sizes[0]);
                    }
                    //约束宽高
                    else if (count($sizes) == 2)
                    {
                        $this->modifySize($true_uri, $thumb_uri, $sizes[0], $sizes[1]);
                    }
                }
                //读缓存
                else
                {
                    header('Location: ' . substr($thumb_uri, 1));
                }
            }
            else
            {
                return 'File not found.';
            }
        }
        //读源文件
        else
        {
            header('Location: ' . $uri);
        }
    }

    /**
    * 等宽缩放
    * ======
    * @param $true_uri  源文件路径
    * @param $thumb_uri 缩略图路径
    * @param $width     缩略宽度
    * ======
    * @author 洪波
    * @version 16.11.10
    */
    private function modifyWidth($true_uri, $thumb_uri, $width)
    {
        //获取图片信息
		$size = getimagesize($true_uri);
		//计算缩略后尺寸
		$sh = $size[1] * ($width / $size[0]);
		$out = imagecreatetruecolor($width, $sh);
		//根据图片类型创建画布
		switch ($size[2])
		{
			case 1:
				$in = imagecreatefromgif($true_uri);
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
				ob_start();
				imagegif($out, $thumb_uri, 100);
				ob_end_flush();
				header('Content-Type: image/GIF');
				imagegif($out, 100);
				break;
			case 2:
				$in = imagecreatefromjpeg($true_uri);
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
				ob_start();
				imagejpeg($out, $thumb_uri, 100);
				ob_end_flush();
				header('Content-Type: image/JPEG');
				imagejpeg($out);
				break;
			case 3:
				$in = imagecreatefrompng($true_uri);
				$alpha = imagecolorallocatealpha($out, 0, 0, 0, 127);  
				imagefill($out, 0, 0, $alpha); 
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
				imagesavealpha($out, true);
				ob_start();
				imagepng($out, $thumb_uri, 9);
				ob_end_flush();
				header('Content-Type: image/PNG');
				imagepng($out);
				break;
		}
		//保存并设置777权限
		chmod($thumb_uri, 0777);
		//清空缓冲区
		imagedestroy($out);
		imagedestroy($in);
    }

	/**
	* 全尺寸约束
	* ======
	* @param $true_uri  源文件路径
    * @param $thumb_uri 缩略图路径
    * @param $width     约束宽度
    * @param $height    约束宽度
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function modifySize($true_uri, $thumb_uri, $width, $height)
	{
		//获取图片信息
		$size = getimagesize($true_uri);
		//根据图片类型创建画布
		switch($size[2])
		{
			case 1: $in = imagecreatefromgif($true_uri);
				break;
			case 2: $in = imagecreatefromjpeg($true_uri);
				break;
			case 3: $in = imagecreatefrompng($true_uri);
				break;
		}
		//判断图片长宽比例
		if($size[0] < $size[1])
		{
			//宽度比高度小的图片
			$sw = $size[0] * ($height / $size[1]);
			$out = imagecreatetruecolor($sw, $height);
			imagecopyresampled($out, $in, 0, 0, 0, 0, $sw, $height, $size[0], $size[1]);
		}
		else
		{
			//宽度比高度大的或正方形的图片
			$sh = $size[1] * ($width / $size[0]);
			/* 如果缩略后的高度仍比限制高度大的话
			* 则以原始高度等比缩放
			*/
			if($sh > $height)
			{
				$sw = $size[0] * ($height / $size[1]);
				$out = imagecreatetruecolor($sw, $height);
				imagecopyresampled($out, $in, 0, 0, 0, 0, $sw, $height, $size[0], $size[1]);
			}
			else
			{
				$out = imagecreatetruecolor($width, $sh);
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
			}
		}
		//根据图片类型输出结果
		switch($size[2])
		{
			case 1:
				ob_start();
				imagegif($out, $thumb_uri, 100);
				ob_end_flush();
				header('Content-Type: image/GIF');
				imagegif($out, 100);
				break;
			case 2:
				ob_start();
				imagejpeg($out, $thumb_uri, 100);
				ob_end_flush();
				header('Content-Type: image/JPEG');
				imagejpeg($out);
				break;
			case 3:
				ob_start();
				imagepng($out, $thumb_uri, 100);
				ob_end_flush();
				header('Content-Type: image/PNG');
				imagepng($out);
				break;
		}
		//保存并设置777权限
		chmod($thumb_uri, 0777);
		//清空缓冲区
		imagedestroy($out);
		imagedestroy($in);
	}
}