<?php
/**
* 异常
* ======
* @author 洪波
* @version 17.02.20
*/
namespace core;

class Exception {
    /**
	* 系统异常处理
	* ======
	* @author 洪波
	* @version 17.01.10
	*/
	public function throws($content, $interrupt = true) {
		if(AUTUMN_DEBUG) {
			header("Content-Type:text/html; charset=utf-8");
			$view = 'app/views/exception.php';
			if(is_file($view)) {
				extract(array(
					'error_detail' => $content,
					'system_version' => Autumn::FRAMEWORK_VERSION
				), EXTR_PREFIX_SAME, 'data');
				ob_start();
				ob_implicit_flush(false);
				require($view);
				echo ob_get_clean();
			} else {
				echo '<div style="text-align:center;padding:10px;border:1px dotted #ccc;color:#ff4e00;background:#eee;border-radius:10px;">',
					'<p style="color:#666;"><strong style="font-size:20px;">警告：系统异常</strong></p>',
					'<div style="border-top:3px solid #ddd; padding:20px;">',$content,'</div>',
					'<p style="color:#999;"><small>Autumn版本：',Autumn::FRAMEWORK_VERSION,'</small></p></div>';
			}
			if($interrupt) {
				exit;
			}
		}
	}
}