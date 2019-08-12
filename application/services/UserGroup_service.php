<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroup_service extends AbstractService {
    public function __construct()
    {
        parent::__construct("userGroup_model");
    }

    public function getModel()
    {
        return $this->model;
    }
}
