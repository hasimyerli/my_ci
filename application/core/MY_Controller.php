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
        $this->getMessage();
    }

    public function getData()
    {
        return $this->data;
    }

    protected function getMessage()
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
    public function __construct() {
        parent::__construct();
        if($this->session->userdata('login') != TRUE){
            redirect(base_url('admin/login'));
        }
    }
}