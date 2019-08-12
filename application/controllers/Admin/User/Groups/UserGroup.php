<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroup extends Admin_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->service('UserGroup_service');
    }

    public function getList()
    {
        $this->data['groups'] = $this->usergroup_service->findAll();
        $this->load->view("admin/user/group/list");
    }

    public function edit($id)
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && $this->validateForm()) {
            $group = [
                'name' => $this->input->post('name'),
                'permission' => json_encode($this->input->post('hasPermissions')),
            ];
            $update = $this->usergroup_service->update($id,$group);
            if ($update)
            {
                $this->session->set_flashdata('successMessage', 'İşlem başarılı.');
            } else {
                $this->session->set_flashdata('errorMessage', 'Bir hata oluştu. Lütfen tekrar deneyin!');
            }
            redirect(base_url('admin/user/group/edit/'.$id));
        }
        $this->getForm($id);
    }

    private function getForm($id = NULL)
    {
        $group = [];
        if (!empty($id)) {
            $this->data['formAction'] = base_url('admin/user/group/edit/'.$id);
            $this->data['breadcrumb'] = "Grup Düzenleme";
            $group = (array)$this->usergroup_service->find($id);
        } else {
            $this->data['breadcrumb'] = "Grup Ekleme";
            $this->data['formAction'] = base_url('admin/user/group/add');
        }

        $hasPermission = [
            'hasPermissions' => json_decode($group['permission'],true)
        ];

        $this->data['permissions'] = $this->getPermissionList();

        $this->setFormField("name", $group);
        $this->setFormField("hasPermissions", $hasPermission);

        $this->load->view("admin/user/group/add_edit");
    }

    protected function getFormRules()
    {
        return [
            [
                'field' => 'name',
                'label' => 'Grup İsmi',
                'rules' => 'required'
            ]
        ];
    }

    private function getPermissionList()
    {
        //TODO:: Bu kısım file yerine db den gelecek

        $path = ['application/controllers/Admin/*'];
        $files = [];
        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                if (is_file($file)) {
                    $files[] = $file;
                }
            }
        }

        $permissionList = [];

        foreach ($files as $key =>  $file) {
            $controller = substr($file, strlen('application/controllers/'));
            $configFile = strrpos($controller, '/Config/route.php');
            if (!$configFile) {
                $permission = substr($controller, 0, strrpos($controller, '.'));
                if (!in_array($permission, $this->getIgnoredPermission())) {
                    $permissionList[] = $permission;
                }
            }
        }
        return $permissionList;
    }
}
