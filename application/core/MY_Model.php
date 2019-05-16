<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
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
    return ($this->db->trans_status() || $this->db->affected_rows());
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
