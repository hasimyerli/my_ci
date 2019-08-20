<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends AbstractModel {

    public function __construct()
    {
        //table name
        parent::__construct("user");
    }

    public function getUser($userId)
    {
        return $this->db
            ->select('u.*, ug.modules, ug.name as role')
            ->from('user u')
            ->where(['u.id' => $userId, 'isActive' => 1])
            ->join('user_group ug', 'u.user_group_id = ug.id', 'inner')
            ->get()
            ->row();
    }

}
