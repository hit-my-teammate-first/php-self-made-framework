<?php
class CaptchaLib{
    /*
     * 生成验证码
     */
    public function generalCaptcha(){
        $image_height=20;
        $image_width=145;
        for($i=0;$i<4;$i++){
                $new_number.=dechex(rand(0,15));
                //return $new_number;
        }
        $_SESSION['captcha']=$new_number;
        $num_image=imagecreate($image_width,$image_height);  //生成图片
        imagecolorallocate($num_image,255,255,255);          //设置图片颜色
        for($i=0;$i<strlen($_SESSION['captcha']);$i++){      //在图片上显示验证码
                $font=rand(3,5);
                $x=rand(1,6)+$image_width*$i/4;
                $y=rand(1,$image_height/4);
                $color=imagecolorallocate($num_image,rand(0,255),rand(0,255),rand(0,255));
                imagestring($num_image,$font,$x,$y,$_SESSION['captcha'][$i],$color);
                }
                ob_clean();    //清除缓存
                header("content-type:image/png");
                imagepng($num_image);
                imagedestroy($num_image);
                
    }
    //检测验证码正不正确，不区分大小写
    public function checkCaptcha($cpt){
        return strtolower($cpt) == strtolower($_SESSION['captcha']);
    }
}
