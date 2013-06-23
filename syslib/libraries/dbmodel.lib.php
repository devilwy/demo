<?php
require_once BASEPATH . '/core/Model.php';
require_once SYSLIB.'/libraries/mem.lib.php';

class DbModel extends CI_Model{

	public $dbr;
	public $dbw;
	public $tableName = '';

	function __construct(){
		parent::__construct();
		$this->dbr = $this->load->database('dbr', true);
		$this->dbw = $this->load->database('dbw', true);
	}

	/**
	 * 查询
	 * @deprecated
	 */
	public function querySQL($sql){
		$_sql = strtolower(trim($sql));
		if(strpos($_sql,'select') !== 0){
			return false;
		}
		$list = $this->dbr->query($sql)->result_array();
		return $list;
	}

	public function queryUpdateSQL($sql){
		$_sql = strtolower(trim($sql));
		return $this->dbw->query($sql);
	}

	/**
	 * 增加记录
	 * @param $data array
	 * @return integer
	 */
	protected function insertData($data , $tableName = ''){
		$this->setTableName($tableName);
		$this->dbw->insert($this->tableName,$data);
		$insert_id = $this->dbw->insert_id();
		if($insert_id==0){
			$affect = $this->dbw->affected_rows();
			if ($affect > 0) {
				return true;
			}else{
				return false;
			}
		}
		return $this->dbw->insert_id();
	}

	/**
	 * 更新
	 * @param $data array
	 * @param $where mix array or string
	 * @return integer
	 */
	protected function updateData($data , $where , $tableName = ''){
		$this->setTableName($tableName);
		$this->dbw->update($this->tableName, $data, $where);
		return $this->dbw->affected_rows();
	}

	/**
	 * 删除
	 * @param $where mix array or string
	 * @return integer
	 */
	protected function deleteData($where , $tableName = ''){
		$this->setTableName($tableName);
		$this->dbw->delete($this->tableName, $where);
		return $this->dbw->affected_rows();
	}

	/**
	 * 获取单行记录
	 * @param $where mix array or string
	 * @return array
	 */
	protected function getOne($where , $tableName = ''){
		$this->setTableName($tableName);
		$row = $this->dbr->from($this->tableName)->where($where)->limit(1)->get()->row_array();
		return $row;
	}

	/**
	 * 获取多行记录
	 * @param $where mix array or string
	 * @param $order string 
	 * @param $limit array array(10,20)=>limit 20,10
	 * @return array
	 */
	protected function getList($where = NULL, $order = NULL ,$limit = NULL , $tableName = ''){
		$this->setTableName($tableName);
		$this->dbr->from($this->tableName);
		if (!empty($where)) {
			$this->dbr->where($where);
		}
		if(!empty($order)){
			$this->dbr->order_by($order);
		}
		if(!empty($limit)){
			$this->dbr->limit($limit['0'],$limit['1']);
		}
		$query = $this->dbr->get();
		return $query->result_array();
	}

	protected function getCount($where , $tableName = ''){
		$this->setTableName($tableName);
		$sql = "select count(*) totalcount from ".$this->tableName . $where;
		$totalcount = $this->dbr->query($sql)->row_array();
		return is_array($totalcount) ? $totalcount['totalcount']:0;
	}
	
	public function selectField($feild){ 
		$this->dbr->select($feild);
	}

	/**
	 * 根据条件统计记录行数，并重命名
	 * @param $where mix array or string 
	 * @param $field string 新的字段名称
	 * @return array
	 */
	protected function getSum($where,$field , $tableName = ''){
		$this->setTableName($tableName);
		$this->dbr->from($this->tableName);
		if (!empty($where)) {
			$this->dbr->where($where,null,false);
		}
		$this->dbr->select_sum($field);
		$query = $this->dbr->get();
		return $query->row_array();
	}
	
	private function setTableName($tableName = ''){ 
		$this->tableName = $tableName;
	}
	protected function setCache($key, $val){
		$options = array('flag'=>0,'expire'=>0);
		return Mem::set($key, $val, $options);
	}

	protected function getCache($key){
		return Mem::get($key);
	}

	protected function delCache($key){
		return Mem::delete($key);
	}

	public function lastQuery(){
		return $this->dbr->last_query();
	}
}