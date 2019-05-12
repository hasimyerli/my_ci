<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends AbstractModel {

    public function __construct()
    {
        //table name
        parent::__construct("user");
    }

}
