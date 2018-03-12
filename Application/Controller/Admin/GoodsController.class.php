<?php
class GoodsController extends Controller{
    //显示商品列表
    public function listAction(){
        $model=new GoodsModel;
        $count=$model->getCount();                //总记录数
        $pagesize=$GLOBALS['config']['admin']['goods_pagesize'];   //页面大小
        $pagecount=ceil($count/$pagesize);                        //总页数
        $pageno= isset($_GET['pageno'])?$_GET['pageno']:1;        //页码
        $list=$model->getPageList($pageno, $pagesize);
        require __VIEW__.'goods_list.html';
    }
    /*
     * 添加商品
     */
    public function addAction(){
        if(!empty($_POST)){
            $data['goodsname']=$_POST['goods_name'];
            $data['price']=$_POST['shop_price'];
            $data['categoryid']=$_POST['cat_id'];
            $data['status']=isset($_POST['status'])?implode(',',$_POST['status']):'';
            $data['goods_desc']=$_POST['goods_brief'];
            //文件上传及格式验证
            $upload=new UploadLib();
            if(!$path=$upload->upload($_FILES['goods_img'])){
                $this->erro('index.php?p=Admin&c=Goods&a=add',$upload->getErro());
                exit;
            }
            $data['img']=$path;   //源图路径
            //生成缩略图
            $image=new ImageLib();
            $src_path=$GLOBALS['config']['app']['upload_path'].$path;
            $data['img_thumb_s']=$image->thumb($src_path, 50,50,'s_',true);
            $data['img_thumb_m']=$image->thumb($src_path, 75,75,'m_',true);
           //写入数据库
            $model=new GoodsModel();
            if($model->insert($data)){
                $this->success('index.php?p=Admin&c=Goods&a=list','添加成功',2);
            }else{
                $this->erro('index.php?p=Admin&c=Goods&a=add','添加失败',2);
            }
            
        }
        $categoryModel=new CategoryModel;
        $cat_list=$categoryModel->getCategoryTree();
        require __VIEW__.'goods_add.html';
    }
    /*
     * 更新商品
     */
    public function modifyAction(){
        $goodsid=(int)$_GET['goodsid'];
        //获取数据信息
        $goods_model=new GoodsModel();
        $goods_info=$goods_model->find($goodsid);
        //上传更新数据
        if(!empty($_POST)){
          $data['goodsname']=$_POST['goods_name'];
          $data['price']=$_POST['shop_price'];
          $data['categoryid']=$_POST['cat_id'];
          $data['status']= isset($_POST['status'])? implode(',', $_POST['status']):'';
          $data['goods_desc']=$_POST['goods_desc'];
          
          $upload=new UploadLib();
          if($_FILES['goods_img']['name']!=''){
              if(!$path=$upload->upload($_FILES['goods_img'])){
                  $this->erro('index.php?p=Admin&c=Goods&a=modify','修改失败');
              }
              $data['img']=$path;
              $image=new ImageLib();
              $src_path=$GLOBALS['config']['app']['upload_path'].$path;
              $data['img_thumb_s']=$image->thumb($src_path, 40, 30, 's_');
              $data['img_thumb_m']=$image->thumb($src_path, 80, 60, 'm_');
              $this->delRubbishImages($goods_info);
          }
          $data['goodsid']=$goodsid;
          //写入数据库
          $model=new GoodsModel();
            if($model->update($data)){
                $this->success('index.php?p=Admin&c=Goods&a=list','添加成功',2);
            }else{
                $this->erro('index.php?p=Admin&c=Goods&a=add','添加失败',2);
            }
          
        }
        $cate_model=new CategoryModel();
        $cat_list=$cate_model->getCategoryTree();
        require __VIEW__.'goods_update.html';
    }
    /*
     * 删除商品
     */
    public function delAction(){
        $goodsid=(int)$_GET['goodsid'];
        $model=new GoodsModel();
        $goods_info=$model->find($goodsid);
        if($model->del($goodsid)){
            $this->delRubbishImages($goods_info);
            $this->success('index.php?p=Admin&c=Goods&a=list','删除成功',2);
        }else{
            $this->erro('index.php?p=Admin&c=Goods&a=list','删除失败',2);
        }
    }
    /*
     * 删除垃圾图片
     */
    private function delRubbishImages($goods_info){
        $img_path=$GLOBALS['config']['app']['upload_path'].$goods_info[0]['img'];
        $img_s_path=$GLOBALS['config']['app']['upload_path'].$goods_info[0]['img_thumb_s'];
        $img_m_path=$GLOBALS['config']['app']['upload_path'].$goods_info[0]['img_thumb_m'];
        if(file_exists($img_path)){
            unlink($img_path);
        }
        if(file_exists($img_s_path)){
            unlink($img_s_path);
        }
        if(file_exists($img_m_path)){
            unlink($img_m_path);
        }
    }
}

