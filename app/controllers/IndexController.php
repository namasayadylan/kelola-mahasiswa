<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('totalMahasiswa', Mahasiswa::count());
        $this->view->setVar('totalProdi', Prodi::count());
    }
}
