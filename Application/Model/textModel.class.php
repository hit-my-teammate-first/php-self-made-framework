<?php
class textModel extends Model{
	/**
	*��ȡ����
	*/
	public function getList() {
		$rs=$this->db->query("select * from rundb1");
		return $rs;
		
	}

	public function deltext($id){
		$sql="delete from rundb1 where run_id = $id";
		$rs=$this->db->delt($sql);
		return $rs;

	}
}
