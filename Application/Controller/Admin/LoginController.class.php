<?php
/*
 * *登录控制器
 */
class LoginController extends Controller{
    //登录页面
    public function loginAction(){
        $md=new AdminModel();
        if(!empty($_POST)){
           $captcha = new CaptchaLib();
           $cpt = $_POST['captcha'];
           if(!$captcha->checkCaptcha($cpt)){
               echo "<script>alert('验证码错误')</script>";
               $this->erro('index.php?p=admin&c=Login&a=login');
              exit;
            }
            //$_SESSION['yy']="alksjdh";
            $name = $_POST['username'];
            $pwd = $_POST['password'];
            $info=$md->getAdminByNameAndPwd($name,$pwd);        
            if($info){
                //-------------记住登录开始--------------
                if(isset($_POST['remember'])){
                    setcookie('id',$info[0]['id'],PHP_INT_MAX);
                    setcookie('pwd',md5($info[0]['name'].$info[0]['pwd'].$GLOBALS['config']['app']['key']),PHP_INT_MAX);
                }
                //-------------记住登录结束--------------
                $_SESSION['admin']=$info;  //用户信息存放到sess中
  
                $md->updataLoginInfo();    //更新登录信息
                //var_dump($_SESSION['admin']);
                $this->success('index.php?p=admin&c=Admin&a=admin');
            }else{
                $this->erro('index.php?p=admin&c=Login&a=login','密码或用户名错误',2);
            }
            exit;
        }
        
        
        //如果有cookie,通过cookie登录
        if($info = $md->getAdminByCookie()){
            $_SESSION['admin']=$info;  //用户信息存放到sess中
            $md->updataLoginInfo();    //更新登录信息
            $this->success('index.php?p=admin&c=Admin&a=admin');
            exit;
        }
       require __VIEW__.'Login.html';
 
    }
    //退出
    public function logOutAction(){
           session_destroy();
           $this->success('index.php?p=admin&c=Login&a=Login');
    }
    /*
     * 获取验证码
     */
    public function CaptchaAction(){
       // $_SESSION['asd']="asd";
        $captcha = new CaptchaLib();
        $captcha->generalCaptcha();

    }
}