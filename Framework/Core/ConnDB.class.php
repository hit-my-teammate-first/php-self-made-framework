<?php
/**
*数据库工具类
*/
class ConnDB{
	private $dbms;
	private $dbname;
	private $user;
	private $pwd;
	private $host;
	private $charset=utf8;
	private $dsn;
	private $pdo;
	protected static $_instance = null;

	/*
	*构造函数
	*/
	public function __construct($arr){

		$this->init($arr);
		$this->CONNECT();
		$this->CHARSET();
	}
	/*
	*singleton instance单例模式
	*
	*@return object
	*/
	public static function getInstance($arr){

		if(self::$_instance === null){
			self::$_instance = new self($arr);
		}
		return self::$_instance;
	}

	/**
     * Query 查询
     *
     * @param String $strSql SQL语句
     * @param String $queryMode 查询方式(All or Row)
     * @return Array
     */
	 public function query($strsql, $queryModel = "All"){
			
			$record= $this->pdo->query($strsql);
			if($record){
				if($queryModel = "All"){
                                    $record->setFetchMode(PDO::FETCH_ASSOC);
                                    $result = $record->fetchAll();
				}elseif($queryModel = "row"){
                                    $result = $record->fetch();
				}
			}else{
				$result = null;
			}
			return $result;
	 }

	public function select($sql){
            $result=$this->pdo->query($sql);
            $rs=$result->fetch();
            return $rs[0];
        } 
     //更改数据  
        public function updt($sql) {  
            if(($rows = $this->pdo->exec($sql)) > 0) {  
                //$this->getPDOError(); 
                echo "<script>aler('yes')</script>";
                return $rows;  
            }else{
                echo "<script>aler('no')</script>";
               
            } 
        } 
   
     
   

	//插入数据  
        public function insert($sql) {  
            return $this->pdo->exec($sql);
   ;  
           
        }
	
	//删除数据  
        public function delt($sql){  
		$rows = $this->pdo->exec($sql);
                //$this->getPDOError();  
                return $rows;  
            
        }  
	

	/*
	*pdo连接数据库
	*/
	private function CONNECT(){
		
		$this->dsn="$this->dbms:host=$this->host;dbname=$this->dbname";
		try{
			$this->pdo=new PDO($this->dsn,$this->user,$this->pwd);
                        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        
		}
		catch(PDOExcepton $e){
			die("Erro!:" . $e->getMessage());
		}
		

	}
	/*
	*设置字符集
	*/
	private function CHARSET(){
		$sql = "set names $this->charset";
		$this->pdo->exec($sql);

	}


	
	

	public function init($arr){
		$this->dbms=isset($arr['dbms']) ? $arr['dbms'] : 'mysql';
		$this->dbname=isset($arr['dbname']) ? $arr['dbname'] : 'db1';
		$this->user=isset($arr['user']) ? $arr['user'] : 'root';
		$this->pwd=isset($arr['pwd']) ? $arr['pwd'] : '';
		$this->host = isset($arr['host']) ? $arr['host'] : '127.0.0.1';
	}


/**
     * getPDOError 捕获PDO错误信息
     */
    private function getPDOError()
    {
        if ($this->pdo->errorCode() != '00000') {
            $arrayError = $this->pdo->errorInfo();
            $this->outputError($arrayError[2]);
        }
    }

	//__sleep方法，序列化对象时调用
	public function __sleep() {
		//返回一个数组，数组内的元素为需要被序列化的属性名的集合
		return array('dbms','dbname','user','pwd','host','charset');
		//return $this;
	}

	/**
     * 防止克隆
     *
     */
    private function __clone() {}

	//__wakeup方法，在反序列化一个对象时自动调用

	public function __wakeup(){
		//数据库相关初始化操作
		/*$this->int($arr);
		$dsn="$this->dbms:host=$this->host;dbname=$this->dbname";
		try{
		$this->pdo=new PDO($dsn,$this->user,$this->pwd);
		$sql=" SET NAMES 'utf8'";
		if ($this->pdo->query($sql)==true) {
			echo "<script>alert('成功');</script>";
		}
		
		}
		catch(PDOExcepton $e){
			die("Erro!:" . $e->getMessage());
		}
	}*/

}

}

?>