<?php

class MY_Controller extends CI_Controller {

    protected $data = [];
    protected $error = [];

    public function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(true);
        $this->data['csrfName'] = $this->security->get_csrf_token_name();
        $this->data['csrfToken'] = $this->security->get_csrf_hash();
        $this->getFlasMessage();
    }

    public function getData()
    {
        return $this->data;
    }

    protected function getFlasMessage()
    {
        if (!empty($this->session->flashdata('successMessage')))
        {
            $this->data['successMessage'] = $this->session->flashdata('successMessage');

        } elseif (!empty($this->session->flashdata('errorMessage')))
        {
            $this->data['errorMessage'] = $this->session->flashdata('errorMessage');
        }
    }
    protected function loadFormValidation($style = true)
    {
        $this->load->library('form_validation');
        if ($style === true) {
            $this->form_validation->set_error_delimiters('<div> - ', '</div>');
        }
    }

    /**
     * Override method
     *
     */
    protected function getFormRules(){}

    private function setFormRules()
    {
        $this->form_validation->set_rules($this->getFormRules());
    }

    protected function validateForm()
    {
        $this->loadFormValidation();
        $this->setFormRules();
        if ($this->form_validation->run() != TRUE)
        {
            $this->data['validationErrors'] = validation_errors();
            return false;
        }
        return true;
    }

    protected function setFormField($field, $data, $default = "")
    {
        if (set_value($field))
        {
            $this->data[$field] = set_value($field);
        }
        else if (!empty($data[$field]))
        {
            $this->data[$field] = $data[$field];
        } else {
            $this->data[$field] = $default;
        }
    }

}

class Admin_Controller extends MY_Controller {

    private $user;

    public function __construct() {

        parent::__construct();

        $this->load->service('User_service');

        $this->user = $this->session->userdata('user');

        if(empty($this->user) || (isset($this->user) && $this->user['isLogin'] != TRUE)) {
            redirect(base_url('admin/login'));
        }
        $this->hasPermission();
    }

    private function hasPermission($key="access")
    {
        $permissions = $this->getUserPermissions();
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $key="modify";
        }
        if(!in_array($this->getRequiredPermission(), $this->getIgnoredPermission()) && (!isset($permissions[$key]) || !in_array($this->getRequiredPermission(), $permissions[$key]))) {
            redirect(base_url('admin/page/error/permission'));
        }
    }

    private function getUserPermissions()
    {
        $user = $this->user_service->getModel()->getUser($this->user['id']);
        return json_decode($user->permission,true);
    }

    private function getRequiredPermission()
    {
        $permission = "";
        try {
            $reflection = new ReflectionClass(get_called_class());
            $prefix = "Admin";
            $dirName = dirname($reflection->getFileName());
            $moduleName = substr($dirName, strrpos($dirName, $prefix),strlen($dirName));
            $className = get_class($this);
            $permission =  str_replace("\\","/",$moduleName)."/".$className;
;        } catch (ReflectionException $e) {}

        return $permission;
    }

    protected function getIgnoredPermission()
    {
        return [
            'Admin/User/Groups/UserGroup',
            'Admin/Page/ErrorPage'
        ];
    }

}

class Security_Controller extends MY_Controller {

    public function __construct() {

        parent::__construct();
    }

}