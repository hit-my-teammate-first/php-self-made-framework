<?php
class CategoryModel extends Model{
    /*
     * 将数组创建成树形结构
     * @param 
     */
    private function CreateTree($list,$parentid=0,$deep=0){
		static $tree=array();
		foreach($list as $rows){
			if($rows['parentid']==$parentid){
				$rows['deep']=$deep;
				$tree[]=$rows;
				$this->CreateTree($list,$rows['id'],$deep+1);
			}
		}
		return $tree;
    }
    /*
     * 获得商品类别的树形结构
     */
    public function getCategoryTree($parentid=0){
        $list=$this->select();
        return $this->CreateTree($list,$parentid);
    }
}
