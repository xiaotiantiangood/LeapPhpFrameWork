<?php
#配置文件

return [
   		 'APP_DEBUG'				=>TRUE,								#是否开启调试模式
		 'LEAP_SUFFIX_NAME'    		=> '.class.php',    				#类文件后缀名
		 'LEAP_CORE_MODULE'    		=> ['Core','ThrowException'],       #核心模块列表
		 'LEAP_AUTO_NAMESPACE' 		=> ['Weight'],      				#自动加载类--，命名空间
		 'LEAP_MODULE_NAMESPACE'    => ['Home','Admin'], 				#加载项目命名空间列表
		 'LEAP_ERROR_TPL'           => '',								#默认的错误显示模板
		 'LEAP_SHOW_MSG'            => '',								#错误显示消息默认
		 'LEAP_404_PAGE'			=> '',								#默认的跳转界面
		 'LEAP_FUNC_NAME'			=> 'function',						#设置公共文件名
		 'LEAP_MODULE_LIST'		    => 'Home,Admin',					#加载绑定的模块列表	
		 'LEAP_NOTICE_ERROR'	    => false,							#开启提示级别错误提示
		 'LEAP_ROUTE_MODE'		    => 1,								#全站路由模式  1 普通模式 2 pathinfo模式  3 兼容模式
		 'LEAP_DEFAULT_MODEL'	    => 'm',								#默认的url 模块系数
		 'LEAP_DEFAULT_CONTROLLER'	=> 'c',								#默认的url 控制器系数
		 'LEAP_DEFAULT_ACTION'      => 'a',								#默认的url 方法系数	
		 'LEAP_URL_MODE'			=>  3,								#url规则   1普通模式  2pathinfo  3.兼容模式	 
		 'LEAP_BIND_MODULE'		    => 'Home',						    #默认绑定模块
		 'LEAP_BIND_CONTROLLER'		=> 'Index',							#默认绑定控制器
		 'LEAP_BIND_ACTION'			=> 'Index',							#默认绑定的方法  
		 'LEAP_VAR_PATHINFO'		=> 'p',								#获取pathinfio 参数	
		 'LEAP_VAR_CON_NAME'		=> 'controller',				    #定义命名控制器命名空间		
		 'LEAP_ACTION_NOT_EXISTS'   => '',								#跳转空方法
		 'LEAP_CONTROLLER_NOT_EXISTS' => '',							#跳转空控制器		
		 'LEAP_TEMPLATE_L_LIMIT'	=>'',								#模板右侧分割符
		 'LEAP_TEMPLATE_R_LIMIT'    =>'',								#模板左侧分割符
		 'LEAP_TEMPLATE_COMPILE'    =>'compile',						#模板编译存放目录
		 'LEAP_TEMPLATE_CACHE'		=> 'cache',							#模板缓存存放目录		
		 'LEAP_FILE_BASE_TYPE'	    => 'md5',							#是否对文件编码	
		 'LEAP_VIEW_SUFFIX'		    => 'html',							#视图层文件后缀
		 'LEAP_VIEW_NAME'			=> 'view',							#视图层文件名字	
		 'LEAP_TEMPLATE_CACHE_OFF'  => true,							#是否开启缓存文件
		 'LEAP_COMPLIE_SUFFIX'		=> 'php',							#生成编译模板后缀
		 'LEAP_TEMPLATE_SATRT_CACHE'=> false,							#是否进入页面直接映射到缓存页面
		 'LEAP_SQL_TYPE'		    =>'mysql',							#数据库链接类型
		 'LEAP_SQL_USER'		    => 'root',							#数据库用户名
		 'LEAP_SQL_PASS'			=> 'root',							#数据库密码
		 'LEAP_SQL_HOST'			=> '127.0.0.1',						#数据库主机
		 'LEAP_SQL_NAME'		    => 'cailehui',						#数据库名称	
		 'LEAP_SQL_PREIX'		    => 'clh_',								#数据库表前缀
		 'LEAP_SQL_ENCODING'	    => 'utf8',							#数据库编码方式
       ];
