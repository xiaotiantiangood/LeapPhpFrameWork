<?php
 namespace Core;
 
class App{
	
	
	
  public static function _init(){
  	#初始化参数
	define('IS_GET',(I('server.request_method') == 'GET' ? true :false));   #Get  请求
	define('IS_POST',(I('server.request_method') == 'POST' ? true :false)); #POST 请求
	define('IS_AJAX',(I('server.http_x_requested_with') && I('server.http_x_requested_with') == 'xmlhttprequest' ? true :false));
	define('REQUEST_METHOD',I('server.request_method'));
	#url 调度 生成
	\Core\Route::dispatch();
	
  }	
  
  
  public static function run(){
  	
  	self::_init();
  	
  	#执行
  	self::exec();
  	
  }
  
  public static function exec(){
  	#检测模块合法性
  	if(!in_array(MODULE_NAME,C(LEAP_MODULE_NAMESPACE))){
  		E(MODULE_NAME.' 模块 不存在');
  	}
  	#获取对应的方法
  	$class = "\\".MODULE_NAME.'\\'.ucfirst(C(LEAP_VAR_CON_NAME)).'\\'.CONTROLLER_NAME;
	#判断类是否存在
	if(!class_exists($class)){
		if(C(LEAP_CONTROLLER_NOT_EXISTS)){
			$class = "\\".MODULE_NAME.'\\'.ucfirst(C(LEAP_VAR_CON_NAME)).'\\'.ucfirst(C(LEAP_CONTROLLER_NOT_EXISTS));
		}else{
			E($class .'  类不存在');
		}
	}
	$action = ACTION_NAME;
	#判断方法是否存在
	if(!method_exists($class,$action)){
		if(C(LEAP_ACTION_NOT_EXISTS)){
			$class::_empty();
		}else{
			E(ACTION_NAME.' 方法 不存在');
		}
	};
	#判断请求参数过滤
	switch(REQUEST_METHOD){
		case 'POST':
			array_walk_recursive($_POST, 'leap_filter');
		break;
		case 'GET':
			array_walk_recursive($_GET, 'leap_filter');
		break;
	}
	#实例化对象
   	$controller = new $class();
   	
   	$controller->$action();
   	
  }
  
}
