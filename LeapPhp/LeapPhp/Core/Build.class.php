<?php
namespace Core;


class Build{
	
	

	
	
	
	#创建文件目录
	#path 		 创建的文件路径
	#filename   创建文件的名字
	#date       是否开启日期文件夹爱
	#basecode   是否开启编码方试
	#return     生成的文件夹路径
	public static function build_dir($path='',$date=false,$basecode=false){
		$strPath = '';
		#判断目录是否存在
		if(file_exists($path)){return $path;}
		#统一目录分割
		$path = explode('/',str_replace('\\','/',$path));

		#生成目录文件
		foreach($path as $k => $v){
			$strPath .= $v.'/';
			if(!file_exists($strPath)){mkdir($strPath);}
		}
		#判断是否生成对应日期文件夹
		if(isEmpty($date)){mkdir($strPath.date('Y-m-d'));}
		$strPath = substr($strPath,0,-1);
		return $strPath;
	}
	
	
	#创建文件
	#dir		文件路径
	#filename   文件名字
	#content    文件内容
	#basecode   是否编码
	#exten      文件后缀
	#return     写入文件后的路径
	public static function build_file($dir='',$filename='',$content='',$basecode=false,$exten=''){
		#判断文件夹是否已经存在
		if(!file_exists($dir)){E($dir.' 不存在');}
		#获取文件后缀
		$ext =explode('.',$filename);
		if($basecode){
			$mode = C(LEAP_FILE_BASE_TYPE);
			$file = $dir.'/'.$mode($filename).'.'.($exten ? $exten : end($ext));
		}else{
			$file = $dir.'/'.$mode($filename).'.'.($exten ? $exten : end($ext));
		}
		file_put_contents($file,$content);
		return $file;
	}
}
