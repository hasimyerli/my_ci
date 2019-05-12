<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Todo_model extends AbstractModel {

    public function __construct()
    {
        //table name
        parent::__construct("todo");
    }

    public function getTodoStatistics()
    {
        return $this->db
            ->select('(select count(*) from todo AS t1 WHERE t1.DATE < DATE_FORMAT(NOW(), "%Y-%m-%d") ) as past',FALSE)
            ->select('(select count(*) from todo t2 WHERE t2.DATE = DATE_FORMAT(NOW(), "%Y-%m-%d") ) as today',FALSE)
            ->select('(select count(*) from todo t3 WHERE t3.DATE > DATE_FORMAT(NOW(), "%Y-%m-%d") ) as future',FALSE)
            ->get()
            ->row();
    }
}
