<?php
include 'Page.php';

class ErrorPage extends Page
{
    public function index()
    {
        $this->data['headTitle'] = "Erişim İzniniz Yok";
        $this->data['breadcrumb'] = "Sayfaya Erişim Kısıtlandı";
        $this->load->view("admin/page/errors/e_403.twig");
    }
}