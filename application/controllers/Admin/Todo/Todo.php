<?php

class Todo extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('Todo_service');
        $this->data['headTitle'] = "Yapılacak İşlemler";
    }

    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && $this->validateForm()) {
            $todo = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'date' => $this->input->post('date'),
            ];
            $insert = $this->todo_service->insert($todo);
            if ($insert)
            {
                $this->session->set_flashdata('successMessage', 'İşlem başarılı.');
            } else {
                $this->session->set_flashdata('errorMessage', 'Bir hata oluştu. Lütfen tekrar deneyin!');
            }
            redirect(base_url('admin/todo/add'));
        }
        $this->getForm();
    }

    public function edit($id)
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && $this->validateForm()) {
            $todo = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'date' => $this->input->post('date'),
            ];
            $update = $this->todo_service->update($id,$todo);
            if ($update)
            {
                $this->session->set_flashdata('successMessage', 'İşlem başarılı.');
            } else {
                $this->session->set_flashdata('errorMessage', 'Bir hata oluştu. Lütfen tekrar deneyin!');
            }
            redirect(base_url('admin/todo/edit/'.$id));
        }
        $this->getForm($id);
    }

    public function delete($id)
    {
        $this->todo_service->delete($id);
        redirect('admin/todo/list');
    }

    public function getList()
    {
        $this->data['breadcrumb'] = "Yapılacaklar Listesi";
        $this->data['todoList'] = $this->todo_service->findAll();
        $this->load->view("admin/todo/list");
    }

    private function getForm($id = NULL)
    {
        $todo = [];
        if (!empty($id)) {
            $this->data['formAction'] = base_url('admin/todo/edit/'.$id);
            $this->data['breadcrumb'] = "Yapılacak İş Düzenleme";
            $todo = (array)$this->todo_service->find($id);
        } else {
            $this->data['breadcrumb'] = "Yapılacak İş Ekle";
            $this->data['formAction'] = base_url('admin/todo/add');
        }

        $this->setFormField("title",$todo);
        $this->setFormField("description",$todo);
        $this->setFormField("date",$todo);

        $this->load->view("admin/todo/form");
    }

    protected function getFormRules()
    {
        return [
            [
                'field' => 'title',
                'label' => 'Başlık',
                'rules' => 'required'
            ],
            [
                'field' => 'description',
                'label' => 'Açıklama',
                'rules' => 'required'
            ],
            [
                'field' => 'date',
                'label' => 'Tarih',
                'rules' => 'required'
            ]
        ];
    }
}
