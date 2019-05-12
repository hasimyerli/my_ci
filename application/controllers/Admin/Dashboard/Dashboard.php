<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('Todo_service');
        $this->data['headTitle'] = "Dashboard";
    }

	public function index()
	{
        $this->data['title'] = '- Dashboard';
        $this->data['statistics'] = $this->todo_service->getModel()->getTodoStatistics();
        $this->load->view('admin/dashboard/index');
	}
}
