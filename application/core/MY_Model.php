<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    protected function isChangedRows()
    {
        return ($this->getQueryInfo()->rows_matched > 0 && $this->getQueryInfo()->changed === 0);
    }

    protected function getQueryInfo()
    {
        //return value mysqli->info
        return $this->parseQueryInfo($this->db->conn_id->info);
    }

    private function parseQueryInfo($info)
    {
        $queryInfo = new stdClass();
        $queryInfo->rows_matched = 0;
        $queryInfo->changed = 0;
        $queryInfo->warnings = 0;

        //$result = "Rows matched: 1  Changed: 0  Warnings: 0"
        $result = explode("  ",$info);
        foreach ($result as $key => $item) {
            $row = explode(": ", $item);
            if ($key === 0) $queryInfo->rows_matched = (int)$row[1];
            if ($key === 1) $queryInfo->changed = (int)$row[1];
            if ($key === 2) $queryInfo->warnings = (int)$row[1];
        }
        return $queryInfo;
    }
}

abstract class AbstractModel extends MY_Model {

    //table name
    protected $table = "";

    public function __construct($table)
    {
    parent::__construct();
    $this->table = $table;
    }

    public function insert($data)
  {
    $this->db
      ->insert($this->table, $data);
    return $this->db->insert_id();
  }

    public function update($id, $data)
    {
        $this->db
            ->where("id", $id)
            ->update($this->table, $data);
        $result = ($this->isChangedRows()) ? 1 : $this->db->affected_rows();
        return ($result > 0);
    }

    public function delete($id)
    {
    return $this->db
      ->where('id', $id)
      ->delete($this->table);
    }

    public function find($id, $field = [])
    {
    return $this->db
      ->select(($field) ? $field : '*')
      ->where('id', $id)
      ->get($this->table)
      ->row();
    }

    public function findAll($field = [], $orderBy = null)
    {
    return $this->db
      ->select(($field) ? $field : '*')
      ->order_by($orderBy)
      ->get($this->table)
      ->result();
    }

    public function findBy($where, $field = [], $orderBy = null)
    {
    return $this->db
      ->select(($field) ? $field : '*')
      ->where($where)
      ->order_by($orderBy)
      ->get($this->table)
      ->result();
    }

    public function findOneBy($where, $field = [], $orderBy = null)
    {
    return $this->db
      ->select(($field) ? $field : '*')
      ->where($where)
      ->order_by($orderBy)
      ->get($this->table)
      ->row();
    }

}
