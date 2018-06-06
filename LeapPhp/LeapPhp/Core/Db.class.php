<?php
#数据库操作类
namespace Core;


class Db{
	
	private $_sqlType = 'mysql';   #数据库连接类型  -- 默认为mysql
	
	private $_user    = '';        #数据库用户名
	
	private $_pass    = '';        #数据库密码
	
	private $_host    = '';        #数据库主机
	
	private $_db	  = '';        #数据库
	
	private $_pdo     = '';        #实例化pdo类
	
	private $_options = [];		   #参数配置项
	
	protected $_tableName = '';    #表名
	
	private $_preix	  = '';		   #表前缀
	
	public $_query    = '';		   #sql拼接语句
	
	public $_field    = '';        #表字段名
	
	public $_alias    = '';        #表别名
	
	#初始化函数
	public function __construct(){
		#检测检测组件是否安装
		check_extension('PDO');
		#初始话sql 数据
		 $this->_initSql()->connect();
	}
	
	
	#初始化数据库连接
	public function _initSql(){
		#检测连接数据库类型
		$this->_sqlType  = C(LEAP_SQL_TYPE) ? C(LEAP_SQL_TYPE) : $this->_sqlType;
		#数据库用户名
		$this->_user     = C(LEAP_SQL_USER) ;
		#数据库密码
		$this->_pass     = C(LEAP_SQL_PASS) ;
		#数据库主机
		$this->_host     = C(LEAP_SQL_HOST) ;
		#数据库名称
		$this->_db       = C(LEAP_SQL_NAME) ;
		#设置配置项
		$this->_options  = [];
		#设置表前缀
		$this->_preix  = C(LEAP_SQL_PREIX);
		
		return $this;
	}
	
	#切换数据库
	public function Db($index=''){
		#判断是否，切换数据库
		$db = C(strtoupper($index));
		if($index && $db){
			$this->_user  = $db['DB_USER'];
			$this->_pass  = $db['DB_PASS'];
			$this->_host  = $db['DB_HOST'] ? $db['DB_HOST'] : C(LEAP_SQL_HOST);
			$this->_db    = $db['DB_NAME'] ? $db['DB_NAME'] : C(LEAP_SQL_NAME);
			$this->_preix = $db['preix'];
		}
		return $this;
	}
	
	
	#配置数据库配置参数
	public function config($options=[]){
		$this->_options = array_merge($this->_options,$options);
		return $this;
	}
	
	
	#连接
	public function connect(){
		$db = $this->_sqlType.':host='.$this->_host.';dbname='.$this->_db;
		$this->_pdo =  new \PDO($db,$this->_user,$this->_pass,$this->_options);
		#开启预处理方式
		$this->_pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		#判断是否开启无缓冲模式
		if($this->_options['buffer']){
			$this->_pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,false);
		}
		#开启pdo 报错模式
		$this->_pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
		#设置数据库编码方式
		$this->_pdo->query('set names '.C(LEAP_SQL_ENCODING));
		return $this;
	}
	
	#设置表名
	public function table($tableName=''){
		$this->_tableName  = $tableName;
		return $this;
	}
	
	
	
	#设置表字段
	public function field($field='*'){
		if(is_array($field)){$field = join(',',$field);}
		$this->_field .= $field;
		return $this;
	}
	
	#设置表别名
	public function alias($name=''){
		$this->_alias = ' AS '.$name;
		return $this;
	}
	
	#设置where
	public function where($condition='',$param=''){
		if(is_array($condition)){
			
		}
	}
	
	
	#
	#
	#获取表字段
	public  function getField($_tableName=''){
		try{
			$result = $this->_pdo->prepare('desc '.$this->_preix.$_tableName);
			$result->execute();
			return $result->fetchAll(\PDO::FETCH_ASSOC);
		}catch(\PDOException $e){
			E($e->getMessage());
		}
	}
}























