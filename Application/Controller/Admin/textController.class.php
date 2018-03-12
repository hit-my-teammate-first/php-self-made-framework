<?php
class textController extends Controller{
	
	/*
	*获取内容清单
	*/
	public function ListAction(){
		$model = new textModel;
		$rs=$model->getList();
		require __VIEW__ .'getlist.html';
	}

	public function addAction(){
		echo '添加页面';
		require __VIEW__ .'addList.html';

	}

	public function updataAction(){
		echo '修改页面';
		require __VIEW__ .'updataList.html';
	}
	public function delAction(){
		$id=$_GET['id'];
		$model =new textModel;
		$rs=$model->deltext($id);
		if($rs){
                    $this->success('index.php?p=admin&c=text&a=list','删除成功',1);
			
		}else{
                    $this->erro('index.php?p=admin&c=text&a=list','删除失败');
		}
    
        }
        
     
        }
        
        
        
        
        



?>