<?php
/**
* 分页
* ======
* @author 洪波
* @version 16.02.29
*/
namespace library;

class Pagination
{

	//总页数
	private $all_page;
	//当前页码
	private $now_page;
	//跳转地址
	private $redirect;
	//列表尺寸（双倍）
	private $size = 5;

	//外部开始标签
	private $outside_start;
	//外部结束标签
	private $outside_end;
	//成员开始标签
	private $item_start;
	//成员结束标签
	private $item_end;
	//选中样式
	private $item_checked;

	/**
	* 构造函数
	* ======
	* @param $all_count 	总记录行数
	* @param $limit 		每页显示行数
	* @param $now_page 		当前页码
	* @param $redirect 		分页跳转url前缀
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function __construct($all_count, $limit, $now_page, $redirect)
	{
		$this->all_page = ceil($all_count / $limit);
		$this->now_page = (int)$now_page;
		$this->redirect = $redirect;
		//初始化标签样式
		$this->outside_start = '<ul class="pagination pagination-sm">';
		$this->outside_end = '</ul>';
		$this->item_start = '<li>';
		$this->item_end = '</li>';
		$this->item_checked = '<li class="active">';
	}

	/**
	* 设置分页样式
	* ======
	* @param $outside_start 外部开始标签
	* @param $outside_end 	外部结束标签
	* @param $item_start 	成员开始标签
	* @param $item_end 		成员结束标签
	* @param $item_checked	选中样式
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function setStyle($outside_start, $outside_end, $item_start, $item_end, $item_checked)
	{
		$this->outside_start = $outside_start;
		$this->outside_end = $outside_end;
		$this->item_start = $item_start;
		$this->item_end = $item_end;
		$this->item_checked = $item_checked;
	}

	/**
	* 设置url参数
	* ======
	* @param $param 	url参数
	* ======
	* @author 洪波
	* @version 13.11.05
	*/
	public function setParams($param)
	{
		if(is_array($param))
		{
			$p = '';
			foreach ($param as $k => $v)
			{
				$p .= '/' . $k . '/' . $v;
			}
			$this->redirect .= $p;
		}
		else
		{
			$this->redirect .= $param;
		}
	}

	/**
	* 构建分页列表
	* ======
	* @author 洪波
	* @version 13.11.05
	*/
	public function build()
	{
		//首页
		$html = $this->outside_start.$this->item_start.'<a href="'.$this->redirect.'/page/1">首页</a>'.$this->item_end;
		//第一页
		if($this->now_page == 1)
		{
			$html .= $this->item_checked.'<a href="'.$this->redirect.'/page/1">1</a>'.$this->item_end;
			//页总数大于1
			if($this->all_page > 1)
			{
				if($this->all_page < $this->size)
					$end = $this->all_page + 1;
				else
					$end = $this->size + 1;

				$html .= $this->buildSide(2, $end);
			}
		}
		//最后一页
		else if($this->now_page == $this->all_page)
		{
			$tail = $this->item_checked.'<a href="'.$this->redirect.'/page/'.$this->now_page.'">'.$this->now_page.'</a>'.$this->item_end;
			//页面总数大于1
			if($this->all_page > 1)
			{
				if($this->all_page <= $this->size)
					$start = 1;
				else
					$start = $this->now_page - $this->size;

				$html .= $this->buildSide($start, $this->now_page).$tail;
			}
		}
		//在中间位置
		else
		{
			//当前页左边
			$start = $this->now_page - $this->size;
			if($start < 1)
				$start = 1;
			$html .= $this->buildSide($start, $this->now_page);
			//当前页
			$html .= $this->item_checked.'<a href="'.$this->redirect.'/page/'.$this->now_page.'">'.$this->now_page.'</a>'.$this->item_end;
			//当前页右边
			$end = $this->now_page + $this->size;
			if($end > $this->all_page)
				$end = $this->all_page;
			$html .= $this->buildSide($this->now_page + 1, $end + 1);
		}
		//尾页
		$end_page = $this->all_page > 0 ? $this->all_page : 1;
		$html .= $this->item_start.'<a href="'.$this->redirect.'/page/'.$end_page.'">尾页</a>'.$this->item_end;
		//return $this->all_page;
		return $html . $this->outside_end;
	}

	/**
	* 构建当前页两边成员
	* ======
	* @param $start 	起始位置
	* @param $end 		结束位置
	* ======
	* @author 洪波
	* @version 13.11.05
	*/
	private function buildSide($start, $end)
	{
		$struct = '';
		for ($i = $start; $i < $end; ++$i)
		{
			$struct .= $this->item_start.'<a href="'.$this->redirect.'/page/'.$i.'">'.$i.'</a>'.$this->item_end;
		}
		return $struct;
	}

}