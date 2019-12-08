<?php

class MY_Controller extends CI_Controller {

    protected $data = [];
    protected $error = [];

    public function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(false);
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

    public function __construct()
    {
        parent::__construct();
        $this->load->service('User_service');
        $this->user = $this->session->userdata('user');
        $this->checkLogin();
        $this->hasPermission();
    }

    /**
     * Login control
     */
    private function checkLogin()
    {
        if(empty($this->user) || (isset($this->user) && $this->user['isLogin'] != TRUE)) {
            redirect(base_url('admin/login'));
        }
    }

    /**
     * Admin değil ise yetki kontrolü yapılır.
     * Admin ise herşeye erişebilir
     */
    protected function hasPermission()
    {
        $user = $this->getUser();
        if ($user->role != 'admin') {
            $userModules = json_decode($user->modules,true);
            $permission = $this->router->fetch_method();
            $requiredModule = $this->getRequiredModule();
            if (!isset($userModules[$requiredModule]) || !in_array($permission, $userModules[$requiredModule])) {
                redirect(base_url('admin/page/error/permission'));
            }
        }
    }

    private function getUser()
    {
        return $this->user_service->getModel()->getUser($this->user['id']);
    }

    private function getRequiredModule()
    {
        $module = "";
        try {
            $reflection = new ReflectionClass(get_called_class());
            $prefix = "Admin";
            $dirName = dirname($reflection->getFileName());
            $moduleName = substr($dirName, strrpos($dirName, $prefix),strlen($dirName));
            $className = get_class($this);
            $module =  str_replace("\\","/",$moduleName)."/".$className;
        } catch (ReflectionException $e) {
            //TODO:: Session error message
        }
        return $module;
    }

    protected function getIgnoredPermission()
    {
        return [
            'Admin/Settings/Module/Module', //Because the name is already declared
            'Admin/Page/Page', // Parent class
            'Admin/Page/ErrorPage' //public
        ];
    }

    protected function getModuleActions($controllerName)
    {
        $moduleActions = [];
        $class = new ReflectionClass($controllerName);
        $className = $class->name;
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if ($method->class == $className && $method->name != '__construct') {
                $moduleActions[] = $method->name;
            }
        }
        return $moduleActions;
    }

    protected function getControllerName($controllerPath)
    {
        $controllerName = explode("/", $controllerPath);
        return end($controllerName);
    }

    protected function getControllerPath($file)
    {
        $controllerPath = substr($file, strlen('application/controllers/'));
        $controllerPath = substr($controllerPath, 0, strrpos($controllerPath, '.'));
        return $controllerPath;
    }
}

class Security_Controller extends MY_Controller {

    public function __construct() {

        parent::__construct();
    }

}
