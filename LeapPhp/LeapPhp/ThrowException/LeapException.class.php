<?php
namespace ThrowException;


class  LeapException extends \Exception{
	
	

  #捕获致命错误
  public static function fatalError(){
  	  $error = error_get_last();
      switch ($error['type']){
      	case E_ERROR:
      	case E_PARSE:
      	case E_CORE_ERROR:
      	case E_USER_ERROR:
      	case E_COMPILE_ERROR:
      		ob_end_clean();
      		$error['function'] = 'fatalError';
      		self::errMsg($error);
      	break;
      }
  }
  
  
  public static function  appError($errno, $errstr, $errfile, $errline){
  	 $e = ['message'=>$errstr,'file'=>$errfile,'line'=>$errline];
 	 switch($errno){
 	 	case E_ERROR:
 	 	case E_PARSE:
 	 	case E_CORE_ERROR:
 	 	case E_COMPILE_ERROR:
 	 	case E_USER_ERROR:
 	 	   self::errMsg($e);
 	 	break;
 	 }
  }
  
  #获取用户自定义级别错误
  public static function exceError($e){
  	$trace = $e->getTrace();
  	$error['message'] = $e->getMessage();
  	if($trace[0]['function'] =='E'){
  		$error['line'] = $trace[0]['line'];
  		$error['file'] = $trace[0]['file'];
  		$error['trace'] = $e->getTraceAsString();
  	}else{
  		$error['line'] = $e->getLine();
  		$error['file'] = $e->getFile();
  	}
  	self::errMsg($error);
  }
  
 
  public static function errMsg($e){
     #加载tpl模板
     if(APP_DEBUG){
     	if($e['function'] == 'fatalError'){
     		ob_start();
     		debug_print_backtrace();
     		$e['trace'] = ob_get_clean();
     	}
     	include __MAIN__.'Tpl/'.(C('LEAP_ERROR_TPL') ? C('LEAP_ERROR_TPL'):'Exception.tpl');
     	die();
     }else{
     	if(!isEmpty(C('LEAP_404_PAGE')) || !file_exists(C('LEAP_404_PAGE'))){
     		unset($e);
     		$e['message'] = C('LEAP_SHOW_MSG') ? C('LEAP_SHOW_MSG') : '页面错误,请重试!';
     		include __MAIN__.'/Tpl/Exception.tpl';
     		exit();
     	}else{
     		redirect(C('LEAP_404_PAGE'));
     	}
     }
  }
	
}
