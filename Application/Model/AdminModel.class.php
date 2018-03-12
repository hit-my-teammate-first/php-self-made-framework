<?php
class AdminModel extends Model{
    /*
     * 通过用户名和密码获取登录权限
     * @param $name string 用户名
     * @param $pwd string 密码
     * @return $info 用户信息
     */
    public function getAdminByNameAndPwd($name,$pwd){
        $pwd=md5($pwd);
        $sql = "select * from admin where name='$name'and pwd='$pwd'";
        return $this->db->query($sql,"row");
    }
    /*
     * 通过cookie登录
     */
    public function getAdminByCookie(){
        if(!isset($_COOKIE["id"])|| !isset($_COOKIE["pwd"])){
           return false;
        }
        $id = $_COOKIE['id'];
        $pwd = $_COOKIE['pwd'];
        $sql = "select * from admin where id=$id";
        $info = $this->db->query($sql);
        if($info){
            return md5($info[0]['name'].$info[0]['pwd'].$GLOBALS['config']['app']['key'])==$pwd?$info:false;
        }
        return false;
    }
    /*
     * 登录成功后更新登录信息
     */
    public function updataLoginInfo(){
        $ip=ip2long($_SERVER['REMOTE_ADDR']);  //客户端地址
        $time=time();
        $id=$_SESSION['admin'][0]['id'];
        $sql="update admin set last_login_ip=$ip,last_login_time=$time where id=$id";
        $rs=$this->db->updt($sql);
        return $rs;
    }
    
    
}