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

        preg_match_all('/-?[0-9]+/',$info, $result);

        $queryInfo->rows_matched = (int)$result[0][0];
        $queryInfo->changed = (int)$result[0][1];
        $queryInfo->warnings = (int)$result[0][2];

        return $queryInfo;
    }

    private function parseQueryInfo_v2($info)
    {
        $queryInfo = new stdClass();

        //$result = "Rows matched: 1  Changed: 0  Warnings: 0"
        $result = explode("  ",$info);
        $row = [];
        foreach ($result as $key => $item) {
            $row[$key] = (int)explode(": ", $item)[1];
        }

        $queryInfo->rows_matched = $row[0];
        $queryInfo->changed = $row[1];
        $queryInfo->warnings = $row[2];
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
        /**
         * referans : http://www.mysqltutorial.org/mysql-insert-ignore/
         * INSERT > INSERT IGNORE
         */
        $insert_query = $this->db->insert_string($this->table, $data);
        $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
        $this->db->query($insert_query);
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
