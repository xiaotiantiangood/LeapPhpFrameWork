<?php
namespace Core;


class Run{
	
	private static $_map = [];
	
	
	
	
	#
	public static function run(){
		#开启错误级别提示
		if(APP_DEBUG && !NOTICE_ERROR){
			error_reporting(E_ALL^E_NOTICE);
		}
		#初始化配置 --参数
		self::_configInit();
		#自动加载
		spl_autoload_register('Core\Run::autoLoad');
		#错误判断
		register_shutdown_function("ThrowException\LeapException::fatalError");
		set_error_handler("ThrowException\LeapException::appError");
		set_exception_handler("ThrowException\LeapException::exceError");
		#定位项目公共的方法文件
		import(COMMON_NAME,__COMMON__);
		#定位项目公共的配置文件
		C(load_config(APP_PATH.__COMMON__.CONF_NAME.COMMON_EXT));
		#自动寻找模块下公共配置
		$module_list  = explode_array(C('LEAP_MODULE_LIST'));
		if(isEmpty($module_list)){
			foreach($module_list as $k=> $v){
				import(COMMON_NAME,$v.'.'.__COMMON__);
				C(load_config(APP_PATH.$v.'/'.__COMMON__.CONF_NAME.COMMON_EXT));
			}
		}
		#生成绑定模块
		
		#路由模式调度
		\Core\App::run();
	}
	
	
	
	#初始化参数
	public static  function _configInit(){
		#加载初始配置文件
		if(!file_exists(__MAIN__.__COMMON__.COMMON_NAME.COMMON_EXT)){
			die(__COMMON__.COMMON_NAME.COMMON_EXT.' Not Exists');
		}
		#加载公共配置文件 --- 所有目录
		if(!file_exists(__MAIN__.__COMMON__.CONF_NAME.COMMON_EXT)){
			die(__COMMON__.CONF_NAME.COMMON_EXT.'Not Exists');
		}
		require_once(__MAIN__.__COMMON__.COMMON_NAME.COMMON_EXT);
		C(load_config(__MAIN__.__COMMON__.CONF_NAME.COMMON_EXT));
	}
	
	
	#
	#
	#
	#自动加载类
	public static function autoLoad($class){
		if(isEmpty($class)){
			$class = str_replace('\\','/',$class);
			if(self::getMap($class)){
				include self::getMap($class);
			}else if(strpos($class,'/')){
				$name = strstr($class,'/',true);
				$namespace = C('LEAP_AUTO_NAMESPACE');
				#设置核心文件的路径
				if(in_array($name,C('LEAP_CORE_MODULE')) || in_array($name,$namespace)){
					$path = __MAIN__;
				}else{
					$path = APP_PATH;
				}
				$_filePath = $path.$class.C('LEAP_SUFFIX_NAME');
				if(!file_exists($_filePath) || !is_file($_filePath)) 
				  E($_filePath.'文件不存在');
				else 
				  self::setMap($class,$_filePath);
				  include $path.$class.C('LEAP_SUFFIX_NAME');
			}
		}
	}
	
	
	#实例化文件入栈
	public static function setMap($class='',$value=''){
		if(!isEmpty(self::$_map[$class])){
			self::$_map[$class] = $value;
		}
	}
	
	#获取实例化文件
	public static function getMap($class){
		if(!isEmpty(self::$_map[$class])){
			return false;
		}
	}
	

}
