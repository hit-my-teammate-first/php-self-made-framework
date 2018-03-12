<?php
class categoryController extends Controller{
    public function listAction(){
        $model=new CategoryModel();
        $list=$model->getCategoryTree();
        require __VIEW__.'category_list.html';
    }
    public function addAction(){
        $model=new CategoryModel();
        if(!empty($_POST)){
            $data['name']=$_POST['cat_name'];
            $data['parentid']=$_POST['parent_id'];
            $data['sort_order']=$_POST['sort_order'];
            if($model->insert($data)){
                $this->success('index.php?p=Admin&c=Category&a=list', '添加成功',1);
            }else{
                $this->erro('index.php?p=Admin&c=Category&a=add','添加失败');
            }
            exit;
        }
        $list=$model->getCategoryTree();
        require __VIEW__.'category_add.html';
    }
    
    public function updateAction(){
        $model=new CategoryModel();
        $id=(int)$_GET['id'];
        if(!empty($_POST)){
            $data['name']=$_POST['cat_name'];
            $data['parentid']=$_POST['parent_id'];
            $data['sort_order']=$_POST['sort_order'];
            $data['id']=$id;
            //自己不能是自己的子集
            if($data['parentid']==$id){
                $this->erro('index.php?p=Admin&c=Category&a=list', '自己不能是自己的子集',2);
                exit;
            }
            //指定的父级不能是自己的后代
            $sublist=$model->getCategoryTree($id);     //当前节点下的所有子元素
            foreach($sublist as $rows){
                if($rows['id']==$data['parentid']){
                   $this->erro('index.php?p=Admin&c=Category&a=list', '指定的父级不能是自己的后代',2);  
                }
               
            }
            if($model->update($data)){
                $this->success('index.php?p=Admin&c=Category&a=list', '修改成功',1);
            }else{
                $this->erro('index.php?p=Admin&c=Category&a=list', '修改失败',1);
            }
            exit;
        }
        $info=$model->find($id);
        $list=$model->getCategoryTree();
        require __VIEW__.'category_update.html';
    }
}
