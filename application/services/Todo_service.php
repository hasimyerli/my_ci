<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Todo_service extends AbstractService {
    public function __construct()
    {
        parent::__construct("todo_model");
    }

    public function getModel()
    {
        return $this->model;
    }
}
