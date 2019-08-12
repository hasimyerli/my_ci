<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroup_model extends AbstractModel {

    public function __construct()
    {
        //table name
        parent::__construct("user_group");
    }
}
