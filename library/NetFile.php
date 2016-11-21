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

	//以日期形式散射文件夹 例如：/16/11/10/file_name.xxx
	const HASH_DATE = 1;
	//以文件名散射文件夹 例如：/a4/a4763b29afc20.xxx
	const HASH_NAME = 2;

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
	
	/**
	* 上传文件
	* ======
	* @param $save_path 	文件保存路径
	* @param $post_name 	文件域名称
	* @param $hash_type 	散列路径类型
	* ======
	* @author 洪波
	* @version 16.02.29 - 16.11.10
	*/
	public function upload($save_path = './', $post_name = 'file_name', $hash_type = 0)
	{
		$result = array(
			'code' => 0,
			'error' => '',
			'uri' => '',
			'size' => '',
			'type' => ''
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
					//获取扩展名
					$ext_name = strtolower(substr(strrchr($_FILES[$post_name]['name'], '.'), 1));
					//新文件名称
					$file_name = uniqid() . '.' . $ext_name;
					//散射存储地址
					$hash_file = $this->hashFolder($save_path, $file_name, $hash_type);
					//开始上传
					if (move_uploaded_file($_FILES[$post_name]['tmp_name'], $hash_file))
					{
						//返回结果
						$result['code'] = 1;
						$result['uri'] = substr($hash_file, 1);
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
			$error_id = $_FILES[$post_name]['error'];
			$result['error'] = $this->errors[$error_id];
		}

		return $result;
	}

	/**
	* 散射存储目录
	* ======
	* @param $file_name 	文件名
	* @param $hash_type 	散射类型
	* ======
	* @author 洪波
	* @version 16.11.10
	*/
	private function hashFolder($save_path, $file_name, $hash_type)
	{
		if ($hash_type == self::HASH_DATE)
		{
			$save_path .= date('y/m/d/');
		}
		else if ($hash_type == self::HASH_NAME)
		{
			$save_path .= substr($file_name, 11, 2) . '/';
		}
		//判断文件夹是否存在并创建
		if(! file_exists($save_path))
		{
			mkdir($save_path, 0777, true);
		}
		return $save_path . $file_name;
	}

	/**
	* 删除文件
	* ======
	* @param $src	资源名称
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public static function deleteFile($src)
	{
		if(is_file($src))
		{
			@unlink($src);
		}
	}

	/**
	* 下载网络图片
	* ======
	* @param $src 		目标图片源
	* @param $save_path 保存路径
	* ======
	* @author 洪波
	* @version 16.07.14
	*/
	public function download($src, $save_path, $hash_type = 0)
	{
		$ext_name = strtolower(strrchr($src, '.'));
		//文件路径
		$file_name = uniqid() . $ext_name;
		$hash_file = $this->hashFolder($save_path, $file_name, $hash_type);
		//写入文件
		file_put_contents($hash_file, file_get_contents($src));

		return substr($hash_file, 1);
	}

	/**
	* 传输文件
	* ======
	* @param $file_data 	文件流
	* @param $url 			发往地址
	* @param $post_name 	文件域名
	* ======
	* @author 洪波
	* @version 16.11.21
	*/
	public function sendFile($file_data, $url, $post_name)
	{
		$boundary = md5(microtime());
		$data = array();
		array_push($data, '--' . $boundary);
		array_push($data, "Content-Disposition: form-data; name=\"" . $post_name . "\"; filename=\"default.jpg\"");
		array_push($data, "Content-Type: application/octet-stream");
		array_push($data, '');
		array_push($data, $file_data);
		//报文结尾
		array_push($data, '--' . $boundary . '--');
		array_push($data, '');
		//换行拼接报文
		$body = implode("\r\n", $data);
		//请求报头
		$_headers = array('Expect:');
		array_push($_headers, "Content-Type: multipart/form-data; boundary=".$boundary);
		//提交请求
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		return $result;
	}
}