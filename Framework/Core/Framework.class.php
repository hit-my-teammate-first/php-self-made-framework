<?php
//自动加载类
class Framework{
    public static function Run(){
        self::initConst();
        self::initConfig();
        self::initRoutes();
        self::autoload($class_name);
        self::initRegisterAutoload();
        self::initDisplay();
    }
    private static function initConst(){
        define('DS',DIRECTORY_SEPARATOR);
        define('ROOT_PATH',getcwd().DS);
        define('APP_PATH',ROOT_PATH .Application .DS);
        define('FRAME_PATH',ROOT_PATH .Framework .DS);
        define('CONFIG_PATH',APP_PATH .Config .DS);
        define('CONTROLLER_PATH',APP_PATH .Controller .DS);
        define('MODEL_PATH',APP_PATH .Model .DS);
        define('VIEW_PATH',APP_PATH .View .DS);
        define('Core_PATH',FRAME_PATH .Core .DS);
        define('Lib_PATH',FRAME_PATH .Lib .DS);   
    }
    /*
     * 导入配置文件
     */
    private static function initConfig(){
        //echo CONFIG_PATH;
        $GLOBALS['config']= require CONFIG_PATH .'config.php';
    }
    /*
     * 确定路由
     */
    private static function initRoutes(){
        $p = isset($_REQUEST['p'])?$_REQUEST['p']:$GLOBALS['config']['app']['default_platform'];
        $c = isset($_REQUEST['c'])?$_REQUEST['c']:$GLOBALS['config']['app']['default_controller'];
        $a = isset($_REQUEST['a'])?$_REQUEST['a']:$GLOBALS['config']['app']['default_action'];
        define('PLATFORM_NAME',$p);
        define('CONTROLLER_NAME',$c);
        define('ACTION_NAME',$a);
        define('__URL__',CONTROLLER_PATH .PLATFORM_NAME.DS);
        define('__VIEW__',VIEW_PATH .PLATFORM_NAME.DS);
      
    }
    /*
     * 自动加载函数
     */
    private static function autoload($class_name){
        $class_map=array(
            'ConnDB'=>Core_PATH .'ConnDB.class.php',
            'Model'=>Core_PATH.'Model.class.php',
            'Controller'=>Core_PATH .'Controller.class.php'
        );
        if(isset($class_map[$class_name])){
            require $class_map[$class_name];
        }elseif(substr($class_name, -5)=='Model'){
            require MODEL_PATH .$class_name .'.class.php';
        }elseif (substr($class_name, -10)=='Controller') {
            require __URL__ .$class_name .'.class.php'; 
        }elseif (substr($class_name,-3=='Lib')) {
            require Lib_PATH .$class_name .'.class.php';
        }
    }
    
    private static function initRegisterAutoload(){
        spl_autoload_register('self::autoload');
    }
    private static function initDisplay(){
        $controller = CONTROLLER_NAME.'Controller';
        $action = ACTION_NAME.'Action';
        $ctrl = new $controller();
        $ctrl->$action();
    } 
}