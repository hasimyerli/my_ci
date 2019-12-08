<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('Module_service');
        $this->data['headTitle'] = "Modül Yönetimi";
    }

    public function getList()
    {
        $this->data['breadcrumb'] = "Modül Listesi";
        $this->data['modules'] = $this->module_service->findAll();
        $this->load->view("admin/module/list");
    }

    public function edit($id)
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $moduleData = [
                'description' => $this->input->post('moduleName'),
                'is_completed' => 1
            ];
            $updateModule = $this->module_service->update($id, $moduleData);
            if ($updateModule) {
                $moduleActions = $this->input->post('moduleActions');
                foreach ($moduleActions as $key => $moduleAction) {
                    $moduleActionId =  $key;
                    $moduleActionData = [
                        'description' => $moduleAction,
                        'is_completed' => 1
                    ];
                    $updateModuleAction = $this->module_service->updateModuleActions($moduleActionId, $moduleActionData);
                    if ($updateModuleAction)
                    {
                        $this->session->set_flashdata('successMessage', 'İşlem başarılı.');
                    } else {
                        $this->session->set_flashdata('errorMessage', 'Bir hata oluştu. Lütfen tekrar deneyin!');
                    }
                }
            } else {
                $this->session->set_flashdata('errorMessage', 'Bir hata oluştu. Lütfen tekrar deneyin!');
            }
            redirect(base_url('admin/module/edit/'.$id));
        }
        $this->getForm($id);
    }

    private function getForm($id = NULL)
    {
        $this->data['module'] = [];
        $this->data['moduleActions'] = [];
        if (!empty($id)) {
            $this->data['formAction'] = base_url('admin/module/edit/'.$id);
            $this->data['breadcrumb'] = "Modül Düzenleme";
            $this->data['module'] = (array)$this->module_service->find($id);
            $this->data['moduleActions'] = (array)$this->module_service->getModuleActions($id);
        }

        $this->load->view("admin/module/form");
    }

    /* file sistemi ile modüllerin veritabanına eklenmesi*/
    public function refreshModules()
    {
        $modules = $this->getFileModules();
        $this->module_service->refreshModule($modules);
        redirect(base_url('admin/modules'));
    }

    private function getFileModules()
    {
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
            $controllerPath = $this->getControllerPath($file);
            $controllerName = $this->getControllerName($controllerPath);
            $configFile = strrpos($controllerPath, '/Config/route');
            if (!$configFile) {
                if (!in_array($controllerPath, $this->getIgnoredPermission())) {
                    include "$file";
                    $permissionList[$controllerPath] = $this->getModuleActions($controllerName);
                }
            }
        }
        return $permissionList;
    }
}
