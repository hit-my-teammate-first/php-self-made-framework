<?php
/*
 * class SessionLib{

        private $db;
        
        public function __construct(){
            $this->SessSave();
        }
        public function SessSave(){
            session_set_save_handler(
		array($this,'open'),
		array($this,'close'),
                array($this,'read'),
                array($this,'write'),
                array($this,'destroy'),
                array($this,'gc')
	);
        } 
       
        /*
	*打开会话
	*/
	/*public function open(){
            $a=array();
            $this->db =ConnDB::getInstance($a);
                
	}
	/*
	*关闭会话
	*/
	/*public function close(){
		return true;
	}
	/*
	*读取会话
	*@param $sess_id string 会话编号
	*/
	/*public function read($sess_id){
		$sql = "select sess_value from sess where sess_id='$sess_id'";
                $rs=$this->db->query($sql,row);
                return $rs;

	}
	/*
	*写入会话
	*@param $sess_id string 会话编号
	*@param $sess_value string 会话值
	*/
	/*public function write($sess_id,$sess_value){
		$time=time();
                $sql="replace into `session` values('$sess_id','$sess_value','$time')";
		//$sql="insert into sess values('$sess_id','$sess_value',$time) on duplicate key update sess_value='$sess_value'";
                $rs=$this->db->insert($sql);
                return $rs;
	}
    /*
	*销毁会话
	*/
	/*public function destroy($sess_id){
		$sql = "delete from sess where sess_id = '$sess_id'";
                return $this->db->delt($sql);
	}
	/*
	*垃圾回收
	*/
	/*public function gc($maxlifetime){
		$time=time()-$maxlifetime;
		$sql = "delete from sess where sess_expires<$time";
                return $this->db->delt($sql);
	}
	
        
}
*/
class SessionLib{
	private $dbms;
	private $dbname;
	private $user;
	private $pwd;
	private $host;
	private $charset;
	private $dsn;
	private $dbh;
	private $arr=array();

        /*
         * 构造函数
         */
        public function __construct(){
            $this->SessSave();
        }
        /*
         * session入库函数
         */
        public function SessSave(){
            session_set_save_handler(
		array($this,'open'),
		array($this,'close'),
                array($this,'read'),
                array($this,'write'),
                array($this,'destroy'),
                array($this,'gc')
            );
        } 
       
        /*
	*打开会话
	*/
	public function open($arr){
            $this->init($arr);
            $this->CONNECT();
            $this->CHARSET();

                
	}
	/*
	*关闭会话
	*/
	public function close(){
            return true;
	}
	/*
	*读取会话
	*@param $sess_id string 会话编号
	*/
	public function read($sess_id){
            $sth = $this->dbh->prepare("SELECT sess_value FROM sess WHERE sess_id = ?");
            $sth->execute(array($sess_id));
            $row = $sth->fetch(PDO::FETCH_NUM);
            if(count($row) == 0) {
                return '';
            } else {
                    return $row[0];
            }

	}
	/*
	*写入会话
	*@param $sess_id string 会话编号
	*@param $sess_value string 会话值
	*/
	public function write($sess_id,$sess_value){
            //date_default_timezone_set('PRC');
            $time = time();
            $sql="insert into sess values('$sess_id','$sess_value','$time') on duplicate key update sess_value='$sess_value'";
            $rs=$this->dbh->query($sql);
            return $rs;
            
            /*
             * $sth = $this->dbh->prepare("UPDATE sess SET sess_id = ?,
            
                sess_value = ? WHERE sess_expires= ?");
            $sth->execute(array($sess_id, $sess_value, $now));
            if($sth->rowCount() == 0) {
                $sth2 = $this->dbh->prepare("INSERT INTO sess (sess_id, sess_value, sess_expires) VALUES (?,?,?)");
                $sth2->execute(array($sess_id, $sess_value, $now));
            }*/
	}
        /*
	*销毁会话
	*/
	public function destroy($sess_id){
            $sth = $this->dbh->prepare("DELETE FROM sess WHERE sess_id = ?");
            $sth->execute(array($sess_id));
            return true;
	}
	/*
	*垃圾回收
	*/
	public function gc($maxlifetime){
                $time=time()-$maxlifetime;
		$sql = "delete from sess where sess_expires<$time";
                return $this->dbh->delt($sql);
	}

	/*
	*pdo连接数据库
	*/
	public function CONNECT(){
		
            $this->dsn="$this->dbms:host=$this->host;dbname=$this->dbname";
            try{
                $this->dbh=new PDO($this->dsn,$this->user,$this->pwd);
                $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            }
            catch(PDOExcepton $e){
                die("Erro!:" . $e->getMessage());
            }
	}
        /*
         * 初始化连接参数
         */
	public function init($arr){
            $this->dbms=isset($arr['dbms']) ? $arr['dbms'] : 'mysql';
            $this->dbname=isset($arr['dbname']) ? $arr['dbname'] : 'db1';
            $this->user=isset($arr['user']) ? $arr['user'] : 'root';
            $this->pwd=isset($arr['pwd']) ? $arr['pwd'] : '';
            $this->host = isset($arr['host']) ? $arr['host'] : '127.0.0.1';
	}

	/*
	*设置字符集
	*/
	public function CHARSET(){
            $sql = "set names $this->charset";
            $this->dbh->exec($sql);

	}
        
}


