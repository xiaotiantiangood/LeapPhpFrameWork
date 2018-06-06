<?php
namespace Core;


class Route{
	
 
	#Url 调度 生成
	public static function dispatch(){
	 	#获取url 类型  -- 普通模式  
	 	$mode = C('LEAP_URL_MODE');
	 	$varpath = C('LEAP_VAR_PATHINFO');
	 	#获取默认绑定模块
	 	$varmodule = C('LEAP_DEFAULT_MODEL');
	 	#获取默认的绑定控制器
	 	$varcontroller = C('LEAP_DEFAULT_CONTROLLER');
	 	#获取默认的绑定方法
	 	$varaction   = C('LEAP_DEFAULT_ACTION');
		
		#判断是否兼容模式
	 	if($_GET[$varpath]){
	 		$_SERVER['PATH_INFO'] = $_GET[$varpath];
	 		unset($_GET[$varpath]);
	 	}
		#兼容模式
		if(isEmpty(I('server.path_info'))){
			$argvs = explode('/',I('server.path_info'));
			if(!isEmpty(reset($argvs))){array_shift($argvs);}
			#拆解参数
			foreach($argvs as $k =>$v){
				if($k%2 == 0){
					$_GET[$v] = $argvs[$k+1];
				}
			}
			#将参数写入到
			$_REQUEST = array_merge($_GET,$_POST);
		}
		#获取模块名
		self::getModule($_GET[$varmodule]);
		#获取控制器名
		self::getController($_GET[$varcontroller]);
		#设置方法名
		self::getAction($_GET[$varaction]);
	 	unset($_GET[$varaction]);
	 	unset($_GET[$varcontroller]);
	 	unset($_GET[$varmodule]);
	}	
	
	
	#获取模块名
	public static function getModule($module){
		$m = isEmpty($module) ? ucfirst($module) :C('LEAP_BIND_MODULE');
		#判断模块是否存在
		if(!is_dir(APP_PATH.$m) || !file_exists(APP_PATH.$m)){
			E($m.'模块不存在');
		}
		defined('MODULE_NAME') or define('MODULE_NAME',$m);
	}
	
	#获取控制器名
	public static function getController($controller){
		$c = isEmpty($controller) ? $controller : C('LEAP_BIND_CONTROLLER');
		defined('CONTROLLER_NAME') or define('CONTROLLER_NAME', $c);
	}
	
	#获取方法名
	public static function getAction($action){
		$a = isEmpty($action) ? $action : C('LEAP_BIND_ACTION');
		defined('ACTION_NAME')  or define('ACTION_NAME',$a);
	}
	
}
