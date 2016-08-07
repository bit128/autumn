<?php
/**
* 网络文件
* ======
* @author 洪波
* @version 16.02.29
*/
namespace library;

class NetFile
{
	const POST_NAME = 'file_name';
	const IMAGE_PATH = './app/statics/files/images/';
	const FILE_PATH = './app/statics/files/others/';

	//上传文件错误类型
	private $errors = array(
		0 => 'Success',
		1 => 'File exceeded upload_max_filesize',
		2 => 'File exceeded max_file_size',
		3 => 'File only partially upload',
		4 => 'No File uploaded',
		5 => 'Files size is 0',
		6 => 'Cannot upload file:No temp directory specified',
		7 => 'Upload failed: Cannot write to disk'
	);
	
	//支持的MIME类型
	private $mimes = array(
		'image/jpeg',
		'image/png',
		'audio/mpeg',
		'video/mp4'
	);

	//MIME对应扩展名
	private $ext_name = array(
		'image/jpeg' => 'jpg',
		'image/png' => 'png',
		'audio/mpeg' => 'mp3',
		'video/mp4' => 'mp4'
	);
	
	/**
	* 下载网络图片
	* ======
	* @param $src 		目标图片源
	* @param $save_path 保存路径
	* ======
	* @author 洪波
	* @version 16.07.14
	*/
	public function download($src, $save_path, $file_name = '')
	{
		if ($file_name == '')
		{
			$file_name = uniqid() . '.jpg';
		}
		//获取网络图片
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $src);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		//写入文件
		file_put_contents($save_path . $file_name, $data);

		return $file_name;
	}
	
	/**
	* 上传文件
	* ======
	* @param $post_name 	文件域名称
	* @param $save_path 	文件保存路径
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function upload($post_name)
	{
		$result = array(
			'code' => 0,
			'error' => '',
			'name' => '',
			'src' => '',
			'mime' => '',
			'type' => '',
			'size' => ''
			);
		//纠错
		if ($_FILES[$post_name]['error'] == 0)
		{
			$type = $_FILES[$post_name]['type'];
			$size = $_FILES[$post_name]['size'];
			//大小检测
			if($size < 2000000)
			{
				if (is_uploaded_file($_FILES[$post_name]['tmp_name']))
				{
					//存储路径
					$ext_name = '';
					//$move_path = '';
					//重组文件名
					if(in_array($type, $this->mimes))
					{
						$ext_name = $this->ext_name[$type];
						$file_name = uniqid() . '.' . $ext_name;
						//$move_path = '.' . $this->type_path[$type];
					}
					else
					{
						$ext_name = strtolower(substr(strrchr($_FILES[$post_name]['name'], '.'), 1));
						$file_name = uniqid() . '.' . $ext_name;
						//$move_path = '.' . self::PATH_OTHER;
					}
					//存储路径
					$save_path = self::FILE_PATH;
					if($ext_name == 'jpg' || $ext_name == 'png')
					{
						$save_path = self::IMAGE_PATH;
					}
					//开始上传
					if (move_uploaded_file($_FILES[$post_name]['tmp_name'], $save_path . $file_name))
					{
						//返回结果
						$result['code'] = 1;
						$result['name'] = $_FILES[$post_name]['name'];
						$result['src'] = $file_name;
						$result['mime'] = $type;
						$result['size'] = $size;
						$result['type'] = $ext_name;
					}
					else
					{
						$result['error'] = $this->errors[7];
					}
				}
				else
				{
					$result['error'] = $this->errors[6];
				}
			}
			else
			{
				$result['error'] = $this->errors[1];
			}
		}
		else
		{
			$error = $_FILES[$post_name]['error'];
			$result['error'] = $this->errors[$error];
		}

		return $result;
	}

	/**
	* 输出文件
	* ======
	* @param $src 	资源名称
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function output($src)
	{
		if(file_exists($src))
		{
			header('Location: ' . $src);
		}
	}

	/**
	* 输出图片
	* ======
	* @param $image_name 	图片名称
	* @param $width 		最大宽度
	* @param $height 		最大高度
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function outputImage($image_name, $save_path)
	{
		//判断是否有尺寸属性
		$image_arr = explode('_', $image_name);
		$l = sizeof($image_arr);
		//读缩略图
		if($l > 1)
		{
			$file_name = $image_arr[$l-1];
			$file_name = $save_path . $file_name;
			$thumb_name = $save_path . 'thumbs/' . $image_name;
			//尝试从缓存中读
			if(file_exists($thumb_name))
			{
				header('Location: ' . substr($thumb_name, 1));
			}
			//缓存不存在，全新裁剪
			else if (file_exists($file_name))
			{
				//仅限宽
				if($l == 2)
				{
					$this->thumbWidth($file_name, $thumb_name, $image_arr[0]);
				}
				//约束宽高
				else if ($l == 3)
				{
					$this->thumbAll($file_name, $thumb_name, $image_arr[0], $image_arr[1]);
				}
			}
		}
		//读原图
		else
		{
			$file_name = $save_path . $image_name;
			header('Location: ' . substr($file_name, 1));
		}
	}

	/**
	* 图片的等宽缩放
	* ======
	* @param $file_name 	原图
	* @param $thumb_name	效果图
	* @param $width 		约束宽度
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function thumbWidth($file_name, $thumb_name, $width)
	{
		//获取图片信息
		$size = getimagesize($file_name);
		//计算缩略后尺寸
		$sh = $size[1] * ($width / $size[0]);
		$out = imagecreatetruecolor($width, $sh);
		//根据图片类型创建画布
		switch ($size[2])
		{
			case 1:
				$in = imagecreatefromgif($file_name);
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
				ob_start();
				imagegif($out, $thumb_name, 100);
				ob_end_flush();
				header('Content-Type: image/GIF');
				imagegif($out, 100);
				break;
			case 2:
				$in = imagecreatefromjpeg($file_name);
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
				ob_start();
				imagejpeg($out, $thumb_name, 100);
				ob_end_flush();
				header('Content-Type: image/JPEG');
				imagejpeg($out);
				break;
			case 3:
				$in = imagecreatefrompng($file_name);
				$alpha = imagecolorallocatealpha($out, 0, 0, 0, 127);  
				imagefill($out, 0, 0, $alpha); 
				imagecopyresampled($out, $in, 0, 0, 0, 0, $width, $sh, $size[0], $size[1]);
				imagesavealpha($out, true);
				ob_start();
				imagepng($out, $thumb_name, 9);
				ob_end_flush();
				header('Content-Type: image/PNG');
				imagepng($out);
				break;
		}
		//保存并设置777权限
		chmod($thumb_name, 0777);
		//清空缓冲区
		imagedestroy($out);
		imagedestroy($in);
	}

	/**
	* 约束图片尺寸
	* ======
	* @param $file_name 	原图
	* @param $thumb_name	效果图
	* @param $width 		约束宽度
	* @param $height 		约束高度
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function thumbAll($file_name, $thumb_name, $width, $height)
	{
		//获取图片信息
		$size = getimagesize($file_name);
		//根据图片类型创建画布
		switch($size[2])
		{
			case 1: $in = imagecreatefromgif($file_name);
				break;
			case 2: $in = imagecreatefromjpeg($file_name);
				break;
			case 3: $in = imagecreatefrompng($file_name);
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
				imagegif($out, $thumb_name, 100);
				ob_end_flush();
				header('Content-Type: image/GIF');
				imagegif($out, 100);
				break;
			case 2:
				ob_start();
				imagejpeg($out, $thumb_name, 100);
				ob_end_flush();
				header('Content-Type: image/JPEG');
				imagejpeg($out);
				break;
			case 3:
				ob_start();
				imagepng($out, $thumb_name, 100);
				ob_end_flush();
				header('Content-Type: image/PNG');
				imagepng($out);
				break;
		}
		//保存并设置777权限
		chmod($thumb_name, 0777);
		//清空缓冲区
		imagedestroy($out);
		imagedestroy($in);
	}

	/**
	* 删除文件
	* ======
	* @param $src	资源名称
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function delete($src)
	{
		if(file_exists(filename))
		{
			@unlink($src);
		}
	}
}