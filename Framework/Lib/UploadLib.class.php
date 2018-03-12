<?php
class UploadLib{
    private $path;      //文件上传路径
    private $type;      //文件允许上传类型
    private $size;      //文件允许上传大小
    private $erro;      //上传过程产生的错误
    //通过构造函数初始化成员变量
    public function __construct() {
        $this->path=$GLOBALS['config']['app']['upload_path'];
        $this->type=$GLOBALS['config']['app']['upload_type'];
        $this->size=$GLOBALS['config']['app']['upload_size'];
        
    }
    //获取错误
    public function getErro(){
        return $this->erro;
       
    }
    /*
     * 文件上传类
     * @param $file $_FILE对象
     */
    public function upload($file){
    
        //判断错误类型
        $erro=$file['erro'];
        if($erro){
            switch ($erro){
                    case 1:
                        $this->erro='上传文件大小超过配置文件中允许的最大值';
                        return false;
                    case 2:
                        $this->erro='上传文件超过表单允许最大值';
                        return false;
                    case 3:
                        $this->erro='文件只有部分上传';
                        return false;
                    case 4:
                        echo $this->erro='文件没有上传';
                        return false;
                    case 6:
                        $this->erro='找不到临时文件';
                        return false;
                    case 7:
                        $this->erro='文件写入失败';
                        return false;
                    default:
                        $this->erro='未知错误';
                        return false;
            }
        
        }
        
        //验证格式
        if(!in_array($file['type'], $this->type)){
            $this->erro='文件类型不正确，只能是'.implode(',', $this->type);
            return false;
        }
        //验证大小
        if($file['size']>$this->size){
            $this->erro='文件大小超过'.($this->size/1024).'k';
            return false;
        }
        //判断文件是不是通过httpshangc
        if(!is_uploaded_file($file['tmp_name'])){
            $this->erro='文件必须是http上传';
            return false;
        }
        //创建文件夹
        $foldername=date('Y-m-d');         //文件夹名称
        $folderpath=$this->path.$foldername;  //文件夹路径
        if(!file_exists($folderpath)){
            mkdir($folderpath);
        }
        //文件上传
        $filename=uniqid('',true).strrchr($file['name'],'.');   //文件名称
        $filepath=$folderpath.DS.$filename;                            //文件路径
        if(move_uploaded_file($file['tmp_name'], $filepath)){
        return "{$foldername}/{$filename}";
        }else{
            $this->erro='上传失败';
            return false;
        }
        
    }
}

