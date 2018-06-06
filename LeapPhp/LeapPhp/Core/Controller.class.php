<?php
namespace Core;
use \Core\View;

class Controller extends View {
	
	protected $_view = '';
	
	#构造函数
	public function __construct(){
		#执行初始化函数
		if(method_exists($this,'_initlize')){
			 $this->_initlize();
		}
		$this->_view = new View();
	}
	
	
	#模板传值
	#name 变量名称
	#value 变量值
	public function assign($name='',$value=''){
		$this->_view->transfer($name,$value);
		return $this;
	}
	
	
	#显示模板
	#template 定位模板路径   例如  Home/Index/index
	#filename 定位模板文件不带文件后缀
	public function display($template='',$filename=''){
		return $this->_view->show($template,$filename);
	}
	
	
	#获取模板内容
	#显示模板
	#template 定位模板路径   例如  Home/Index/index
	#filename 定位模板文件不带文件后缀
	public function fetch($template='',$filename=''){
		return $this->_view->fetch($template,$filename);
	}
	
	
	
	
	#返回数据
	public function ResultReturn($data='',$type='Json'){
		switch(strtolower($type)){
			case 'json':
				header('Content-Type:application/json;Charset=utf-8');
				exit(json_encode($data,true));
			break;
			case 'xml':
				header('Content-Type:text/xml;Charset=utf-8');
				exit(xml_encode($data));
			break;
			case 'eval':
				header('Content-Type:text/html;Charset=utf-8');
				exit($data);
			break;
		}
	} 
}
