<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Service {
    public function __construct()
    {
        log_message('debug', "Service Class Initialized");
    }
    function __get($key)
    {
        $CI =& get_instance();
        return $CI->$key;
    }
}

abstract class AbstractService extends MY_Service {

    protected $model;

    public function __construct($modelName)
    {
        parent::__construct();
        $this->load->model($modelName);
        $this->model = $this->{$modelName};
    }

    abstract protected function getModel();

    public function insert($data) {
        return $this->model->insert($data);
    }

    public function update($id, $data) {
        return $this->model->update($id,$data);
    }

    public function delete($id) {
        return $this->model->delete($id);
    }

    public function find($id, $field = [])
    {
        return $this->model->find($id, $field);
    }

    public function findAll($field = [], $orderBy = null)
    {
        return $this->model->findAll($field, $orderBy);
    }

    public function findOneBy($where, $field = [], $orderBy = null)
    {
        return $this->model->findOneBy($where, $field, $orderBy);
    }

    public function findBy($where, $field = [], $orderBy = null)
    {
        return $this->model->findBy($where, $field, $orderBy);
    }
}
