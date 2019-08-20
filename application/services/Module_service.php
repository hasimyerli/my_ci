<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_service extends AbstractService {
    public function __construct()
    {
        parent::__construct("module_model");
    }

    public function getModel()
    {
        return $this->model;
    }

    public function updateModuleActions($id, $moduleActionData) {
        return $this->model->updateModuleActions($id, $moduleActionData);
    }

    public function addModuleActions($moduleId) {
        return $this->model->addModuleActions($moduleId);
    }

    public function getModuleActions($moduleId) {
       return $this->model->getModuleActions($moduleId);
    }

    public function refreshModule($modules)
    {
        foreach ($modules as $moduleKey => $module) {
            $name = explode("/", $moduleKey);
            $moduleData = [
                'name' => end($name),
                'unique_name' => $moduleKey,
                'description' => end($name)
            ];
            try {
                $moduleId = $this->insert($moduleData);
                if ($moduleId) {
                    foreach ($module as $moduleActionKey => $moduleAction) {
                        $moduleActionData = [
                            'module_id' => $moduleId,
                            'name' => $moduleAction,
                            'description' => $moduleAction
                        ];
                        $this->model->addModuleAction($moduleActionData);
                    }
                }

            } catch (\Exception $e) {
                //TODO:: Session error message
            }
        }
    }
}
