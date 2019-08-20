<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_model extends AbstractModel {

    public function __construct()
    {
        //table name
        parent::__construct("module");
    }

    public function addModuleAction($data)
    {
        $this->db
            ->insert('module_action', $data);
        return $this->db->insert_id();
    }

    public function updateModuleActions($id, $moduleActionData) {
        $this->db
            ->where(['id' => $id])
            ->update('module_action', $moduleActionData);
        $result = ($this->isChangedRows()) ? 1 : $this->db->affected_rows();
        return ($result > 0);
    }

    public function getModuleActions($moduleId)
    {
        return $this->db
            ->select('*')
            ->from('module_action')
            ->where(['module_id' => $moduleId])
            ->get()
            ->result();
    }

}
