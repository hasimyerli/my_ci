<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_service extends AbstractService {
    public function __construct()
    {
        parent::__construct("user_model");
    }

    public function getModel()
    {
        return $this->model;
    }
}
