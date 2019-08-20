<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroup extends Admin_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->service('UserGroup_service');
        $this->load->service('Module_service');
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
                'modules' => json_encode($this->input->post('moduleData'))
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
        $moduleData = [];
        $group = [];
        if (!empty($id)) {
            $this->data['formAction'] = base_url('admin/user/group/edit/'.$id);
            $this->data['breadcrumb'] = "Grup Düzenleme";
            $group = (array)$this->usergroup_service->find($id);
            $modules = (array)$this->module_service->findAll();
            foreach ($modules as $module) {
                $moduleData[$module->id]['module'] = $module;
                $moduleActions = (array)$this->module_service->getModuleActions($module->id);
                $moduleData[$module->id]['actions'] = $moduleActions;
            }
        } else {
            $this->data['breadcrumb'] = "Grup Ekleme";
            $this->data['formAction'] = base_url('admin/user/group/add');
        }

        $hasModule = (array)json_decode($group['modules']);

        $this->setFormField("moduleData",['moduleData' => $moduleData]);
        $this->setFormField("hasModule", ['hasModule' => $hasModule]);
        $this->setFormField("name", $group);

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
}
