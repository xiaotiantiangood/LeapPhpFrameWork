<?php
header('Content-Type:text/html;Charset=Utf-8');
#
#
#
#
#
#








defined('__ROOT__')  	or 	define('__ROOT__',str_replace('\\','/',dirname(__DIR__))); #定义当前框架根目录
defined('__MAIN__')  	or	define('__MAIN__',__ROOT__.'/LeapPhp/');				   #定义框架核心目录
defined('__COMMON__') 	or  define('__COMMON__','Common/');						   	   #定义公共配置文件目录
defined('COMMON_EXT') 	or  define('COMMON_EXT','.php');							   #定义公共文件名后缀
defined('COMMON_NAME') 	or 	define('COMMON_NAME','function');						   #定义公共方法文件名
defined('CONF_NAME') 	or 	define('CONF_NAME','Config');							   #定义公共配置文件名
defined('RUNTIME_PATH') or  define('RUNTIME_PATH',APP_PATH.'RunTime/');			   	   #模板编译目录

#加载运行文件
if(!require(__MAIN__.'Core/Run.class.php')) die ('Main File Loss');
#主方法
Core\Run::run();



