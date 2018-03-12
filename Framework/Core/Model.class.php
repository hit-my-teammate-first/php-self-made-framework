<?php
abstract class Model{
	protected $db;
	
	public function __construct() {
		$this->initDB();
	}

	private function initDB() {

		
		$this->db=ConnDB::getInstance($GLOBALS['config']['database']);
	}
        private function getTable(){
            //获得当前对象所处于的类
            return substr(get_class($this),0,-5);
        }
        /*
         * 获取主键值
         */
        private function getPrimaryKey($table){
                $rs=$this->db->query("desc $table");
                if($rs[0]['Key']=="PRI"){
                    return $rs[0]['Field'];
                }
                
            }
        /*
         * insert
         */
        public function insert($data){
            $fields=array_keys($data);
            $values= array_values($data);
            //将数组中的每个元素添上反引号
            $values=array_map(function($values){
                return "'{$values}'";
            },$values);
            //将字段分别用逗号隔开
            $fields_str= implode(',', $fields);
            $values_str= implode(',', $values);
            //拼接字符串
            $table= strtolower($this->getTable());
            $sql="insert into $table ($fields_str) values($values_str)";
            return $this->db->insert($sql);
        }
        /*
         * update
         */
        public function update($data){
           $fields= array_keys($data);
           $table= strtolower($this->getTable());//获取当前名
           $rs=$this->getPrimaryKey($table); //获取主键 
           //去除主键下标
           $index= array_search($rs, $fields); //主键下标
           unset($fields[$index]);             //销毁主键
           //遍历循环字段
           $fields= array_map(function($fields) use ($data){
                    return "{$fields}='{$data[$fields]}'";
           }, $fields);
           $fields=implode(',',$fields);
           $sql="update $table set {$fields} where {$rs}=$data[$rs]";
           return $this->db->insert($sql);
        }
        /*
         * 封装万能删除方法
         * @param $id mixed 主键字段的值
         */
        public function del($id){
            $table=$this->getTable();
            $pk=$this->getPrimaryKey($table);
            $sql="delete from $table where `$pk` =$id";
            return $this->db->insert($sql);
        }
        /*
         * 封装万能查询语句
         * @param $field string 排序字段
         * @param $order string 排序方法
         * 返回二维数组
         */
        public function select($field='',$order='asc'){
            $table=$this->getTable();
            $sql="select * from $table ";
            if($field!=''){
                $sql.="order by `{$field}` {$order}";
            }
           return $this->db->query($sql);
        }
        /*
         * 封装万能查询语句
         * @param $field string 排序字段
         * @param $order string 排序方法
         * 返回二维数组
         */
        public function find($id){
            $table=$this->getTable();
            $pk=$this->getPrimaryKey($table);
            $sql="select * from $table where `$pk`=$id ";
            return $this->db->query($sql);
            
        }
        
        /*
         * 封装万能获取当前页数据方法
         * @param $pageno int 当前页码
         * @param $pagesize int 页面大小
         */
        public function getPageList($pageno,$pagesize){
            $table=$this->getTable();
            $pk=$this->getPrimaryKey($table);
            $startno=($pageno-1)*$pagesize;
            $sql="select * from $table order by `{$pk}` desc limit $startno,$pagesize";
            return $this->db->query($sql);
        }
        //封装获取总记录数方法
        public function getCount(){
            $table=$this->getTable();
            $sql="select count(*) from $table";
            return $this->db->select($sql);
        }
        
}