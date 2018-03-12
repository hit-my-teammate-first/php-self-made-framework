<?php
class Controller{
    public  function __construct(){
        $this->initSess();
        $this->CheckLogin();
       
    }
    
    protected function initSess(){
        new SessionLib();
        session_start();
        //$_SESSION['U']="ALKDJFLK";
    }
    
    /*
     * 成功跳转方法
     * @param $url string 跳转地址
     * $param $msg string 显示信息
     * $param $time int 页面停留时间
     */
    public function success($url,$msg='',$time=3){
            $this->jump($url,$msg,$time,true);
        }
    /*
     * 失败跳转
     */
    public function erro($url,$msg='',$time=3){
            $this->jump($url,$msg,$time,false);
        }
    /*
     * 检查登录权限
     */ 
    public function CheckLogin(){
        if(strtolower(CONTROLLER_NAME)==login){
            return;
        }
        if($_SESSION['admin']==""){
            header('location:index.php?p=admin&c=Login&a=login');
            exit;
        }
       
    }
        /*
         * 跳转方法
         * 
         */
        private function jump($url,$msg='',$time=3,$flag=true){
            if($msg==''){
                header("location:{$url}");
            }else{
                if($flag){
                    $path = '<img src="./Public/images/true.jpg"/>';
                }else{
                    $path = '<img src="./Public/images/erro.jpg"/>';
                }
                echo <<<jump
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="{$time};URL={$url}" />

<title>无标题文档</title>
<style type=text/css>
body{
	text-align:center;
	font-size:36px;
	background-color:#3399FF;
	color:#FFFFFF
	padding-top:30px;
	font-family:'微软雅黑';
}
</style>
</head>

<body>
{$path}
{$msg}
</body>
</html>
jump;
            }
        }
}
