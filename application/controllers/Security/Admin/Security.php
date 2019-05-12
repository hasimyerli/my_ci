<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_hash'] = $this->security->get_csrf_hash();
        $this->load->service('User_service');
    }

    public function login()
    {
        $this->data['title'] = 'Admin Panel | Login';

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $username = $this->input->post('username');
            $password = $this->cryptTo($this->input->post('password'));
            $user = $this->user_service->findOneBy(['username' => $username, 'password' => $password]);
            if($user){
                $array = array(
                    'username' => $user->username,
                    'login' => TRUE
                );
                $this->session->set_userdata($array);
                redirect(base_url('admin/dashboard'));
            }else{
                $this->data['errorMessage'] = "Lütfen bilgilerinizi kontrol edin!";
            }
            $this->load->view('admin/login/index');
        }else{
            $this->load->view('admin/login/index');
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('admin/login'));
    }

    public static function cryptTo($value)
    {
        $ci = &get_instance();
        $data = sha1( $ci->config->item('encryption_key') . $value . 'privateHash');
        return $data;
    }

}
