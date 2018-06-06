<?php
namespace Core;



class View {
	
	#右分界符
	private $_leftLimit   = "{{";
	
	#左分界符
	private $_rightLimit  = "}}";
	
	#设置变量
	private $_argvs       = [];
	
	
	#模板缓存存放目录
	private $_cache_dir    = "";
	
	
	#模板编译目录
	private $_complie_dir = "";
	
	
	#析构函数
	public function __construct(){
		
		$this->_leftLimit   = C(LEAP_TEMPLATE_L_LIMIT) ? C(LEAP_TEMPLATE_L_LIMIT) : $this->_leftLimit;
		$this->_rightLimit  = C(LEAP_TEMPLATE_R_LIMIT) ? C(LEAP_TEMPLATE_R_LIMIT) : $this->_rightLimit;
		$this->_cache_dir   = C(LEAP_TEMPLATE_CACHE);
		$this->_complie_dir = C(LEAP_TEMPLATE_COMPILE);
	}
	
	
	#模板传值
	public function transfer($name='',$value=''){
		$this->_argvs =array_merge($this->_argvs,[$name=>$value]);
		return $this;
	}
	
	
	#显示模板
	#template 模板编译路径
	#filename 定位模板文件
	public function show($template='',$filename=''){
		#获取查找索引
		$index  = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		if(C(LEAP_TEMPLATE_CACHE_OFF) && C(LEAP_TEMPLATE_SATRT_CACHE)){
			$compile = complie_template(MODULE_NAME.'/'.CONTROLLER_NAME,ACTION_NAME);
			if(!$compile){
				$compile = reset($this->template($template,$filename,$cache));
			}
		}else{
			#直接编译模板
			$compile = reset($this->template($template,$filename,$cache));
		}
		#变量 显示
		extract($this->_argvs);
		include $compile;
		exit();
	}
	
	
	#获取模板内容
	#template 模板编译路径
	#filename 定位模板文件
	public function fetch($template='',$filename=''){
		#获取查找索引
		$index  = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		if(C(LEAP_TEMPLATE_CACHE_OFF) && C(LEAP_TEMPLATE_SATRT_CACHE)){
			$compile = complie_template(MODULE_NAME.'/'.CONTROLLER_NAME,ACTION_NAME);
			if(!$compile){
				$compile = reset($this->template($template,$filename,$cache));
			}
		}else{
			#直接编译模板
			$compile = reset($this->template($template,$filename,$cache));
		}
		return  file_get_contents($compile);
	}
	
	#映射模板路径,生成模板信息
	#comolie_path    模板路径星信息
	#filename		  模板文件信息
	#cache 			 是否开启单个缓存
	public function template($compile_path='',$filename='',$cache=false){
		#自动定位到所需模块
		if(!isEmpty($compile_path)){
			$compile_path = APP_PATH.MODULE_NAME.'/'.ucfirst(C(LEAP_VIEW_NAME)).'/'.CONTROLLER_NAME;
		}else{
			#统一分隔符
			$compile_path = APP_PATH.str_replace('\\','/',$compile_path);
			#拆分后缀
			$ext  = explode('/',$compile_path);
			if(is_file(end($ext))){
				array_pop($ext);
			}
			$compile_path = join('/',$ext);
		}
		#自动定位方法
		if(!isEmpty($filename)){
			$filename = ACTION_NAME.'.'.strtolower(C(LEAP_VIEW_SUFFIX));
		}else{
			#定位模板是否有后缀
			if(!strstr(C(LEAP_VIEW_SUFFIX),$filename)){
				$filename .= '.'.C(LEAP_VIEW_SUFFIX); 
			}
		}
		#判断模板文件是否存在
		if(!file_exists($compile_path.'/'.$filename)){E($compile_path.'/'.$filename.' 模板文件不存在');}
		#获取文件信息
		$content = file_get_contents($compile_path.'/'.$filename);
		#将文件匹配替换
		$content = $this->view_regx($content);
		#自动定位编译目录
		$compile_template = RUNTIME_PATH.C(LEAP_TEMPLATE_COMPILE).'/'.MODULE_NAME.'/'.CONTROLLER_NAME;
		#自动定位缓存编译目录
		$cache_template   = RUNTIME_PATH.C(LEAP_TEMPLATE_CACHE).'/'.MODULE_NAME.'/'.CONTROLLER_NAME;
		#生成模板编译文件
		$comp_dir = \Core\Build::build_dir($compile_template);	
		#判断是否开启缓存
		if(C(LEAP_TEMPLATE_CACHE_OFF)){		
			$cache_dir = \Core\Build::build_dir($cache_template);
			if($cache_dir){
				$last_cache_path = \Core\Build::build_file($cache_dir,$filename,$content,true,C(LEAP_COMPLIE_SUFFIX));
			}
		}
		#生成编译模板
		if($comp_dir){
			$last_complie_path =  \Core\Build::build_file($comp_dir,$filename,$content,true,C(LEAP_COMPLIE_SUFFIX));
		}
		return ['compile'=>$last_complie_path,'cache'=>$last_cache_path];
	}
	
	
	#自编译模板规则
	#
	#
	public  function view_regx($content){
		#编译模板规则
		$partten = [
					 "#\s*$this->_leftLimit\s*if\s*condition=[\"|\'](.*)[\"|\']\s*$this->_rightLimit\s*#i",  #if 判断
					 "#\s*$this->_leftLimit\s*elseif\s*condition=[\'|\"](.*?)[\'|\"]\s*$this->_rightLimit\s*#i", #else if
					 "#\s*$this->_leftLimit\s*else\s*$this->_rightLimit\s*#i",								 #else
					 "#\s*$this->_leftLimit\s*\/if\s*$this->_rightLimit\s*#i",
					 "#\s*$this->_leftLimit\s*foreach\s*name=[\"|\'](.*?)[\"|\']\s*item=[\"|\'](.*?)[\"|\']\s*key=[\"|\'](.*?)[\"|\']\s*$this->_rightLimit#i",      #循环变量  
					 "#\s*$this->_leftLimit\s*for\s*start=[\"|\'](.*?)[\"|\']\s*end=[\"|\'](.*?)[\"|\']\s*index=[\"|\'](.*?)[\"|\']\s*$this->_rightLimit#i",
					 "#\s*$this->_leftLimit\s*(\/for)\s*$this->_rightLimit#i",
					 "#\s*$this->_leftLimit\s*(\/foreach)\s*$this->_rightLimit#i",
					 "#\s*$this->_leftLimit\s*(.*?)\.(.*?)\.(.*?)\.(.*?)\.(.*?)\s*$this->_rightLimit\s*#i",  #解析四维数组
					 "#\s*$this->_leftLimit\s*(.*?)\.(.*?)\.(.*?)\.(.*?)\s*$this->_rightLimit\s*#i",         #解析三维数组
					 "#\s*$this->_leftLimit\s*(.*?)\.(.*?)\.(.*?)\s*$this->_rightLimit\s*#i",				 #解析数组二维数组
					 "#\s*$this->_leftLimit\s*(.*?)\.(.*?)\s*$this->_rightLimit\s*#i",                       #解析数组一维数组
					 "#\s*$this->_leftLimit\s*(.*?)\s*$this->_rightLimit\s*#i",					             #输出变量
					];
		#替换模板规则
		$replace = [
					 "<?php if(\\1){ ?>",
					 "<?php }else if(\\1){ ?>",
					 "<?php  }else{ ?>",
					 "<?php }?>",
					 "<?php foreach(\\1 as $\\3 => $\\2){?>",
					 "<?php for(\\3=\\1;\\3 <= \\2;\\3++){?>",
					 "<?php } ?>",
					 "<?php } ?>",
					 "<?php echo \\1['\\2']['\\3']['\\4']['\\5'];?>",
					 "<?php echo \\1['\\2']['\\3']['\\4'];?>",
					 "<?php echo \\1['\\2']['\\3'];?>",
					 "<?php echo \\1['\\2'];?>",
					 "<?php echo \\1;?>",
					];
		
		
		#执行替换
		return preg_replace($partten,$replace,$content);
	}
	
}






















