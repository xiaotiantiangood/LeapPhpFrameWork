<?php
   #快捷函数



	#C 设置配置项方法
	#options 配置文件选项   字符串 或者 数组 ,也可 使用  
	#value   配置文件值
	#return  array
	function C($options='',$value=''){
		static $config = [];
		#判断只有数据
		if(isEmpty($options) && !isEmpty($value)){
			#判断是数组--设置配置文件
			if(is_array($options)){
				$config = !isEmpty($config) ? $options : array_merge($config,$options);
			}else if(is_string($options)){ #获取配置项
				if(strpos($options,'.')){
				   $argv = explode('.',$options);
				   	return $config[reset($argv)][end($argv)];
				}else{
					return $config[$options];
				}
			}
		}else if(isEmpty($options) && isEmpty($value)){
			if(is_array($options)){return false;}
			if(count($value) > 3){return false;}
			if(strpos($options,'.')){
				$split = explode('.',$options);
				if(count($split) == 2){
					$config = array_merge($config,[reset($split)=>[end($split)=>$value]]);
				}else if(count($split) == 3){
					$config = array_merge($config,[reset($split)=>[$split[1]=>[end($split)=>$value]]]);
				}
			}else{
				$config[$options] = $value;
			}
			
		}
		return $config;
	}
	
	
	#
	#错误异常抛出
	function E($message='',$code=0){
		throw new ThrowException\LeapException($message,$code);
	}
	
	

	#
	#
	#获取请求参数
	function I($options='',$default=null,$func='htmlspecialchars'){
		#获取数据操作
		if(!isEmpty($options)){return false;}
	    #自动获取类型
	    if(strpos($options,'.')){
	    	list($method,$value) = explode('.',$options,2);
	    }else{
	    	#自动识别get OR Post
	    	$method = 'AUTO';
	    }
	    switch(strtoupper($method)){
	    	case 'GET':$input = $_GET;break;
	    	case 'POST':$input = $_POST;break;
	    	case 'SESSION':$input = $_SESSION;break;
	    	case 'COOKIE':$input = $_COOKIE;break;
	    	case 'SERVER':$input = $_SERVER;break;
	    	case 'REQUEST':$input = $_REQUEST;break;
	    	case 'AUTO':
	    		switch(strtoupper($_SERVER['REQUEST_METHODS'])){
	    			case 'POST':$input = $_POST;break;
	    			case 'GET':$input = $_GET;break;
	    			default:$input = $_GET;break;
	    		}
	    	break;
	    }
	    #全部转换为小写
	    $input = array_change_key_case($input,CASE_LOWER);
	    #判断
	   	if(isEmpty($value)){
			$input = 
	   		$data = $input[strtolower($value)];
	   		
	   	}else if($method == 'AUTO'){
	   		$data = $func($input[strtolower($options)]) ?  $func($input[strtolower($options)]) : $default;
	   	}else{
	   		foreach($input as $k => $v){
	   			 $data[$k] = $func($v) ? $func($v) : $default;
	   		}
	   	}
	   	return $data;
	}
	
	
	
	
	
	#检测数据是否为空
	#val     字符串 
	#return  boolean
	function isEmpty($val=''){
		if(!$val || 
			!isset($val) || 
			empty($val)  || 
			$val == '0'){
			return false;
		}
		return true;
	}
	
	#name redirect 地址跳转
	#argv url      跳转的url地址
	#argv time     跳转的时间   default 0
	#argv msg      跳转的消息
	function redirect($url='',$time=0,$msg=''){
		if(!$url){E('Function redirect Must  Not Is Empty');}
		if(!$msg)$msg = '将在'.$time.'秒后跳转';
		if($time == 0){
			header('location:'.$url);
			exit();
		}else{
			header('refresh:'.$time.';url='.$url);
			exit($msg);
		}
	}
	
	#
	#
	#加载初始配置文件
	function load_config($file='',$callback=''){
		$ext = pathinfo($file,PATHINFO_EXTENSION);
		if(file_exists($file)){
			switch($ext){
				case 'php':
					return include $file;
				break;
			}
		}
		return false;
	}
	
	
	#导入类库 或者文件
	function import($class='',$namespace='',$ext='.php'){
		static $_import = [];
		if($_import[$namespace.$class]){
			return $_import[$namespace.$class];
		}
		#获取导入文件名
		if(!isEmpty($class)){return false;}
		#判断是否指定所在文件夹
		if(isEmpty($namespace)){
			if(strpos($namespace,'.')){
				$name = strstr($namespace,'.',true);
				if(in_array($name,C('LEAP_CORE_MODULE'))){
					$path = __MAIN__;
				}else{
					$path = APP_PATH;
				}
			}else{
				$path = APP_PATH;	
			}
		}else{
			$path = APP_PATH;
		}
		$filepath = $path.str_replace('.','/',$namespace).$class.$ext;
		#判断文件是否已经存在
		if(file_exists($filepath)){
			return include $filepath;
		}
	}
	
	#
	#
	#explode 衍生 函数
	function explode_array($options='',$split=','){
		if(is_array($options)){
			return $options;
		}else if(is_string($options) && $split){
			return explode($split,$options);
		}else{
			return false;
		}
	}
	
	
	function leap_filter($optios){
		#敏感字符过滤,mysql防注入过滤
		$argv = htmlspecialchars($optios);
		#设置mysql验证
		$sensbefore = ['>','<','%','$'];
		$sensafter  = ['&gt;','&lt;','',''];
		#替换
		$argv = str_replace($sensbefore,$sensafter,$optios);
	 	return $argv;
	}
	
	#对应模板编译缓存--定位到缓存文件
	#dir  缓存文件目录
	#file 缓存文件名称
	function complie_template($dir='',$file=''){
		$func = C(LEAP_FILE_BASE_TYPE);
		if(isEmpty($dir)&& isEmpty($file)){
			#获取缓存存放目录
			$path = RUNTIME_PATH.C(LEAP_TEMPLATE_CACHE).'/'.$dir.'/';
			$mainfile = $func($file.'.'.C(LEAP_VIEW_SUFFIX)).'.'.C(LEAP_COMPLIE_SUFFIX);
			#检测模版是否存在
			if(file_exists($path.$mainfile)){
				return $path.$mainfile;
			}
			return false;
		}
		return false;
	}
	
	
	#检测组建是否已经安装
	function check_extension($name){
		if(!extension_loaded($name)){
			 E($name.' extension is not install');
		}
	}
	
	

